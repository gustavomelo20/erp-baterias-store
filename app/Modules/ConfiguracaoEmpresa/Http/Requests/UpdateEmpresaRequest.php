<?php

namespace App\Modules\ConfiguracaoEmpresa\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmpresaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $empresaId = (int) $this->user()->empresa_id;

        return [
            'razao_social'          => ['required', 'string', 'max:255'],
            'nome_fantasia'         => ['nullable', 'string', 'max:255'],
            'cnpj'                  => [
                'required',
                'string',
                'max:18',
                Rule::unique('empresas', 'cnpj')->ignore($empresaId),
            ],
            'inscricao_estadual'    => ['nullable', 'string', 'max:30'],
            'inscricao_municipal'   => ['nullable', 'string', 'max:30'],
            'regime_tributario'     => ['required', 'in:simples,presumido,real'],
            'cnae_principal'        => ['nullable', 'string', 'max:20'],
            'email_fiscal'          => ['nullable', 'email', 'max:255'],
            'telefone'              => ['nullable', 'string', 'max:20'],
            'cep'                   => ['nullable', 'string', 'max:10'],
            'logradouro'            => ['nullable', 'string', 'max:255'],
            'numero'                => ['nullable', 'string', 'max:20'],
            'complemento'           => ['nullable', 'string', 'max:120'],
            'bairro'                => ['nullable', 'string', 'max:120'],
            'cidade'                => ['nullable', 'string', 'max:120'],
            'uf'                    => ['nullable', 'string', 'size:2'],
            'codigo_municipio_ibge' => ['nullable', 'string', 'max:10'],
        ];
    }
}
