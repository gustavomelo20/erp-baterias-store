<?php

namespace App\Modules\ConfiguracaoEmpresa\UseCases;

use App\Models\Empresa;
use App\Modules\ConfiguracaoEmpresa\Services\BuscarEmpresaService;

class BuscarEmpresaUseCase
{
    public function __construct(
        private readonly BuscarEmpresaService $service,
    ) {}

    public function execute(int $empresaId): Empresa
    {
        return $this->service->buscar($empresaId);
    }
}
