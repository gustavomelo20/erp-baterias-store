<?php

namespace App\Modules\Estoque\Services;

use App\Models\Produto;
use App\Modules\Estoque\DTOs\UpdatePrecoVendaDTO;

class AtualizarPrecoVendaService
{
    public function atualizar(Produto $produto, UpdatePrecoVendaDTO $dto): void
    {
        if ((int) $produto->empresa_id !== $dto->empresaId || (int) $produto->loja_id !== $dto->lojaId) {
            abort(404);
        }

        $produto->update(['preco_unitario' => $dto->precoUnitario]);
    }
}
