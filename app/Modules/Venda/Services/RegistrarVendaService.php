<?php

namespace App\Modules\Venda\Services;

use App\Models\Produto;
use App\Models\Venda;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RegistrarVendaService
{
    /**
     * Persiste as vendas e decrementa o estoque dentro de uma transação.
     *
     * @param Collection $itens  [{produto_id: int, quantidade: int}]
     */
    public function persistir(
        Collection $itens,
        float      $descontoPedido,
        string     $dataVenda,
        int        $empresaId,
        int        $lojaId,
        string     $erroEstoqueCampo = 'quantidade',
    ): void {
        DB::transaction(function () use ($itens, $descontoPedido, $dataVenda, $empresaId, $lojaId, $erroEstoqueCampo): void {
            $linhas        = collect();
            $subtotalPedido = 0.0;

            foreach ($itens as $item) {
                $produto = Produto::query()
                    ->where('empresa_id', $empresaId)
                    ->where('loja_id', $lojaId)
                    ->lockForUpdate()
                    ->findOrFail($item['produto_id']);

                $quantidade = (int) $item['quantidade'];

                if ($produto->quantidade < $quantidade) {
                    throw ValidationException::withMessages([
                        $erroEstoqueCampo => 'Estoque insuficiente para o produto: '.$produto->nome.'.',
                    ]);
                }

                $precoUnitario  = (float) $produto->preco_unitario;
                $subtotalItem   = round($precoUnitario * $quantidade, 2);
                $subtotalPedido += $subtotalItem;

                $linhas->push([
                    'produto'        => $produto,
                    'quantidade'     => $quantidade,
                    'preco_unitario' => $precoUnitario,
                    'subtotal'       => $subtotalItem,
                ]);
            }

            $subtotalPedido = round($subtotalPedido, 2);

            if ($descontoPedido > $subtotalPedido) {
                throw ValidationException::withMessages([
                    'desconto' => 'O desconto não pode ser maior que o subtotal da venda.',
                ]);
            }

            $descontoRestante = $descontoPedido;

            foreach ($linhas as $index => $linha) {
                $produto = $linha['produto'];
                $produto->decrement('quantidade', $linha['quantidade']);

                $descontoItem = 0.0;
                if ($descontoPedido > 0 && $subtotalPedido > 0) {
                    if ($index === $linhas->count() - 1) {
                        $descontoItem = round($descontoRestante, 2);
                    } else {
                        $proporcao    = $linha['subtotal'] / $subtotalPedido;
                        $descontoItem = round($descontoPedido * $proporcao, 2);
                        $descontoRestante = round($descontoRestante - $descontoItem, 2);
                    }
                }

                Venda::query()->create([
                    'empresa_id'     => $empresaId,
                    'loja_id'        => $lojaId,
                    'produto_id'     => $produto->id,
                    'quantidade'     => $linha['quantidade'],
                    'preco_unitario' => $linha['preco_unitario'],
                    'desconto'       => $descontoItem,
                    'total'          => round($linha['subtotal'] - $descontoItem, 2),
                    'data_venda'     => $dataVenda,
                ]);
            }
        });
    }
}
