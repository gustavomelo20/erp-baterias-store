<?php

namespace App\Modules\Fornecedor\DTOs;

class StoreFornecedorDTO
{
    public function __construct(
        public readonly int     $empresaId,
        public readonly string  $cnpj,
        public readonly string  $nome,
        public readonly ?string $ie,
        public readonly ?string $logradouro,
        public readonly ?string $numero,
        public readonly ?string $bairro,
        public readonly ?string $municipio,
        public readonly ?string $uf,
        public readonly ?string $cep,
    ) {}

    public static function fromArray(array $dados, int $empresaId): self
    {
        return new self(
            empresaId:  $empresaId,
            cnpj:       preg_replace('/\D/', '', $dados['cnpj']),
            nome:       $dados['nome'],
            ie:         $dados['ie'] ?? null,
            logradouro: $dados['logradouro'] ?? null,
            numero:     $dados['numero'] ?? null,
            bairro:     $dados['bairro'] ?? null,
            municipio:  $dados['municipio'] ?? null,
            uf:         $dados['uf'] ?? null,
            cep:        isset($dados['cep']) ? preg_replace('/\D/', '', $dados['cep']) : null,
        );
    }
}
