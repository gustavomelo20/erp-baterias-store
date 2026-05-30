<?php

namespace App\Modules\ConfiguracaoEmpresa\DTOs;

class UpdateEmpresaDTO
{
    public function __construct(
        public readonly string  $razaoSocial,
        public readonly ?string $nomeFantasia,
        public readonly string  $cnpj,
        public readonly ?string $inscricaoEstadual,
        public readonly ?string $inscricaoMunicipal,
        public readonly string  $regimeTributario,
        public readonly ?string $cnaePrincipal,
        public readonly ?string $emailFiscal,
        public readonly ?string $telefone,
        public readonly ?string $cep,
        public readonly ?string $logradouro,
        public readonly ?string $numero,
        public readonly ?string $complemento,
        public readonly ?string $bairro,
        public readonly ?string $cidade,
        public readonly ?string $uf,
        public readonly ?string $codigoMunicipioIbge,
    ) {}

    public static function fromArray(array $dados): self
    {
        return new self(
            razaoSocial:          $dados['razao_social'],
            nomeFantasia:         $dados['nome_fantasia'] ?? null,
            cnpj:                 preg_replace('/\D+/', '', $dados['cnpj'] ?? '') ?: '',
            inscricaoEstadual:    $dados['inscricao_estadual'] ?? null,
            inscricaoMunicipal:   $dados['inscricao_municipal'] ?? null,
            regimeTributario:     $dados['regime_tributario'],
            cnaePrincipal:        $dados['cnae_principal'] ?? null,
            emailFiscal:          $dados['email_fiscal'] ?? null,
            telefone:             preg_replace('/\D+/', '', $dados['telefone'] ?? '') ?: null,
            cep:                  preg_replace('/\D+/', '', $dados['cep'] ?? '') ?: null,
            logradouro:           $dados['logradouro'] ?? null,
            numero:               $dados['numero'] ?? null,
            complemento:          $dados['complemento'] ?? null,
            bairro:               $dados['bairro'] ?? null,
            cidade:               $dados['cidade'] ?? null,
            uf:                   isset($dados['uf']) ? strtoupper((string) $dados['uf']) : null,
            codigoMunicipioIbge:  $dados['codigo_municipio_ibge'] ?? null,
        );
    }
}
