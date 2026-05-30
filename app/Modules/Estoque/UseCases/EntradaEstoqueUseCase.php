<?php

namespace App\Modules\Estoque\UseCases;

use App\Modules\Estoque\DTOs\EntradaEstoqueDTO;
use App\Modules\Estoque\Services\EntradaEstoqueService;

class EntradaEstoqueUseCase
{
    public function __construct(
        private readonly EntradaEstoqueService $service,
    ) {}

    public function execute(EntradaEstoqueDTO $dto): void
    {
        $this->service->registrar($dto);
    }
}
