<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Venda;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View as ViewContract;
use Illuminate\View\View;

class EstoqueVendaController extends Controller
{
    public function index(Request $request): View
    {
        [$empresaId, $lojaId] = $this->tenantIds($request);

        $produtos = Produto::query()
            ->where('empresa_id', $empresaId)
            ->where('loja_id', $lojaId)
            ->orderBy('nome')
            ->get();

        return view('welcome', [
            'produtos' => $produtos,
        ]);
    }

    public function estoque(Request $request): View
    {
        [$empresaId, $lojaId] = $this->tenantIds($request);

        $produtos = Produto::query()
            ->where('empresa_id', $empresaId)
            ->where('loja_id', $lojaId)
            ->orderBy('nome')
            ->get();

        $vendasRecentes = Venda::query()
            ->with('produto')
            ->where('empresa_id', $empresaId)
            ->where('loja_id', $lojaId)
            ->latest()
            ->limit(10)
            ->get();

        return view('estoque', [
            'produtos' => $produtos,
            'vendasRecentes' => $vendasRecentes,
        ]);
    }

    public function dashboard(Request $request): View
    {
        [$empresaId, $lojaId] = $this->tenantIds($request);

        $filtros = $request->validate([
            'data_inicio' => ['nullable', 'date'],
            'data_fim' => ['nullable', 'date', 'after_or_equal:data_inicio'],
        ]);

        $dataInicio = $filtros['data_inicio'] ?? now()->toDateString();
        $dataFim = $filtros['data_fim'] ?? now()->toDateString();

        $vendasPeriodo = Venda::query()
            ->with('produto')
            ->where('empresa_id', $empresaId)
            ->where('loja_id', $lojaId)
            ->whereDate('data_venda', '>=', $dataInicio)
            ->whereDate('data_venda', '<=', $dataFim)
            ->latest()
            ->get();

        $resumoPeriodo = [
            'pedidos' => $vendasPeriodo->count(),
            'faturamento' => (float) $vendasPeriodo->sum('total'),
            'itens' => (int) $vendasPeriodo->sum('quantidade'),
            'lucro' => (float) $vendasPeriodo->sum(function (Venda $venda): float {
                $custoUnitario = (float) ($venda->produto->preco_custo ?? 0);
                $custoTotal = $custoUnitario * (int) $venda->quantidade;

                return round((float) $venda->total - $custoTotal, 2);
            }),
        ];

        $ultimasVendas = Venda::query()
            ->with('produto')
            ->where('empresa_id', $empresaId)
            ->where('loja_id', $lojaId)
            ->whereDate('data_venda', '>=', $dataInicio)
            ->whereDate('data_venda', '<=', $dataFim)
            ->latest()
            ->limit(10)
            ->get();

        $produtosEstoque = Produto::query()
            ->where('empresa_id', $empresaId)
            ->where('loja_id', $lojaId)
            ->orderBy('nome')
            ->get();

        return view('dashboard', [
            'resumoPeriodo' => $resumoPeriodo,
            'vendasPeriodo' => $vendasPeriodo,
            'ultimasVendas' => $ultimasVendas,
            'produtosEstoque' => $produtosEstoque,
            'filtros' => [
                'data_inicio' => $dataInicio,
                'data_fim' => $dataFim,
            ],
        ]);
    }

    public function storeProduto(Request $request): RedirectResponse
    {
        [$empresaId, $lojaId] = $this->tenantIds($request);

        $dados = $request->validate([
            'nome' => [
                'required',
                'string',
                'max:255',
                Rule::unique('produtos', 'nome')->where(fn ($query) => $query
                    ->where('empresa_id', $empresaId)
                    ->where('loja_id', $lojaId)
                ),
            ],
            'quantidade' => ['required', 'integer', 'min:0'],
            'preco_custo' => ['required', 'numeric', 'min:0'],
            'preco_unitario' => ['required', 'numeric', 'min:0'],
        ]);

        Produto::query()->create([
            ...$dados,
            'empresa_id' => $empresaId,
            'loja_id' => $lojaId,
        ]);

        return back()->with('success', 'Produto cadastrado no estoque com sucesso.');
    }

    public function updatePrecoVenda(Request $request, Produto $produto): RedirectResponse
    {
        [$empresaId, $lojaId] = $this->tenantIds($request);

        if ((int) $produto->empresa_id !== $empresaId || (int) $produto->loja_id !== $lojaId) {
            abort(404);
        }

        $dados = $request->validate([
            'preco_unitario' => ['required', 'numeric', 'min:0'],
        ]);

        $produto->update([
            'preco_unitario' => $dados['preco_unitario'],
        ]);

        return back()->with('success', 'Preço de venda atualizado com sucesso.');
    }

    public function storeEntradaEstoque(Request $request): RedirectResponse
    {
        [$empresaId, $lojaId] = $this->tenantIds($request);

        $dados = $request->validate([
            'produto_id' => [
                'required',
                Rule::exists('produtos', 'id')->where(fn ($query) => $query
                    ->where('empresa_id', $empresaId)
                    ->where('loja_id', $lojaId)
                ),
            ],
            'quantidade' => ['required', 'integer', 'min:1'],
            'preco_custo' => ['required', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($dados, $empresaId, $lojaId): void {
            $produto = Produto::query()
                ->where('empresa_id', $empresaId)
                ->where('loja_id', $lojaId)
                ->lockForUpdate()
                ->findOrFail($dados['produto_id']);

            $qtdNova = (int) $dados['quantidade'];
            $custNovo = (float) $dados['preco_custo'];
            $qtdAtual = (int) $produto->quantidade;
            $custAtual = (float) $produto->preco_custo;

            // Preço médio ponderado: (qtdAtual * custAtual + qtdNova * custNovo) / (qtdAtual + qtdNova)
            $totalQtd = $qtdAtual + $qtdNova;
            $precoMedio = $totalQtd > 0
                ? round(($qtdAtual * $custAtual + $qtdNova * $custNovo) / $totalQtd, 2)
                : $custNovo;

            $produto->update([
                'quantidade' => $totalQtd,
                'preco_custo' => $precoMedio,
            ]);
        });

        return back()->with('success', 'Estoque atualizado com preço médio ponderado.');
    }

    public function storeVenda(Request $request): RedirectResponse
    {
        [$empresaId, $lojaId] = $this->tenantIds($request);

        $dados = $request->validate([
            'items' => ['sometimes', 'array', 'min:1'],
            'items.*.produto_id' => [
                'required_with:items',
                Rule::exists('produtos', 'id')->where(fn ($query) => $query
                    ->where('empresa_id', $empresaId)
                    ->where('loja_id', $lojaId)
                ),
            ],
            'items.*.quantidade' => ['required_with:items', 'integer', 'min:1'],
            'produto_id' => [
                'required_without:items',
                Rule::exists('produtos', 'id')->where(fn ($query) => $query
                    ->where('empresa_id', $empresaId)
                    ->where('loja_id', $lojaId)
                ),
            ],
            'quantidade' => ['required_without:items', 'integer', 'min:1'],
            'desconto' => ['nullable', 'numeric', 'min:0'],
        ]);

        $itensBrutos = collect($dados['items'] ?? [[
            'produto_id' => $dados['produto_id'],
            'quantidade' => $dados['quantidade'],
        ]]);

        $itens = $itensBrutos
            ->groupBy('produto_id')
            ->map(function ($grupo, $produtoId): array {
                return [
                    'produto_id' => (int) $produtoId,
                    'quantidade' => (int) collect($grupo)->sum('quantidade'),
                ];
            })
            ->values();

        $erroEstoqueCampo = isset($dados['items']) ? 'items' : 'quantidade';
        $descontoPedido = round((float) ($dados['desconto'] ?? 0), 2);
        $dataVenda = now()->toDateString();

        DB::transaction(function () use ($itens, $descontoPedido, $dataVenda, $erroEstoqueCampo, $empresaId, $lojaId): void {
            $linhas = collect();
            $subtotalPedido = 0.0;

            foreach ($itens as $item) {
                $produto = Produto::query()
                    ->where('empresa_id', $empresaId)
                    ->where('loja_id', $lojaId)
                    ->lockForUpdate()
                    ->findOrFail($item['produto_id']);

                $quantidade = (int) $item['quantidade'];
                if ($produto->quantidade < $quantidade) {
                    throw ValidationException::withMessages([
                        $erroEstoqueCampo => 'Estoque insuficiente para o produto: '.$produto->nome.'.',
                    ]);
                }

                $precoUnitario = (float) $produto->preco_unitario;
                $subtotalItem = round($precoUnitario * $quantidade, 2);
                $subtotalPedido += $subtotalItem;

                $linhas->push([
                    'produto' => $produto,
                    'quantidade' => $quantidade,
                    'preco_unitario' => $precoUnitario,
                    'subtotal' => $subtotalItem,
                ]);
            }

            $subtotalPedido = round($subtotalPedido, 2);
            if ($descontoPedido > $subtotalPedido) {
                throw ValidationException::withMessages([
                    'desconto' => 'O desconto nao pode ser maior que o subtotal da venda.',
                ]);
            }

            $descontoRestante = $descontoPedido;
            foreach ($linhas as $index => $linha) {
                $produto = $linha['produto'];
                $produto->decrement('quantidade', $linha['quantidade']);

                $descontoItem = 0.0;
                if ($descontoPedido > 0 && $subtotalPedido > 0) {
                    if ($index === $linhas->count() - 1) {
                        $descontoItem = round($descontoRestante, 2);
                    } else {
                        $proporcao = $linha['subtotal'] / $subtotalPedido;
                        $descontoItem = round($descontoPedido * $proporcao, 2);
                        $descontoRestante = round($descontoRestante - $descontoItem, 2);
                    }
                }

                Venda::query()->create([
                    'empresa_id' => $empresaId,
                    'loja_id' => $lojaId,
                    'produto_id' => $produto->id,
                    'quantidade' => $linha['quantidade'],
                    'preco_unitario' => $linha['preco_unitario'],
                    'desconto' => $descontoItem,
                    'total' => round($linha['subtotal'] - $descontoItem, 2),
                    'data_venda' => $dataVenda,
                ]);
            }
        });

        return back()->with('success', 'Venda registrada e estoque atualizado.');
    }

    public function checkoutVenda(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'items_json' => ['required', 'json'],
            'desconto' => ['required', 'numeric', 'min:0'],
        ]);

        $items = json_decode($dados['items_json'], true);
        if (!is_array($items) || empty($items)) {
            return back()->with('error', 'Itens inválidos.');
        }

        $request->session()->put('checkout_items', $items);
        $request->session()->put('checkout_desconto', (float) $dados['desconto']);

        return redirect()->route('vendas.pagamento');
    }

    public function showPagamento(Request $request): RedirectResponse|ViewContract
    {
        [$empresaId, $lojaId] = $this->tenantIds($request);

        $items = session('checkout_items', []);
        $desconto = (float) session('checkout_desconto', 0);

        if (empty($items)) {
            return redirect()->route('welcome');
        }

        $produtos = Produto::query()
            ->where('empresa_id', $empresaId)
            ->where('loja_id', $lojaId)
            ->whereIn('id', collect($items)->pluck('produto_id')->map(fn ($id) => (int) $id)->all())
            ->get()
            ->keyBy('id');

        $itensDetalhados = collect($items)
            ->map(function (array $item) use ($produtos): ?array {
                $produto = $produtos->get((int) ($item['produto_id'] ?? 0));
                if (!$produto) {
                    return null;
                }

                $quantidade = max(1, (int) ($item['quantidade'] ?? 1));
                $precoUnitario = (float) $produto->preco_unitario;

                return [
                    'produto_id' => (int) $produto->id,
                    'nome' => $produto->nome,
                    'quantidade' => $quantidade,
                    'preco_unitario' => $precoUnitario,
                    'subtotal' => round($precoUnitario * $quantidade, 2),
                ];
            })
            ->filter()
            ->values();

        if ($itensDetalhados->isEmpty()) {
            return redirect()->route('welcome')->with('error', 'Nao foi possivel carregar os itens da venda.');
        }

        $subtotal = round((float) $itensDetalhados->sum('subtotal'), 2);
        $total = round(max($subtotal - $desconto, 0), 2);

        $formasPagamento = [
            'dinheiro' => 'Dinheiro',
            'credito' => 'Cartão Crédito',
            'debito' => 'Cartão Débito',
            'pix' => 'PIX',
        ];

        return view('vendas.pagamento', [
            'items' => $itensDetalhados,
            'subtotal' => $subtotal,
            'total' => $total,
            'desconto' => $desconto,
            'formasPagamento' => $formasPagamento,
        ]);
    }

    public function showRecibo(Request $request): RedirectResponse|ViewContract
    {
        [$empresaId, $lojaId] = $this->tenantIds($request);

        $recibo = session('recibo_venda');

        if (empty($recibo)) {
            return redirect()->route('welcome');
        }

        $empresa = \App\Models\Empresa::find($empresaId);
        $loja = \App\Models\Loja::find($lojaId);

        $request->session()->forget('recibo_venda');

        return view('vendas.recibo', [
            'recibo' => $recibo,
            'empresa' => $empresa,
            'loja' => $loja,
        ]);
    }

    public function confirmarVenda(Request $request): RedirectResponse
    {
        [$empresaId, $lojaId] = $this->tenantIds($request);

        $dados = $request->validate([
            'forma_pagamento' => ['required', 'in:dinheiro,credito,debito,pix'],
            'email_cliente' => ['nullable', 'email'],
        ]);

        $items = session('checkout_items', []);
        $desconto = session('checkout_desconto', 0);

        if (empty($items)) {
            return redirect()->route('welcome')->with('error', 'Sessão expirada.');
        }

        $itensBrutos = collect($items);

        $itens = $itensBrutos
            ->groupBy('produto_id')
            ->map(function ($grupo, $produtoId): array {
                return [
                    'produto_id' => (int) $produtoId,
                    'quantidade' => (int) collect($grupo)->sum('quantidade'),
                ];
            })
            ->values();

        $descontoPedido = round((float) $desconto, 2);
        $dataVenda = now()->toDateString();

        DB::transaction(function () use ($itens, $descontoPedido, $dataVenda, $empresaId, $lojaId): void {
            $linhas = collect();
            $subtotalPedido = 0.0;

            foreach ($itens as $item) {
                $produto = Produto::query()
                    ->where('empresa_id', $empresaId)
                    ->where('loja_id', $lojaId)
                    ->lockForUpdate()
                    ->findOrFail($item['produto_id']);

                $quantidade = (int) $item['quantidade'];
                if ($produto->quantidade < $quantidade) {
                    throw ValidationException::withMessages([
                        'itens' => 'Estoque insuficiente para o produto: '.$produto->nome.'.',
                    ]);
                }

                $precoUnitario = (float) $produto->preco_unitario;
                $subtotalItem = round($precoUnitario * $quantidade, 2);
                $subtotalPedido += $subtotalItem;

                $linhas->push([
                    'produto' => $produto,
                    'quantidade' => $quantidade,
                    'preco_unitario' => $precoUnitario,
                    'subtotal' => $subtotalItem,
                ]);
            }

            $subtotalPedido = round($subtotalPedido, 2);
            if ($descontoPedido > $subtotalPedido) {
                throw ValidationException::withMessages([
                    'desconto' => 'O desconto não pode ser maior que o subtotal da venda.',
                ]);
            }

            $descontoRestante = $descontoPedido;
            foreach ($linhas as $index => $linha) {
                $produto = $linha['produto'];
                $produto->decrement('quantidade', $linha['quantidade']);

                $descontoItem = 0.0;
                if ($descontoPedido > 0 && $subtotalPedido > 0) {
                    if ($index === $linhas->count() - 1) {
                        $descontoItem = round($descontoRestante, 2);
                    } else {
                        $proporcao = $linha['subtotal'] / $subtotalPedido;
                        $descontoItem = round($descontoPedido * $proporcao, 2);
                        $descontoRestante = round($descontoRestante - $descontoItem, 2);
                    }
                }

                Venda::query()->create([
                    'empresa_id' => $empresaId,
                    'loja_id' => $lojaId,
                    'produto_id' => $produto->id,
                    'quantidade' => $linha['quantidade'],
                    'preco_unitario' => $linha['preco_unitario'],
                    'desconto' => $descontoItem,
                    'total' => round($linha['subtotal'] - $descontoItem, 2),
                    'data_venda' => $dataVenda,
                ]);
            }
        });

        // Monta dados do recibo para exibição/impressão
        $itensBrutos = collect($items);
        $produtosRecibo = \App\Models\Produto::query()
            ->where('empresa_id', $empresaId)
            ->where('loja_id', $lojaId)
            ->whereIn('id', $itensBrutos->pluck('produto_id')->map(fn ($id) => (int) $id)->all())
            ->get()
            ->keyBy('id');

        $itensRecibo = $itensBrutos
            ->groupBy('produto_id')
            ->map(function ($grupo, $produtoId) use ($produtosRecibo): ?array {
                $produto = $produtosRecibo->get((int) $produtoId);
                if (!$produto) {
                    return null;
                }
                $quantidade = (int) collect($grupo)->sum('quantidade');
                $precoUnitario = (float) $produto->preco_unitario;
                return [
                    'nome' => $produto->nome,
                    'quantidade' => $quantidade,
                    'preco_unitario' => $precoUnitario,
                    'subtotal' => round($precoUnitario * $quantidade, 2),
                ];
            })
            ->filter()
            ->values()
            ->toArray();

        $subtotalRecibo = round(collect($itensRecibo)->sum('subtotal'), 2);
        $descontoPedido = round((float) $desconto, 2);
        $totalRecibo = round(max($subtotalRecibo - $descontoPedido, 0), 2);

        $formasPagamento = [
            'dinheiro' => 'Dinheiro',
            'credito' => 'Cartão Crédito',
            'debito' => 'Cartão Débito',
            'pix' => 'PIX',
        ];

        $request->session()->forget(['checkout_items', 'checkout_desconto']);
        $request->session()->put('recibo_venda', [
            'itens' => $itensRecibo,
            'subtotal' => $subtotalRecibo,
            'desconto' => $descontoPedido,
            'total' => $totalRecibo,
            'forma_pagamento' => $formasPagamento[$dados['forma_pagamento']] ?? $dados['forma_pagamento'],
            'email_cliente' => $dados['email_cliente'] ?? null,
            'data_hora' => now()->format('d/m/Y H:i:s'),
        ]);

        return redirect()->route('vendas.recibo');
    }

    /**
     * @return array{0:int,1:int}
     */
    private function tenantIds(Request $request): array
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        return [
            (int) $user->empresa_id,
            (int) $request->session()->get('loja_id'),
        ];
    }
}
