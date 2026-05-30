<?php

namespace App\Modules\Estoque\Services;

use App\Models\Produto;
use App\Models\Venda;
use Illuminate\Database\Eloquent\Collection;

class ListarEstoqueService
{
    public function listar(int $empresaId, int $lojaId): array
    {
        $produtos = Produto::query()
            ->where('empresa_id', $empresaId)
            ->where('loja_id', $lojaId)
            ->orderBy('nome')
            ->get();

        $vendasRecentes = Venda::query()
            ->with('produto')
            ->where('empresa_id', $empresaId)
            ->where('loja_id', $lojaId)
            ->latest()
            ->limit(10)
            ->get();

        return [
            'produtos'       => $produtos,
            'vendasRecentes' => $vendasRecentes,
        ];
    }
}
