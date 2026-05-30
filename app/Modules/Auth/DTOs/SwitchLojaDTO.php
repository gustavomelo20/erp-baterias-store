<?php

namespace App\Modules\Auth\DTOs;

class SwitchLojaDTO
{
    public function __construct(
        public readonly int     $lojaId,
        public readonly ?string $senhaLoja,
    ) {}

    public static function fromArray(array $dados): self
    {
        return new self(
            lojaId:    (int) $dados['loja_id'],
            senhaLoja: $dados['senha_loja'] ?? null,
        );
    }
}
