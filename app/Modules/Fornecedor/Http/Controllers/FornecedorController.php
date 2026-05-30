<?php

namespace App\Modules\Fornecedor\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Fornecedor;
use App\Modules\Fornecedor\DTOs\StoreFornecedorDTO;
use App\Modules\Fornecedor\Http\Requests\StoreFornecedorRequest;
use App\Modules\Fornecedor\Services\FornecedorService;
use App\Traits\ResolvesTenantIds;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FornecedorController extends Controller
{
    use ResolvesTenantIds;

    public function __construct(
        private readonly FornecedorService $service,
    ) {}

    public function index(Request $request): View
    {
        [$empresaId] = $this->tenantIds($request);

        return view('fornecedores.index', [
            'fornecedores' => $this->service->listar($empresaId),
        ]);
    }

    public function store(StoreFornecedorRequest $request): RedirectResponse
    {
        [$empresaId] = $this->tenantIds($request);

        $this->service->criar(StoreFornecedorDTO::fromArray($request->validated(), $empresaId));

        return back()->with('success', 'Fornecedor cadastrado com sucesso.');
    }

    public function update(StoreFornecedorRequest $request, Fornecedor $fornecedor): RedirectResponse
    {
        [$empresaId] = $this->tenantIds($request);

        abort_if($fornecedor->empresa_id !== $empresaId, 403);

        $this->service->atualizar($fornecedor, StoreFornecedorDTO::fromArray($request->validated(), $empresaId));

        return back()->with('success', 'Fornecedor atualizado com sucesso.');
    }

    public function destroy(Request $request, Fornecedor $fornecedor): RedirectResponse
    {
        [$empresaId] = $this->tenantIds($request);

        abort_if($fornecedor->empresa_id !== $empresaId, 403);

        $this->service->deletar($fornecedor);

        return back()->with('success', 'Fornecedor removido com sucesso.');
    }
}
