<?php

namespace App\Modules\SkuDePara\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SkuDePara;
use App\Modules\SkuDePara\DTOs\StoreSkuDeParaDTO;
use App\Modules\SkuDePara\Http\Requests\StoreSkuDeParaRequest;
use App\Modules\SkuDePara\Services\SkuDeParaService;
use App\Traits\ResolvesTenantIds;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SkuDeParaController extends Controller
{
    use ResolvesTenantIds;

    public function __construct(
        private readonly SkuDeParaService $service,
    ) {}

    public function index(Request $request): View
    {
        [$empresaId, $lojaId] = $this->tenantIds($request);

        return view('sku-depara.index', [
            'depara'       => $this->service->listar($empresaId, $lojaId),
            'fornecedores' => $this->service->listarFornecedores($empresaId),
            'produtos'     => $this->service->listarProdutos($empresaId, $lojaId),
        ]);
    }

    public function store(StoreSkuDeParaRequest $request): RedirectResponse
    {
        [$empresaId, $lojaId] = $this->tenantIds($request);

        $this->service->criar(StoreSkuDeParaDTO::fromArray($request->validated(), $empresaId, $lojaId));

        return back()->with('success', 'Mapeamento SKU cadastrado com sucesso.');
    }

    public function destroy(Request $request, SkuDePara $skuDePara): RedirectResponse
    {
        [$empresaId, $lojaId] = $this->tenantIds($request);

        abort_if($skuDePara->empresa_id !== $empresaId || $skuDePara->loja_id !== $lojaId, 403);

        $this->service->deletar($skuDePara);

        return back()->with('success', 'Mapeamento removido com sucesso.');
    }
}
