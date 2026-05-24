<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Venda;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class EstoqueVendaController extends Controller
{
    public function index(): View
    {
        $produtos = Produto::query()->orderBy('nome')->get();

        return view('welcome', [
            'produtos' => $produtos,
        ]);
    }

    public function estoque(): View
    {
        $produtos = Produto::query()->orderBy('nome')->get();

        $vendasRecentes = Venda::query()
            ->with('produto')
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
        $filtros = $request->validate([
            'data_inicio' => ['nullable', 'date'],
            'data_fim' => ['nullable', 'date', 'after_or_equal:data_inicio'],
        ]);

        $dataInicio = $filtros['data_inicio'] ?? now()->toDateString();
        $dataFim = $filtros['data_fim'] ?? now()->toDateString();

        $vendasPeriodo = Venda::query()
            ->with('produto')
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
            ->whereDate('data_venda', '>=', $dataInicio)
            ->whereDate('data_venda', '<=', $dataFim)
            ->latest()
            ->limit(10)
            ->get();

        $produtosEstoque = Produto::query()
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
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:255', 'unique:produtos,nome'],
            'quantidade' => ['required', 'integer', 'min:0'],
            'preco_custo' => ['required', 'numeric', 'min:0'],
            'preco_unitario' => ['required', 'numeric', 'min:0'],
        ]);

        Produto::query()->create($dados);

        return back()->with('success', 'Produto cadastrado no estoque com sucesso.');
    }

    public function updatePrecoVenda(Request $request, Produto $produto): RedirectResponse
    {
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
        $dados = $request->validate([
            'produto_id' => ['required', 'exists:produtos,id'],
            'quantidade' => ['required', 'integer', 'min:1'],
            'preco_custo' => ['required', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($dados): void {
            $produto = Produto::query()
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
        $dados = $request->validate([
            'items' => ['sometimes', 'array', 'min:1'],
            'items.*.produto_id' => ['required_with:items', 'exists:produtos,id'],
            'items.*.quantidade' => ['required_with:items', 'integer', 'min:1'],
            'produto_id' => ['required_without:items', 'exists:produtos,id'],
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

        DB::transaction(function () use ($itens, $descontoPedido, $dataVenda, $erroEstoqueCampo): void {
            $linhas = collect();
            $subtotalPedido = 0.0;

            foreach ($itens as $item) {
                $produto = Produto::query()
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

    /**
     * @return \Illuminate\Support\Collection<int, Venda>
     */
    private function vendasDeHoje()
    {
        return Venda::query()
            ->with('produto')
            ->whereDate('data_venda', now()->toDateString())
            ->latest()
            ->get();
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

    public function showPagamento(): View
    {
        $items = session('checkout_items', []);
        $desconto = session('checkout_desconto', 0);

        if (empty($items)) {
            return redirect()->route('welcome');
        }

        $formasPagamento = [
            'dinheiro' => 'Dinheiro',
            'credito' => 'Cartão Crédito',
            'debito' => 'Cartão Débito',
            'pix' => 'PIX',
        ];

        return view('vendas.pagamento', [
            'items' => $items,
            'desconto' => $desconto,
            'formasPagamento' => $formasPagamento,
        ]);
    }

    public function confirmarVenda(Request $request): RedirectResponse
    {
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

        DB::transaction(function () use ($itens, $descontoPedido, $dataVenda): void {
            $linhas = collect();
            $subtotalPedido = 0.0;

            foreach ($itens as $item) {
                $produto = Produto::query()
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
                    'produto_id' => $produto->id,
                    'quantidade' => $linha['quantidade'],
                    'preco_unitario' => $linha['preco_unitario'],
                    'desconto' => $descontoItem,
                    'total' => round($linha['subtotal'] - $descontoItem, 2),
                    'data_venda' => $dataVenda,
                ]);
            }
        });

        $request->session()->forget(['checkout_items', 'checkout_desconto']);

        return redirect()->route('welcome')->with('success', 'Venda registrada com sucesso!');
    }
}
