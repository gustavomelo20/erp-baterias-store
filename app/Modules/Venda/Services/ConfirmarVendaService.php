<?php

namespace App\Modules\Venda\Services;

use App\Models\Produto;
use App\Modules\Venda\DTOs\ConfirmarVendaDTO;
use Illuminate\Support\Collection;

class ConfirmarVendaService
{
    private const FORMAS_PAGAMENTO = [
        'dinheiro' => 'Dinheiro',
        'credito'  => 'Cartão Crédito',
        'debito'   => 'Cartão Débito',
        'pix'      => 'PIX',
    ];

    public function __construct(
        private readonly RegistrarVendaService $registrarVenda,
    ) {}

    public function confirmar(ConfirmarVendaDTO $dto): array
    {
        $itensBrutos = collect($dto->items);

        $itens = $itensBrutos
            ->groupBy('produto_id')
            ->map(fn ($grupo, $produtoId) => [
                'produto_id' => (int) $produtoId,
                'quantidade' => (int) collect($grupo)->sum('quantidade'),
            ])
            ->values();

        $descontoPedido = round($dto->desconto, 2);

        $this->registrarVenda->persistir(
            $itens,
            $descontoPedido,
            now()->toDateString(),
            $dto->empresaId,
            $dto->lojaId,
            'itens',
        );

        return $this->montarRecibo($itensBrutos, $descontoPedido, $dto);
    }

    private function montarRecibo(Collection $itensBrutos, float $descontoPedido, ConfirmarVendaDTO $dto): array
    {
        $produtosRecibo = Produto::query()
            ->where('empresa_id', $dto->empresaId)
            ->where('loja_id', $dto->lojaId)
            ->whereIn('id', $itensBrutos->pluck('produto_id')->map(fn ($id) => (int) $id)->all())
            ->get()
            ->keyBy('id');

        $itensRecibo = $itensBrutos
            ->groupBy('produto_id')
            ->map(function ($grupo, $produtoId) use ($produtosRecibo): ?array {
                $produto = $produtosRecibo->get((int) $produtoId);
                if (!$produto) {
                    return null;
                }

                $quantidade    = (int)   collect($grupo)->sum('quantidade');
                $precoUnitario = (float) $produto->preco_unitario;

                return [
                    'nome'           => $produto->nome,
                    'quantidade'     => $quantidade,
                    'preco_unitario' => $precoUnitario,
                    'subtotal'       => round($precoUnitario * $quantidade, 2),
                ];
            })
            ->filter()
            ->values()
            ->toArray();

        $subtotalRecibo = round(collect($itensRecibo)->sum('subtotal'), 2);
        $totalRecibo    = round(max($subtotalRecibo - $descontoPedido, 0), 2);

        return [
            'itens'           => $itensRecibo,
            'subtotal'        => $subtotalRecibo,
            'desconto'        => $descontoPedido,
            'total'           => $totalRecibo,
            'forma_pagamento' => self::FORMAS_PAGAMENTO[$dto->formaPagamento] ?? $dto->formaPagamento,
            'email_cliente'   => $dto->emailCliente,
            'data_hora'       => now()->format('d/m/Y H:i:s'),
        ];
    }
}
