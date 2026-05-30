<?php

namespace App\Modules\Venda\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutVendaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items_json' => ['required', 'json'],
            'desconto'   => ['required', 'numeric', 'min:0'],
        ];
    }
}
