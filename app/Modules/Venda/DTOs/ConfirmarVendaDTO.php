<?php

namespace App\Modules\Venda\DTOs;

class ConfirmarVendaDTO
{
    public function __construct(
        public readonly string  $formaPagamento,
        public readonly ?string $emailCliente,
        public readonly array   $items,
        public readonly float   $desconto,
        public readonly int     $empresaId,
        public readonly int     $lojaId,
    ) {}
}
