<?php

namespace App\Modules\Estoque\Services;

use App\Models\Produto;
use Illuminate\Database\Eloquent\Collection;

class ListarProdutosService
{
    public function listar(int $empresaId, int $lojaId): Collection
    {
        return Produto::query()
            ->where('empresa_id', $empresaId)
            ->where('loja_id', $lojaId)
            ->orderBy('nome')
            ->get();
    }
}
