<?php

namespace App\Modules\Dashboard\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DashboardFiltrosRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'data_inicio' => ['nullable', 'date'],
            'data_fim'    => ['nullable', 'date', 'after_or_equal:data_inicio'],
        ];
    }
}
