<?php

namespace App\Modules\ConfiguracaoEmpresa\Services;

use App\Models\Empresa;
use App\Modules\ConfiguracaoEmpresa\DTOs\UpdateEmpresaDTO;

class AtualizarEmpresaService
{
    public function atualizar(Empresa $empresa, UpdateEmpresaDTO $dto): void
    {
        $empresa->update([
            'nome'                  => $dto->nomeFantasia ?: $dto->razaoSocial,
            'razao_social'          => $dto->razaoSocial,
            'nome_fantasia'         => $dto->nomeFantasia,
            'cnpj'                  => $dto->cnpj,
            'inscricao_estadual'    => $dto->inscricaoEstadual,
            'inscricao_municipal'   => $dto->inscricaoMunicipal,
            'regime_tributario'     => $dto->regimeTributario,
            'cnae_principal'        => $dto->cnaePrincipal,
            'email_fiscal'          => $dto->emailFiscal,
            'telefone'              => $dto->telefone,
            'cep'                   => $dto->cep,
            'logradouro'            => $dto->logradouro,
            'numero'                => $dto->numero,
            'complemento'           => $dto->complemento,
            'bairro'                => $dto->bairro,
            'cidade'                => $dto->cidade,
            'uf'                    => $dto->uf,
            'codigo_municipio_ibge' => $dto->codigoMunicipioIbge,
        ]);
    }
}
