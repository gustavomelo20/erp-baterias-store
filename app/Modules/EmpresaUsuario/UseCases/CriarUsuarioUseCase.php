<?php

namespace App\Modules\EmpresaUsuario\UseCases;

use App\Models\User;
use App\Modules\EmpresaUsuario\DTOs\CriarUsuarioDTO;
use App\Modules\EmpresaUsuario\Services\CriarUsuarioService;

class CriarUsuarioUseCase
{
    public function __construct(
        private readonly CriarUsuarioService $service,
    ) {}

    public function execute(CriarUsuarioDTO $dto): User
    {
        return $this->service->criar($dto);
    }
}
