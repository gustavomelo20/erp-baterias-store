<?php

namespace App\Modules\EmpresaCadastro\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmpresaCadastroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'empresa_nome' => ['required', 'string', 'max:255'],
            'loja_nome'    => ['required', 'string', 'max:255'],
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'     => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
}
