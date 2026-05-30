<?php

namespace App\Modules\Venda\Http\Controllers;

use App\Http\Controllers\Controller;
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
