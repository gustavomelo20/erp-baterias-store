<?php

namespace App\Modules\Fornecedor\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFornecedorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $empresaId = (int) $this->user()->empresa_id;

        return [
            'cnpj'      => [
                'required', 'string',
                Rule::unique('fornecedores', 'cnpj')
                    ->where(fn ($q) => $q->where('empresa_id', $empresaId))
                    ->ignore($this->route('fornecedor')),
            ],
            'nome'      => ['required', 'string', 'max:255'],
            'ie'        => ['nullable', 'string', 'max:30'],
            'logradouro'=> ['nullable', 'string', 'max:255'],
            'numero'    => ['nullable', 'string', 'max:20'],
            'bairro'    => ['nullable', 'string', 'max:100'],
            'municipio' => ['nullable', 'string', 'max:100'],
            'uf'        => ['nullable', 'string', 'size:2'],
            'cep'       => ['nullable', 'string'],
        ];
    }
}
