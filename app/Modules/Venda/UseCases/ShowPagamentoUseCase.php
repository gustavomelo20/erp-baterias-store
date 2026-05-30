<?php

namespace App\Modules\Venda\UseCases;

use App\Modules\Venda\Services\ShowPagamentoService;

class ShowPagamentoUseCase
{
    public function __construct(
        private readonly ShowPagamentoService $service,
    ) {}

    public function execute(array $items, float $desconto, int $empresaId, int $lojaId): ?array
    {
        return $this->service->obterDados($items, $desconto, $empresaId, $lojaId);
    }
}
