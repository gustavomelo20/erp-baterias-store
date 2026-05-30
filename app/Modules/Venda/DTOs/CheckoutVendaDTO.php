<?php

namespace App\Modules\Venda\DTOs;

class CheckoutVendaDTO
{
    public function __construct(
        public readonly array $items,
        public readonly float $desconto,
    ) {}

    public static function fromArray(array $dados): self
    {
        return new self(
            items:    json_decode($dados['items_json'], true),
            desconto: (float) $dados['desconto'],
        );
    }
}
