<?php

namespace App\Modules\Auth\UseCases;

use App\Models\Loja;
use App\Models\User;
use App\Modules\Auth\DTOs\SwitchLojaDTO;
use App\Modules\Auth\Services\SwitchLojaService;

class SwitchLojaUseCase
{
    public function __construct(
        private readonly SwitchLojaService $service,
    ) {}

    /**
     * @return array{success: true, loja: Loja}
     *       | array{success: false, field: string, error: string}
     */
    public function execute(User $user, SwitchLojaDTO $dto): array
    {
        return $this->service->trocar($user, $dto);
    }
}
