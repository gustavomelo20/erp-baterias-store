<?php

namespace App\Modules\Estoque\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProdutoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $empresaId = (int) $this->user()->empresa_id;
        $lojaId    = (int) $this->session()->get('loja_id');

        return [
            'nome' => [
                'required',
                'string',
                'max:255',
                Rule::unique('produtos', 'nome')->where(fn ($q) => $q
                    ->where('empresa_id', $empresaId)
                    ->where('loja_id', $lojaId)
                ),
            ],
            'quantidade'      => ['required', 'integer', 'min:0'],
            'preco_custo'     => ['required', 'numeric', 'min:0'],
            'preco_unitario'  => ['required', 'numeric', 'min:0'],
        ];
    }
}
