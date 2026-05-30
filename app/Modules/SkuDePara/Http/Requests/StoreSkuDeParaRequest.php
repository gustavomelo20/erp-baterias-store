<?php

namespace App\Modules\SkuDePara\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSkuDeParaRequest extends FormRequest
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
            'fornecedor_id'  => ['required', 'integer', Rule::exists('fornecedores', 'id')->where('empresa_id', $empresaId)],
            'sku_fornecedor' => [
                'required', 'string', 'max:100',
                Rule::unique('sku_depara', 'sku_fornecedor')
                    ->where(fn ($q) => $q
                        ->where('empresa_id', $empresaId)
                        ->where('loja_id', $lojaId)
                        ->where('fornecedor_id', $this->input('fornecedor_id'))
                    )
                    ->ignore($this->route('sku_depara')),
            ],
            'produto_id' => [
                'required', 'integer',
                Rule::exists('produtos', 'id')->where(fn ($q) => $q
                    ->where('empresa_id', $empresaId)
                    ->where('loja_id', $lojaId)
                ),
            ],
        ];
    }
}
