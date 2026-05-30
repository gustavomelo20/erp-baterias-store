<?php

namespace App\Modules\Estoque\DTOs;

class UpdatePrecoVendaDTO
{
    public function __construct(
        public readonly float $precoUnitario,
        public readonly int   $empresaId,
        public readonly int   $lojaId,
    ) {}

    public static function fromArray(array $dados, int $empresaId, int $lojaId): self
    {
        return new self(
            precoUnitario: (float) $dados['preco_unitario'],
            empresaId:     $empresaId,
            lojaId:        $lojaId,
        );
    }
}
