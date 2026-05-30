<?php

namespace App\Modules\EmpresaUsuario\DTOs;

class CriarUsuarioDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly array  $lojas,
        public readonly int    $empresaId,
    ) {}

    public static function fromArray(array $dados, int $empresaId): self
    {
        return new self(
            name:      $dados['name'],
            email:     $dados['email'],
            password:  $dados['password'],
            lojas:     $dados['lojas'],
            empresaId: $empresaId,
        );
    }
}
