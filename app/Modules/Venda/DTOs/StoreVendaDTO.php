<?php

namespace App\Modules\Venda\DTOs;

use Illuminate\Support\Collection;

class StoreVendaDTO
{
    public function __construct(
        public readonly Collection $itens,
        public readonly float      $desconto,
        public readonly int        $empresaId,
        public readonly int        $lojaId,
        public readonly string     $erroEstoqueCampo,
    ) {}

    public static function fromArray(array $dados, int $empresaId, int $lojaId): self
    {
        $usouItems   = isset($dados['items']);
        $itensBrutos = collect($dados['items'] ?? [[
            'produto_id' => $dados['produto_id'],
            'quantidade' => $dados['quantidade'],
        ]]);

        $itens = $itensBrutos
            ->groupBy('produto_id')
            ->map(fn ($grupo, $produtoId) => [
                'produto_id' => (int) $produtoId,
                'quantidade' => (int) collect($grupo)->sum('quantidade'),
            ])
            ->values();

        return new self(
            itens:             $itens,
            desconto:          round((float) ($dados['desconto'] ?? 0), 2),
            empresaId:         $empresaId,
            lojaId:            $lojaId,
            erroEstoqueCampo:  $usouItems ? 'items' : 'quantidade',
        );
    }
}
