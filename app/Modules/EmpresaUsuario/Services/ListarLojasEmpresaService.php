<?php

namespace App\Modules\EmpresaUsuario\Services;

use App\Models\Loja;
use Illuminate\Database\Eloquent\Collection;

class ListarLojasEmpresaService
{
    public function listar(int $empresaId): Collection
    {
        return Loja::query()
            ->where('empresa_id', $empresaId)
            ->orderBy('nome')
            ->get();
    }
}
