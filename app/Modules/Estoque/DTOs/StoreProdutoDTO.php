<?php

namespace App\Modules\Estoque\DTOs;

class StoreProdutoDTO
{
    public function __construct(
        public readonly string $nome,
        public readonly int    $quantidade,
        public readonly float  $precoCusto,
        public readonly float  $precoUnitario,
        public readonly int    $empresaId,
        public readonly int    $lojaId,
    ) {}

    public static function fromArray(array $dados, int $empresaId, int $lojaId): self
    {
        return new self(
            nome:           $dados['nome'],
            quantidade:     (int)   $dados['quantidade'],
            precoCusto:     (float) $dados['preco_custo'],
            precoUnitario:  (float) $dados['preco_unitario'],
            empresaId:      $empresaId,
            lojaId:         $lojaId,
        );
    }
}
