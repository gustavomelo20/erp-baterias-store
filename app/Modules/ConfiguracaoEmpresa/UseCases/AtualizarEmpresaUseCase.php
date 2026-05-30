<?php

namespace App\Modules\ConfiguracaoEmpresa\UseCases;

use App\Models\Empresa;
use App\Modules\ConfiguracaoEmpresa\DTOs\UpdateEmpresaDTO;
use App\Modules\ConfiguracaoEmpresa\Services\AtualizarEmpresaService;
use App\Modules\ConfiguracaoEmpresa\Services\BuscarEmpresaService;

class AtualizarEmpresaUseCase
{
    public function __construct(
        private readonly BuscarEmpresaService   $buscar,
        private readonly AtualizarEmpresaService $atualizar,
    ) {}

    public function execute(int $empresaId, UpdateEmpresaDTO $dto): Empresa
    {
        $empresa = $this->buscar->buscar($empresaId);

        $this->atualizar->atualizar($empresa, $dto);

        return $empresa;
    }
}
