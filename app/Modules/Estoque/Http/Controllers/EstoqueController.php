<?php

namespace App\Modules\Estoque\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use App\Models\SkuDePara;
use App\Modules\Estoque\DTOs\EntradaEstoqueDTO;
use App\Modules\Estoque\DTOs\StoreProdutoDTO;
use App\Modules\Estoque\DTOs\UpdatePrecoVendaDTO;
use App\Modules\Estoque\Http\Requests\EntradaEstoqueRequest;
use App\Modules\Estoque\Http\Requests\StoreProdutoRequest;
use App\Modules\Estoque\Http\Requests\UpdatePrecoVendaRequest;
use App\Modules\Estoque\Http\Requests\IngestaoXmlRequest;
use App\Modules\Estoque\UseCases\AtualizarPrecoVendaUseCase;
use App\Modules\Estoque\UseCases\CriarProdutoUseCase;
use App\Modules\Estoque\UseCases\EntradaEstoqueUseCase;
use App\Modules\Estoque\UseCases\IngestaoXmlUseCase;
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
        private readonly IngestaoXmlUseCase       $ingestaoXml,
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

    public function ingestaoXml(IngestaoXmlRequest $request): RedirectResponse
    {
        [$empresaId, $lojaId] = $this->tenantIds($request);

        try {
            $pendente = $this->ingestaoXml->parsear(
                $request->file('xml_nfe'),
                $empresaId,
                $lojaId
            );
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['xml_nfe' => $e->getMessage()]);
        }

        if (count($pendente['itens']) === 0) {
            return back()->withErrors(['xml_nfe' => 'Nenhum item encontrado no XML.']);
        }

        $temNaoMapeados = collect($pendente['itens'])->contains(fn ($i) => $i['produto_id'] === null);

        if ($temNaoMapeados) {
            $request->session()->put('xml_pendente', $pendente);
            return redirect()->route('estoque.resolver-sku');
        }

        $resultado = $this->ingestaoXml->processarPendente($pendente, $empresaId, $lojaId);
        $total     = count($resultado['importados']);

        return redirect()->route('estoque.index')
            ->with('xml_resultado', $resultado)
            ->with('success', "{$total} item(ns) importado(s) com sucesso.");
    }

    public function resolverSku(Request $request): View|RedirectResponse
    {
        [$empresaId, $lojaId] = $this->tenantIds($request);

        $pendente = $request->session()->get('xml_pendente');

        if (!$pendente) {
            return redirect()->route('estoque.index');
        }

        $naoMapeados = array_values(
            array_filter($pendente['itens'], fn ($i) => $i['produto_id'] === null)
        );

        $produtos = Produto::query()
            ->where('empresa_id', $empresaId)
            ->where('loja_id', $lojaId)
            ->orderBy('nome')
            ->get();

        return view('estoque.resolver-sku', compact('pendente', 'naoMapeados', 'produtos'));
    }

    public function confirmarResolucao(Request $request): RedirectResponse
    {
        [$empresaId, $lojaId] = $this->tenantIds($request);

        $pendente = $request->session()->get('xml_pendente');

        if (!$pendente) {
            return redirect()->route('estoque.index');
        }

        $resolucoes = $request->input('resolucao', []);

        // Monta mapa sku_fornecedor → dados da resolução
        $resolucaoMap = [];
        foreach ($resolucoes as $res) {
            if (!empty($res['sku_fornecedor'])) {
                $resolucaoMap[$res['sku_fornecedor']] = $res;
            }
        }

        foreach ($pendente['itens'] as &$item) {
            if ($item['produto_id'] !== null) {
                continue;
            }

            $res  = $resolucaoMap[$item['sku_fornecedor']] ?? null;
            $tipo = $res['tipo'] ?? 'ignorar';

            if (!$res || $tipo === 'ignorar') {
                continue;
            }

            $produtoId = null;

            if ($tipo === 'existente' && !empty($res['produto_id'])) {
                $produtoId = (int) $res['produto_id'];
            } elseif ($tipo === 'novo' && !empty($res['nome'])) {
                $novo = Produto::query()->create([
                    'empresa_id'     => $empresaId,
                    'loja_id'        => $lojaId,
                    'nome'           => trim($res['nome']),
                    'sku'            => !empty($res['sku']) ? trim($res['sku']) : null,
                    'preco_custo'    => (float) ($res['preco_custo'] ?? $item['custo_unitario']),
                    'preco_unitario' => (float) ($res['preco_unitario'] ?? 0),
                    'quantidade'     => 0,
                ]);
                $produtoId = $novo->id;
            }

            if ($produtoId !== null) {
                if (($res['salvar_mapeamento'] ?? '0') === '1' && $pendente['fornecedor_id']) {
                    SkuDePara::updateOrCreate(
                        [
                            'empresa_id'     => $empresaId,
                            'loja_id'        => $lojaId,
                            'fornecedor_id'  => $pendente['fornecedor_id'],
                            'sku_fornecedor' => $item['sku_fornecedor'],
                        ],
                        ['produto_id' => $produtoId]
                    );
                }
                $item['produto_id'] = $produtoId;
            }
        }
        unset($item);

        $resultado = $this->ingestaoXml->processarPendente($pendente, $empresaId, $lojaId);
        $request->session()->forget('xml_pendente');

        $total     = count($resultado['importados']);
        $ignorados = count($resultado['nao_mapeados']);

        $msg = "{$total} item(ns) importado(s) com sucesso.";
        if ($ignorados > 0) {
            $msg .= " {$ignorados} item(ns) ignorado(s).";
        }

        return redirect()->route('estoque.index')
            ->with('xml_resultado', $resultado)
            ->with('success', $msg);
    }
}
