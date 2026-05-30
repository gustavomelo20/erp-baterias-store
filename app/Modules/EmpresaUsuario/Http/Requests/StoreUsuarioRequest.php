<?php

namespace App\Modules\EmpresaUsuario\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $empresaId = (int) $this->user()->empresa_id;

        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'lojas'    => ['required', 'array', 'min:1'],
            'lojas.*'  => [
                'integer',
                Rule::exists('lojas', 'id')->where(fn ($q) => $q->where('empresa_id', $empresaId)),
            ],
        ];
    }
}
