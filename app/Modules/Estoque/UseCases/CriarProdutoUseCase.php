<?php

namespace App\Modules\Estoque\UseCases;

use App\Modules\Estoque\DTOs\StoreProdutoDTO;
use App\Modules\Estoque\Services\CriarProdutoService;

class CriarProdutoUseCase
{
    public function __construct(
        private readonly CriarProdutoService $service,
    ) {}

    public function execute(StoreProdutoDTO $dto): void
    {
        $this->service->criar($dto);
    }
}
