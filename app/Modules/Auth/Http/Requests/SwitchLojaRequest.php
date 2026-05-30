<?php

namespace App\Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SwitchLojaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'loja_id'    => ['required', 'integer'],
            'senha_loja' => ['nullable', 'string'],
        ];
    }
}
