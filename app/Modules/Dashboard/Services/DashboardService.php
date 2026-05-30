<?php

namespace App\Modules\Dashboard\Services;

use App\Models\Produto;
use App\Models\Venda;
use App\Modules\Dashboard\DTOs\DashboardFiltrosDTO;

class DashboardService
{
    public function obterDados(DashboardFiltrosDTO $dto): array
    {
        $vendasPeriodo = Venda::query()
            ->with('produto')
            ->where('empresa_id', $dto->empresaId)
            ->where('loja_id', $dto->lojaId)
            ->whereDate('data_venda', '>=', $dto->dataInicio)
            ->whereDate('data_venda', '<=', $dto->dataFim)
            ->latest()
            ->get();

        $resumoPeriodo = [
            'pedidos'      => $vendasPeriodo->count(),
            'faturamento'  => (float) $vendasPeriodo->sum('total'),
            'itens'        => (int)   $vendasPeriodo->sum('quantidade'),
            'lucro'        => (float) $vendasPeriodo->sum(function (Venda $venda): float {
                $custoUnitario = (float) ($venda->produto->preco_custo ?? 0);
                $custoTotal    = $custoUnitario * (int) $venda->quantidade;

                return round((float) $venda->total - $custoTotal, 2);
            }),
        ];

        $produtosEstoque = Produto::query()
            ->where('empresa_id', $dto->empresaId)
            ->where('loja_id', $dto->lojaId)
            ->orderBy('nome')
            ->get();

        return [
            'resumoPeriodo'  => $resumoPeriodo,
            'vendasPeriodo'  => $vendasPeriodo,
            'ultimasVendas'  => $vendasPeriodo->take(10),
            'produtosEstoque' => $produtosEstoque,
            'filtros'        => [
                'data_inicio' => $dto->dataInicio,
                'data_fim'    => $dto->dataFim,
            ],
        ];
    }
}
