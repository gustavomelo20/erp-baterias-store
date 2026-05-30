<?php

namespace App\Modules\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Dashboard\DTOs\DashboardFiltrosDTO;
use App\Modules\Dashboard\Http\Requests\DashboardFiltrosRequest;
use App\Modules\Dashboard\UseCases\DashboardUseCase;
use App\Traits\ResolvesTenantIds;
use Illuminate\View\View;

class DashboardController extends Controller
{
    use ResolvesTenantIds;

    public function __construct(
        private readonly DashboardUseCase $useCase,
    ) {}

    public function dashboard(DashboardFiltrosRequest $request): View
    {
        [$empresaId, $lojaId] = $this->tenantIds($request);

        $dados = $this->useCase->execute(
            DashboardFiltrosDTO::fromArray($request->validated(), $empresaId, $lojaId)
        );

        return view('dashboard', $dados);
    }
}
