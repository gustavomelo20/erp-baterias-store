<?php

namespace App\Modules\Fornecedor\Services;

use App\Models\Fornecedor;
use App\Modules\Fornecedor\DTOs\StoreFornecedorDTO;
use Illuminate\Database\Eloquent\Collection;

class FornecedorService
{
    public function listar(int $empresaId): Collection
    {
        return Fornecedor::query()
            ->where('empresa_id', $empresaId)
            ->orderBy('nome')
            ->get();
    }

    public function criar(StoreFornecedorDTO $dto): Fornecedor
    {
        return Fornecedor::query()->create([
            'empresa_id' => $dto->empresaId,
            'cnpj'       => $dto->cnpj,
            'nome'       => $dto->nome,
            'ie'         => $dto->ie,
            'logradouro' => $dto->logradouro,
            'numero'     => $dto->numero,
            'bairro'     => $dto->bairro,
            'municipio'  => $dto->municipio,
            'uf'         => $dto->uf,
            'cep'        => $dto->cep,
        ]);
    }

    public function atualizar(Fornecedor $fornecedor, StoreFornecedorDTO $dto): void
    {
        $fornecedor->update([
            'cnpj'       => $dto->cnpj,
            'nome'       => $dto->nome,
            'ie'         => $dto->ie,
            'logradouro' => $dto->logradouro,
            'numero'     => $dto->numero,
            'bairro'     => $dto->bairro,
            'municipio'  => $dto->municipio,
            'uf'         => $dto->uf,
            'cep'        => $dto->cep,
        ]);
    }

    public function deletar(Fornecedor $fornecedor): void
    {
        $fornecedor->delete();
    }
}
