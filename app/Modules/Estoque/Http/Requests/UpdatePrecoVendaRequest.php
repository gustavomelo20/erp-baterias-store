<?php

namespace App\Modules\Estoque\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePrecoVendaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'preco_unitario' => ['required', 'numeric', 'min:0'],
        ];
    }
}
