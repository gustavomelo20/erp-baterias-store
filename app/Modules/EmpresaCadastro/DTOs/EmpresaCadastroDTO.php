<?php

namespace App\Modules\EmpresaCadastro\DTOs;

class EmpresaCadastroDTO
{
    public function __construct(
        public readonly string $empresaNome,
        public readonly string $lojaNome,
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
    ) {}

    public static function fromArray(array $dados): self
    {
        return new self(
            empresaNome: $dados['empresa_nome'],
            lojaNome: $dados['loja_nome'],
            name: $dados['name'],
            email: $dados['email'],
            password: $dados['password'],
        );
    }
}
