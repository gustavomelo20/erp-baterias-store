<?php

namespace App\Modules\EmpresaUsuario\UseCases;

use App\Modules\EmpresaUsuario\Services\ListarLojasEmpresaService;
use Illuminate\Database\Eloquent\Collection;

class ListarLojasUseCase
{
    public function __construct(
        private readonly ListarLojasEmpresaService $service,
    ) {}

    public function execute(int $empresaId): Collection
    {
        return $this->service->listar($empresaId);
    }
}
