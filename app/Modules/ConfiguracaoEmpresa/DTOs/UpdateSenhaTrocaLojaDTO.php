<?php

namespace App\Modules\ConfiguracaoEmpresa\DTOs;

class UpdateSenhaTrocaLojaDTO
{
    public function __construct(
        public readonly ?string $senha,
    ) {}

    public static function fromArray(array $dados): self
    {
        return new self(
            senha: $dados['senha_troca_loja'] ?? null,
        );
    }
}
