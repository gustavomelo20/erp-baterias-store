<?php

namespace App\Modules\Venda\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVendaRequest extends FormRequest
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
            'items'               => ['sometimes', 'array', 'min:1'],
            'items.*.produto_id'  => [
                'required_with:items',
                Rule::exists('produtos', 'id')->where(fn ($q) => $q
                    ->where('empresa_id', $empresaId)
                    ->where('loja_id', $lojaId)
                ),
            ],
            'items.*.quantidade'  => ['required_with:items', 'integer', 'min:1'],
            'produto_id'          => [
                'required_without:items',
                Rule::exists('produtos', 'id')->where(fn ($q) => $q
                    ->where('empresa_id', $empresaId)
                    ->where('loja_id', $lojaId)
                ),
            ],
            'quantidade'          => ['required_without:items', 'integer', 'min:1'],
            'desconto'            => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
