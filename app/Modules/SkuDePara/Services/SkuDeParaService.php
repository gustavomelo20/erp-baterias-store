<?php

namespace App\Modules\SkuDePara\Services;

use App\Models\Fornecedor;
use App\Models\Produto;
use App\Models\SkuDePara;
use App\Modules\SkuDePara\DTOs\StoreSkuDeParaDTO;
use Illuminate\Database\Eloquent\Collection;

class SkuDeParaService
{
    public function listar(int $empresaId, int $lojaId): Collection
    {
        return SkuDePara::query()
            ->with(['fornecedor', 'produto'])
            ->where('empresa_id', $empresaId)
            ->where('loja_id', $lojaId)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function criar(StoreSkuDeParaDTO $dto): SkuDePara
    {
        return SkuDePara::query()->create([
            'empresa_id'    => $dto->empresaId,
            'loja_id'       => $dto->lojaId,
            'fornecedor_id' => $dto->fornecedorId,
            'sku_fornecedor'=> $dto->skuFornecedor,
            'produto_id'    => $dto->produtoId,
        ]);
    }

    public function deletar(SkuDePara $skuDePara): void
    {
        $skuDePara->delete();
    }

    public function listarFornecedores(int $empresaId): Collection
    {
        return Fornecedor::query()
            ->where('empresa_id', $empresaId)
            ->orderBy('nome')
            ->get();
    }

    public function listarProdutos(int $empresaId, int $lojaId): Collection
    {
        return Produto::query()
            ->where('empresa_id', $empresaId)
            ->where('loja_id', $lojaId)
            ->orderBy('nome')
            ->get();
    }
}
