<?php

namespace App\Modules\ConfiguracaoEmpresa\Services;

use App\Models\Empresa;

class BuscarEmpresaService
{
    public function buscar(int $empresaId): Empresa
    {
        return Empresa::query()->findOrFail($empresaId);
    }
}
