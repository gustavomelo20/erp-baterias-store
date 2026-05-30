<?php

namespace App\Modules\Auth\UseCases;

use App\Models\Loja;
use App\Modules\Auth\DTOs\LoginDTO;
use App\Modules\Auth\Services\LoginService;

class LoginUseCase
{
    public function __construct(
        private readonly LoginService $service,
    ) {}

    /**
     * @return array{success: true, loja: Loja}
     *       | array{success: false, error: string}
     */
    public function execute(LoginDTO $dto): array
    {
        return $this->service->tentarLogin($dto);
    }
}
