<?php

namespace App\Modules\Venda\Services;

use App\Models\Produto;

class ShowPagamentoService
{
    private const FORMAS_PAGAMENTO = [
        'dinheiro'  => 'Dinheiro',
        'credito'   => 'Cartão Crédito',
        'debito'    => 'Cartão Débito',
        'pix'       => 'PIX',
        'orcamento' => 'Orçamento',
    ];

    public function obterDados(array $items, float $desconto, int $empresaId, int $lojaId): ?array
    {
        if (empty($items)) {
            return null;
        }

        $produtos = Produto::query()
            ->where('empresa_id', $empresaId)
            ->where('loja_id', $lojaId)
            ->whereIn('id', collect($items)->pluck('produto_id')->map(fn ($id) => (int) $id)->all())
            ->get()
            ->keyBy('id');

        $itensDetalhados = collect($items)
            ->map(function (array $item) use ($produtos): ?array {
                $produto = $produtos->get((int) ($item['produto_id'] ?? 0));
                if (!$produto) {
                    return null;
                }

                $quantidade    = max(1, (int) ($item['quantidade'] ?? 1));
                $precoUnitario = (float) $produto->preco_unitario;

                return [
                    'produto_id'     => (int) $produto->id,
                    'nome'           => $produto->nome,
                    'quantidade'     => $quantidade,
                    'preco_unitario' => $precoUnitario,
                    'subtotal'       => round($precoUnitario * $quantidade, 2),
                ];
            })
            ->filter()
            ->values();

        if ($itensDetalhados->isEmpty()) {
            return null;
        }

        $subtotal = round((float) $itensDetalhados->sum('subtotal'), 2);
        $total    = round(max($subtotal - $desconto, 0), 2);

        return [
            'items'          => $itensDetalhados,
            'subtotal'       => $subtotal,
            'total'          => $total,
            'desconto'       => $desconto,
            'formasPagamento' => self::FORMAS_PAGAMENTO,
        ];
    }
}
