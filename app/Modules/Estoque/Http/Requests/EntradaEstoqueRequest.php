<?php

namespace App\Modules\Estoque\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EntradaEstoqueRequest extends FormRequest
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
            'produto_id' => [
                'required',
                Rule::exists('produtos', 'id')->where(fn ($q) => $q
                    ->where('empresa_id', $empresaId)
                    ->where('loja_id', $lojaId)
                ),
            ],
            'quantidade'  => ['required', 'integer', 'min:1'],
            'preco_custo' => ['required', 'numeric', 'min:0'],
        ];
    }
}
