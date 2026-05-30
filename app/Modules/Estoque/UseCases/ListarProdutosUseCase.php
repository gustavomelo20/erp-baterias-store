<?php

namespace App\Modules\Estoque\UseCases;

use App\Modules\Estoque\Services\ListarProdutosService;
use Illuminate\Database\Eloquent\Collection;

class ListarProdutosUseCase
{
    public function __construct(
        private readonly ListarProdutosService $service,
    ) {}

    public function execute(int $empresaId, int $lojaId): Collection
    {
        return $this->service->listar($empresaId, $lojaId);
    }
}
