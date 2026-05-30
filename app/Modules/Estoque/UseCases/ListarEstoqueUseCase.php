<?php

namespace App\Modules\Estoque\UseCases;

use App\Modules\Estoque\Services\ListarEstoqueService;

class ListarEstoqueUseCase
{
    public function __construct(
        private readonly ListarEstoqueService $service,
    ) {}

    public function execute(int $empresaId, int $lojaId): array
    {
        return $this->service->listar($empresaId, $lojaId);
    }
}
