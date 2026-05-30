<?php

namespace App\Modules\Venda\UseCases;

use App\Modules\Venda\DTOs\StoreVendaDTO;
use App\Modules\Venda\Services\RegistrarVendaService;

class RegistrarVendaUseCase
{
    public function __construct(
        private readonly RegistrarVendaService $service,
    ) {}

    public function execute(StoreVendaDTO $dto): void
    {
        $this->service->persistir(
            $dto->itens,
            $dto->desconto,
            now()->toDateString(),
            $dto->empresaId,
            $dto->lojaId,
            $dto->erroEstoqueCampo,
        );
    }
}
