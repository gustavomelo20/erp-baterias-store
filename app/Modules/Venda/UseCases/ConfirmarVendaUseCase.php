<?php

namespace App\Modules\Venda\UseCases;

use App\Modules\Venda\DTOs\ConfirmarVendaDTO;
use App\Modules\Venda\Services\ConfirmarVendaService;

class ConfirmarVendaUseCase
{
    public function __construct(
        private readonly ConfirmarVendaService $service,
    ) {}

    public function execute(ConfirmarVendaDTO $dto): array
    {
        return $this->service->confirmar($dto);
    }
}
