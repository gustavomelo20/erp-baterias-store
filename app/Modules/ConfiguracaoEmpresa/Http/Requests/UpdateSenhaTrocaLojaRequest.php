<?php

namespace App\Modules\ConfiguracaoEmpresa\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSenhaTrocaLojaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'senha_troca_loja' => ['nullable', 'string', 'min:6', 'confirmed'],
        ];
    }
}
