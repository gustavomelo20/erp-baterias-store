<?php

namespace App\Modules\Estoque\DTOs;

class EntradaEstoqueDTO
{
    public function __construct(
        public readonly int   $produtoId,
        public readonly int   $quantidade,
        public readonly float $precoCusto,
        public readonly int   $empresaId,
        public readonly int   $lojaId,
    ) {}

    public static function fromArray(array $dados, int $empresaId, int $lojaId): self
    {
        return new self(
            produtoId:  (int)   $dados['produto_id'],
            quantidade: (int)   $dados['quantidade'],
            precoCusto: (float) $dados['preco_custo'],
            empresaId:  $empresaId,
            lojaId:     $lojaId,
        );
    }
}
