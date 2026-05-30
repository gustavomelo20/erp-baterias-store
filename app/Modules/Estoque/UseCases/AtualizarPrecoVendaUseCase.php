<?php

namespace App\Modules\Estoque\UseCases;

use App\Models\Produto;
use App\Modules\Estoque\DTOs\UpdatePrecoVendaDTO;
use App\Modules\Estoque\Services\AtualizarPrecoVendaService;

class AtualizarPrecoVendaUseCase
{
    public function __construct(
        private readonly AtualizarPrecoVendaService $service,
    ) {}

    public function execute(Produto $produto, UpdatePrecoVendaDTO $dto): void
    {
        $this->service->atualizar($produto, $dto);
    }
}
