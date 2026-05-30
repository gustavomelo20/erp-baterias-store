<?php

namespace App\Modules\Estoque\Services;

use App\Models\Produto;
use App\Modules\Estoque\DTOs\StoreProdutoDTO;

class CriarProdutoService
{
    public function criar(StoreProdutoDTO $dto): Produto
    {
        return Produto::query()->create([
            'nome'            => $dto->nome,
            'quantidade'      => $dto->quantidade,
            'preco_custo'     => $dto->precoCusto,
            'preco_unitario'  => $dto->precoUnitario,
            'empresa_id'      => $dto->empresaId,
            'loja_id'         => $dto->lojaId,
        ]);
    }
}
