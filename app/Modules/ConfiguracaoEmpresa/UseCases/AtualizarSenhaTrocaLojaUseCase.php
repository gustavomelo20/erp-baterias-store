<?php

namespace App\Modules\ConfiguracaoEmpresa\UseCases;

use App\Models\User;
use App\Modules\ConfiguracaoEmpresa\DTOs\UpdateSenhaTrocaLojaDTO;
use App\Modules\ConfiguracaoEmpresa\Services\AtualizarSenhaTrocaLojaService;

class AtualizarSenhaTrocaLojaUseCase
{
    public function __construct(
        private readonly AtualizarSenhaTrocaLojaService $service,
    ) {}

    /**
     * @return bool  true = senha definida, false = senha removida
     */
    public function execute(User $user, UpdateSenhaTrocaLojaDTO $dto): bool
    {
        return $this->service->atualizar($user, $dto);
    }
}
