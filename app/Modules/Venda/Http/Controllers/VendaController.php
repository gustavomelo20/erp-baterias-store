<?php

namespace App\Modules\Venda\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Loja;
use App\Modules\Venda\DTOs\CheckoutVendaDTO;
use App\Modules\Venda\DTOs\ConfirmarVendaDTO;
use App\Modules\Venda\DTOs\StoreVendaDTO;
use App\Modules\Venda\Http\Requests\CheckoutVendaRequest;
use App\Modules\Venda\Http\Requests\ConfirmarVendaRequest;
use App\Modules\Venda\Http\Requests\StoreVendaRequest;
use App\Modules\Venda\UseCases\CheckoutVendaUseCase;
use App\Modules\Venda\UseCases\ConfirmarVendaUseCase;
use App\Modules\Venda\UseCases\RegistrarVendaUseCase;
use App\Modules\Venda\UseCases\ShowPagamentoUseCase;
use App\Modules\Venda\UseCases\ShowReciboUseCase;
use App\Traits\ResolvesTenantIds;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VendaController extends Controller
{
    use ResolvesTenantIds;

    public function __construct(
        private readonly RegistrarVendaUseCase $registrarVenda,
        private readonly CheckoutVendaUseCase  $checkoutVenda,
        private readonly ShowPagamentoUseCase  $showPagamento,
        private readonly ConfirmarVendaUseCase $confirmarVenda,
        private readonly ShowReciboUseCase     $showRecibo,
    ) {}

    public function storeVenda(StoreVendaRequest $request): RedirectResponse
    {
        [$empresaId, $lojaId] = $this->tenantIds($request);

        $this->registrarVenda->execute(
            StoreVendaDTO::fromArray($request->validated(), $empresaId, $lojaId)
        );

        return back()->with('success', 'Venda registrada e estoque atualizado.');
    }

    public function checkoutVenda(CheckoutVendaRequest $request): RedirectResponse
    {
        $dto    = CheckoutVendaDTO::fromArray($request->validated());
        $dados  = $this->checkoutVenda->execute($dto);

        if ($dados === null) {
            return back()->with('error', 'Itens inválidos.');
        }

        $request->session()->put('checkout_items',   $dados['items']);
        $request->session()->put('checkout_desconto', $dados['desconto']);

        return redirect()->route('vendas.pagamento');
    }

    public function showPagamento(Request $request): RedirectResponse|View
    {
        [$empresaId, $lojaId] = $this->tenantIds($request);

        $items   = session('checkout_items', []);
        $desconto = (float) session('checkout_desconto', 0);

        $dados = $this->showPagamento->execute($items, $desconto, $empresaId, $lojaId);

        if ($dados === null) {
            return redirect()->route('welcome')->with('error', 'Não foi possível carregar os itens da venda.');
        }

        return view('vendas.pagamento', $dados);
    }

    public function confirmarVenda(ConfirmarVendaRequest $request): RedirectResponse
    {
        [$empresaId, $lojaId] = $this->tenantIds($request);

        $items   = session('checkout_items', []);
        $desconto = (float) session('checkout_desconto', 0);

        if (empty($items)) {
            return redirect()->route('welcome')->with('error', 'Sessão expirada.');
        }

        $validated = $request->validated();

        // Orçamento: gera PDF sem salvar venda no banco
        if ($validated['forma_pagamento'] === 'orcamento') {
            $empresa = Empresa::find($empresaId);
            $loja    = Loja::find($lojaId);

            $request->session()->put('orcamento_dados', [
                'items'        => $items,
                'desconto'     => $desconto,
                'nome_cliente' => $validated['nome_cliente'] ?? null,
                'empresa'      => [
                    'nome'         => $empresa?->nome_fantasia ?: $empresa?->nome,
                    'cnpj'         => $empresa?->cnpj,
                    'telefone'     => $empresa?->telefone,
                    'email'        => $empresa?->email_fiscal,
                    'logradouro'   => $empresa?->logradouro,
                    'numero'       => $empresa?->numero,
                    'bairro'       => $empresa?->bairro,
                    'cidade'       => $empresa?->cidade,
                    'uf'           => $empresa?->uf,
                ],
                'loja_nome'    => $loja?->nome,
            ]);

            return redirect()->route('vendas.orcamento');
        }

        $dto = new ConfirmarVendaDTO(
            formaPagamento: $validated['forma_pagamento'],
            emailCliente:   $validated['email_cliente'] ?? null,
            items:          $items,
            desconto:       $desconto,
            empresaId:      $empresaId,
            lojaId:         $lojaId,
        );

        $recibo = $this->confirmarVenda->execute($dto);

        $request->session()->forget(['checkout_items', 'checkout_desconto']);
        $request->session()->put('recibo_venda', $recibo);

        return redirect()->route('vendas.recibo');
    }

    public function showOrcamento(Request $request): RedirectResponse|View
    {
        [$empresaId, $lojaId] = $this->tenantIds($request);

        $dados = session('orcamento_dados');

        if (empty($dados)) {
            return redirect()->route('welcome');
        }

        $request->session()->forget('orcamento_dados');
        $request->session()->forget(['checkout_items', 'checkout_desconto']);

        $items    = collect($dados['items'])->groupBy('produto_id');
        $empresa  = Empresa::find($empresaId);
        $loja     = Loja::find($lojaId);

        $produtosMap = \App\Models\Produto::query()
            ->where('empresa_id', $empresaId)
            ->where('loja_id', $lojaId)
            ->whereIn('id', collect($dados['items'])->pluck('produto_id')->map(fn ($id) => (int) $id)->all())
            ->get()
            ->keyBy('id');

        $itens = $items->map(function ($grupo, $produtoId) use ($produtosMap): ?array {
            $produto = $produtosMap->get((int) $produtoId);
            if (!$produto) {
                return null;
            }
            $quantidade    = (int) collect($grupo)->sum('quantidade');
            $precoUnitario = (float) $produto->preco_unitario;

            return [
                'nome'           => $produto->nome,
                'quantidade'     => $quantidade,
                'preco_unitario' => $precoUnitario,
                'subtotal'       => round($precoUnitario * $quantidade, 2),
            ];
        })->filter()->values()->toArray();

        $subtotal = round(collect($itens)->sum('subtotal'), 2);
        $desconto = round((float) $dados['desconto'], 2);
        $total    = round(max($subtotal - $desconto, 0), 2);

        return view('vendas.orcamento', [
            'numero'       => strtoupper(substr(uniqid(), -8)),
            'data_emissao' => now()->format('d/m/Y'),
            'validade'     => now()->addDays(7)->format('d/m/Y'),
            'itens'        => $itens,
            'subtotal'     => $subtotal,
            'desconto'     => $desconto,
            'total'        => $total,
            'nome_cliente' => $dados['nome_cliente'] ?? null,
            'empresa'      => [
                'nome'       => $empresa?->nome_fantasia ?: ($empresa?->nome ?? 'Baterias'),
                'cnpj'       => $empresa?->cnpj,
                'telefone'   => $empresa?->telefone,
                'email'      => $empresa?->email_fiscal,
                'logradouro' => $empresa?->logradouro,
                'numero'     => $empresa?->numero,
                'bairro'     => $empresa?->bairro,
                'cidade'     => $empresa?->cidade,
                'uf'         => $empresa?->uf,
            ],
            'loja_nome'    => $loja?->nome,
        ]);
    }

    public function showRecibo(Request $request): RedirectResponse|View
    {
        [$empresaId, $lojaId] = $this->tenantIds($request);

        $recibo = session('recibo_venda');

        if (empty($recibo)) {
            return redirect()->route('welcome');
        }

        $request->session()->forget('recibo_venda');

        return view('vendas.recibo', $this->showRecibo->execute($recibo, $empresaId, $lojaId));
    }
}
