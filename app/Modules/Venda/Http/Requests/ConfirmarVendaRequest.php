<?php

namespace App\Modules\Venda\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmarVendaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'forma_pagamento' => ['required', 'in:dinheiro,credito,debito,pix,orcamento'],
            'nome_cliente'    => ['nullable', 'string', 'max:100'],
            'email_cliente'   => ['nullable', 'email'],
        ];
    }
}
