<?php

namespace App\Modules\Auth\DTOs;

class LoginDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {}

    public static function fromArray(array $dados): self
    {
        return new self(
            email:    $dados['email'],
            password: $dados['password'],
        );
    }
}
