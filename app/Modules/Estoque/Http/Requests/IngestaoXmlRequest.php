<?php

namespace App\Modules\Estoque\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IngestaoXmlRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'xml_nfe' => ['required', 'file', 'mimes:xml', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'xml_nfe.required' => 'Selecione um arquivo XML de NF-e.',
            'xml_nfe.mimes'    => 'O arquivo deve ser um XML válido.',
            'xml_nfe.max'      => 'O arquivo XML não pode ultrapassar 5 MB.',
        ];
    }
}
