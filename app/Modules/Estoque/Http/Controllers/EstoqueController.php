<?php

namespace App\Modules\Estoque\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use App\Modules\Estoque\DTOs\EntradaEstoqueDTO;
use App\Modules\Estoque\DTOs\StoreProdutoDTO;
use App\Modules\Estoque\DTOs\UpdatePrecoVendaDTO;
use App\Modules\Estoque\Http\Requests\EntradaEstoqueRequest;
use App\Modules\Estoque\Http\Requests\StoreProdutoRequest;
use App\Modules\Estoque\Http\Requests\UpdatePrecoVendaRequest;
use App\Modules\Estoque\UseCases\AtualizarPrecoVendaUseCase;
use App\Modules\Estoque\UseCases\CriarProdutoUseCase;
use App\Modules\Estoque\UseCases\EntradaEstoqueUseCase;
use App\Modules\Estoque\UseCases\ListarEstoqueUseCase;
use App\Modules\Estoque\UseCases\ListarProdutosUseCase;
use App\Traits\ResolvesTenantIds;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EstoqueController extends Controller
{
    use ResolvesTenantIds;

    public function __construct(
        private readonly ListarProdutosUseCase    $listarProdutos,
        private readonly ListarEstoqueUseCase     $listarEstoque,
        private readonly CriarProdutoUseCase      $criarProduto,
        private readonly AtualizarPrecoVendaUseCase $atualizarPrecoVenda,
        private readonly EntradaEstoqueUseCase    $entradaEstoque,
    ) {}

    public function index(Request $request): View
    {
        [$empresaId, $lojaId] = $this->tenantIds($request);

        return view('welcome', [
            'produtos' => $this->listarProdutos->execute($empresaId, $lojaId),
        ]);
    }

    public function estoque(Request $request): View
    {
        [$empresaId, $lojaId] = $this->tenantIds($request);

        return view('estoque', $this->listarEstoque->execute($empresaId, $lojaId));
    }

    public function storeProduto(StoreProdutoRequest $request): RedirectResponse
    {
        [$empresaId, $lojaId] = $this->tenantIds($request);

        $this->criarProduto->execute(
            StoreProdutoDTO::fromArray($request->validated(), $empresaId, $lojaId)
        );

        return back()->with('success', 'Produto cadastrado no estoque com sucesso.');
    }

    public function updatePrecoVenda(UpdatePrecoVendaRequest $request, Produto $produto): RedirectResponse
    {
        [$empresaId, $lojaId] = $this->tenantIds($request);

        $this->atualizarPrecoVenda->execute(
            $produto,
            UpdatePrecoVendaDTO::fromArray($request->validated(), $empresaId, $lojaId)
        );

        return back()->with('success', 'Preço de venda atualizado com sucesso.');
    }

    public function storeEntradaEstoque(EntradaEstoqueRequest $request): RedirectResponse
    {
        [$empresaId, $lojaId] = $this->tenantIds($request);

        $this->entradaEstoque->execute(
            EntradaEstoqueDTO::fromArray($request->validated(), $empresaId, $lojaId)
        );

        return back()->with('success', 'Estoque atualizado com preço médio ponderado.');
    }
}
