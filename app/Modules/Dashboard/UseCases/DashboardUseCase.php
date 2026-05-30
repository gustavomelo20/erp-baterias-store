<?php

namespace App\Modules\Dashboard\UseCases;

use App\Modules\Dashboard\DTOs\DashboardFiltrosDTO;
use App\Modules\Dashboard\Services\DashboardService;

class DashboardUseCase
{
    public function __construct(
        private readonly DashboardService $service,
    ) {}

    public function execute(DashboardFiltrosDTO $dto): array
    {
        return $this->service->obterDados($dto);
    }
}
