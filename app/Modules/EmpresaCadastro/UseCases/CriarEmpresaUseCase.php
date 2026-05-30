<?php

namespace App\Modules\EmpresaCadastro\UseCases;

use App\Models\Loja;
use App\Models\User;
use App\Modules\EmpresaCadastro\DTOs\EmpresaCadastroDTO;
use App\Modules\EmpresaCadastro\Services\EmpresaCadastroService;
use Illuminate\Support\Facades\Auth;

class CriarEmpresaUseCase
{
    public function __construct(
        private readonly EmpresaCadastroService $service,
    ) {}

    /**
     * Executa o cadastro de empresa e autentica o usuário criado.
     *
     * @return array{0: User, 1: Loja}
     */
    public function execute(EmpresaCadastroDTO $dto): array
    {
        [$user, $loja] = $this->service->criar($dto);

        Auth::login($user);

        return [$user, $loja];
    }
}
