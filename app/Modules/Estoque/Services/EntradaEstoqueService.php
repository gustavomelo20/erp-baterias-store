<?php

namespace App\Modules\Estoque\Services;

use App\Models\Produto;
use App\Modules\Estoque\DTOs\EntradaEstoqueDTO;
use Illuminate\Support\Facades\DB;

class EntradaEstoqueService
{
    public function registrar(EntradaEstoqueDTO $dto): void
    {
        DB::transaction(function () use ($dto): void {
            $produto = Produto::query()
                ->where('empresa_id', $dto->empresaId)
                ->where('loja_id', $dto->lojaId)
                ->lockForUpdate()
                ->findOrFail($dto->produtoId);

            $qtdNova  = $dto->quantidade;
            $custNovo = $dto->precoCusto;
            $qtdAtual = (int)   $produto->quantidade;
            $custAtual = (float) $produto->preco_custo;

            $totalQtd  = $qtdAtual + $qtdNova;

            // Preço médio ponderado: (qtdAtual * custAtual + qtdNova * custNovo) / totalQtd
            $precoMedio = $totalQtd > 0
                ? round(($qtdAtual * $custAtual + $qtdNova * $custNovo) / $totalQtd, 2)
                : $custNovo;

            $produto->update([
                'quantidade'  => $totalQtd,
                'preco_custo' => $precoMedio,
            ]);
        });
    }
}
