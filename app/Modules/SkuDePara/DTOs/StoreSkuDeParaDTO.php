<?php

namespace App\Modules\SkuDePara\DTOs;

class StoreSkuDeParaDTO
{
    public function __construct(
        public readonly int    $empresaId,
        public readonly int    $lojaId,
        public readonly int    $fornecedorId,
        public readonly string $skuFornecedor,
        public readonly int    $produtoId,
    ) {}

    public static function fromArray(array $dados, int $empresaId, int $lojaId): self
    {
        return new self(
            empresaId:     $empresaId,
            lojaId:        $lojaId,
            fornecedorId:  (int) $dados['fornecedor_id'],
            skuFornecedor: trim($dados['sku_fornecedor']),
            produtoId:     (int) $dados['produto_id'],
        );
    }
}
