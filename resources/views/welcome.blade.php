<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PDV | Baterias ERP</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg: #f3f4f6;
            --white: #ffffff;
            --dark: #1f2937;
            --gold: #f59e0b;
            --gold-light: #fef3c7;
            --green: #10b981;
            --red: #ef4444;
            --border: #e5e7eb;
            --muted: #6b7280;
        }
        html, body { height: 100%; font-family: 'Inter', system-ui, sans-serif; background: var(--bg); color: var(--dark); }
        
        /* HEADER */
        .header { height: 56px; background: var(--dark); display: flex; align-items: center; padding: 0 1.5rem; gap: 1rem; position: sticky; top: 0; z-index: 100; }
        .brand { font-weight: 900; font-size: 1.1rem; color: var(--gold); }
        .spacer { flex: 1; }
        .clock { font-size: .85rem; color: #9ca3af; font-weight: 600; }
        .btn-exit, .btn-fullscreen { background: #374151; color: #e5e7eb; border: none; border-radius: .5rem; padding: .4rem .85rem; font-size: .82rem; font-weight: 600; cursor: pointer; transition: background .2s; }
        .btn-exit:hover, .btn-fullscreen:hover { background: #4b5563; }

        /* LAYOUT */
        .container { display: grid; grid-template-columns: 1fr 360px; height: calc(100vh - 56px); overflow: hidden; gap: 0; }

        /* PRODUTOS (LEFT) */
        .productos { display: flex; flex-direction: column; overflow: hidden; border-right: 1px solid var(--border); }
        .prod-header { background: var(--white); border-bottom: 1px solid var(--border); padding: 1rem; display: flex; align-items: center; gap: .75rem; }
        .prod-title { font-size: .75rem; font-weight: 700; text-transform: uppercase; color: var(--muted); letter-spacing: .05em; }
        .search { flex: 1; background: var(--bg); border: 1.5px solid var(--border); border-radius: .5rem; font-family: inherit; font-size: .9rem; color: var(--dark); padding: .45rem .8rem; outline: none; }
        .search:focus { border-color: var(--gold); }
        .search::placeholder { color: var(--muted); }

        .grid { flex: 1; overflow-y: auto; padding: 1rem; display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: .7rem; align-content: start; }
        .grid::-webkit-scrollbar { width: 6px; }
        .grid::-webkit-scrollbar-thumb { background: var(--border); border-radius: 3px; }

        .card {
            background: var(--white);
            border: 2px solid var(--border);
            border-radius: .6rem;
            padding: .95rem;
            cursor: pointer;
            transition: all .15s;
            user-select: none;
        }
        .card:hover { border-color: var(--gold); box-shadow: 0 2px 8px rgba(0,0,0,.08); transform: translateY(-1px); }
        .card.active { border-color: var(--gold); background: var(--gold-light); }
        .card.out-of-stock { opacity: .4; pointer-events: none; }
        .card-name { font-weight: 700; font-size: .85rem; color: var(--dark); margin-bottom: .4rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .card-price { font-weight: 900; font-size: 1.15rem; color: var(--dark); }
        .card-stock { font-size: .7rem; color: var(--muted); margin-top: .3rem; }
        .card-stock.low { color: var(--red); font-weight: 700; }

        /* CHECKOUT (RIGHT) */
        .checkout { background: var(--white); display: flex; flex-direction: column; overflow: hidden; box-shadow: -2px 0 12px rgba(0,0,0,.08); }
        .co-header { background: var(--dark); color: var(--white); padding: 1rem; border-bottom: 1px solid var(--border); }
        .co-title { font-size: .8rem; font-weight: 700; text-transform: uppercase; color: #9ca3af; margin-bottom: .2rem; letter-spacing: .05em; }
        .co-heading { font-size: 1rem; font-weight: 800; }

        .co-body { flex: 1; overflow-y: auto; padding: 1.2rem; display: flex; flex-direction: column; gap: 1rem; }
        .co-body::-webkit-scrollbar { width: 4px; }
        .co-body::-webkit-scrollbar-thumb { background: var(--border); border-radius: 2px; }

        .label { font-size: .7rem; font-weight: 700; text-transform: uppercase; color: var(--muted); margin-bottom: .4rem; letter-spacing: .05em; display: block; }

        .product-box { background: var(--bg); border: 2px dashed var(--border); border-radius: .6rem; padding: .85rem; min-height: 60px; display: flex; align-items: center; justify-content: space-between; transition: all .2s; }
        .product-box.active { background: var(--gold-light); border-color: var(--gold); border-style: solid; }
        .prod-placeholder { font-size: .88rem; color: var(--muted); }
        .prod-name { font-weight: 800; font-size: 1rem; color: var(--dark); }
        .prod-price { font-weight: 900; font-size: 1.05rem; color: var(--gold); }

        .input { background: var(--bg); border: 1.5px solid var(--border); border-radius: .5rem; font-family: inherit; font-weight: 700; color: var(--dark); padding: .55rem .8rem; outline: none; width: 100%; transition: border-color .15s; }
        .input:focus { border-color: var(--gold); }
        .input.large { font-size: 1.45rem; padding: .65rem; text-align: center; }
        .input.medium { font-size: 1rem; text-align: center; }

        .cart { background: var(--bg); border: 1px solid var(--border); border-radius: .6rem; overflow: hidden; }
        .cart-empty { padding: .75rem .9rem; font-size: .82rem; color: var(--muted); }
        .cart-list { list-style: none; }
        .cart-item { display: grid; grid-template-columns: 1fr auto auto; gap: .5rem; align-items: center; padding: .6rem .8rem; border-top: 1px solid var(--border); }
        .cart-item:first-child { border-top: none; }
        .cart-name { font-size: .84rem; font-weight: 700; color: var(--dark); }
        .cart-meta { font-size: .74rem; color: var(--muted); }
        .cart-total { font-size: .82rem; font-weight: 800; color: var(--dark); }
        .cart-remove { background: transparent; border: none; color: var(--red); font-size: .8rem; font-weight: 700; cursor: pointer; }

        .qty-row { display: grid; grid-template-columns: 1fr auto; gap: .5rem; align-items: end; }
        .btn-add { background: var(--dark); color: var(--white); border: none; border-radius: .5rem; padding: .68rem .9rem; font-size: .78rem; font-weight: 800; text-transform: uppercase; cursor: pointer; }
        .btn-add:disabled { opacity: .5; cursor: not-allowed; }

        .btn-submit { width: 100%; background: linear-gradient(135deg, var(--gold) 0%, #d97706 100%); color: var(--dark); font-family: inherit; font-weight: 900; font-size: 1rem; text-transform: uppercase; letter-spacing: .05em; border: none; border-radius: .6rem; padding: .95rem; cursor: pointer; transition: all .15s; }
        .btn-submit:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(245,158,11,.3); }
        .btn-submit:disabled { background: var(--border); color: var(--muted); cursor: not-allowed; }
        .btn-calc { width: 100%; background: #374151; color: #e5e7eb; font-family: inherit; font-weight: 700; font-size: .85rem; text-transform: uppercase; letter-spacing: .04em; border: none; border-radius: .6rem; padding: .65rem; cursor: pointer; transition: background .15s; margin-top: .5rem; }
        .btn-calc:hover { background: #4b5563; }

        /* MODAL CALCULADORA */
        .calc-overlay { display: none; position: fixed; inset: 0; background: rgba(15,23,42,.55); z-index: 1200; align-items: center; justify-content: center; padding: 1rem; }
        .calc-overlay.open { display: flex; }
        .calc-box { background: #fff; border-radius: 1rem; padding: 1.5rem; width: 100%; max-width: 360px; box-shadow: 0 18px 45px rgba(15,23,42,.28); }
        .calc-title { font-size: 1.05rem; font-weight: 900; color: var(--dark); margin-bottom: 1.2rem; display: flex; justify-content: space-between; align-items: center; }
        .calc-close { background: none; border: none; font-size: 1.2rem; cursor: pointer; color: var(--muted); line-height: 1; }
        .calc-total-display { background: var(--dark); color: var(--green); border-radius: .6rem; padding: .85rem 1rem; text-align: right; margin-bottom: 1rem; }
        .calc-total-label { font-size: .65rem; text-transform: uppercase; color: #9ca3af; font-weight: 700; letter-spacing: .05em; margin-bottom: .2rem; }
        .calc-total-value { font-size: 1.6rem; font-weight: 900; }
        .calc-row { display: flex; gap: .75rem; align-items: flex-end; margin-bottom: 1rem; }
        .calc-field { flex: 1; }
        .calc-field label { font-size: .7rem; font-weight: 700; text-transform: uppercase; color: var(--muted); letter-spacing: .05em; display: block; margin-bottom: .35rem; }
        .calc-field input { background: var(--bg); border: 1.5px solid var(--border); border-radius: .5rem; font-family: inherit; font-weight: 700; color: var(--dark); padding: .6rem .8rem; outline: none; width: 100%; font-size: 1rem; text-align: center; transition: border-color .15s; }
        .calc-field input:focus { border-color: var(--gold); }
        .calc-result { background: #fef3c7; border: 1.5px solid var(--gold); border-radius: .6rem; padding: .75rem 1rem; margin-bottom: 1.2rem; display: flex; justify-content: space-between; align-items: center; }
        .calc-result-label { font-size: .75rem; font-weight: 700; text-transform: uppercase; color: #92400e; }
        .calc-result-val { font-size: 1.1rem; font-weight: 900; color: var(--dark); }
        .calc-result-final { font-size: .8rem; color: var(--muted); margin-top: .3rem; }
        .calc-actions { display: flex; gap: .75rem; }
        .calc-btn { flex: 1; border: none; border-radius: .5rem; padding: .75rem; font-family: inherit; font-weight: 700; font-size: .85rem; text-transform: uppercase; cursor: pointer; transition: all .15s; }
        .calc-btn-cancel { background: var(--border); color: var(--dark); }
        .calc-btn-cancel:hover { background: #d1d5db; }
        .calc-btn-apply { background: linear-gradient(135deg, var(--gold) 0%, #d97706 100%); color: var(--dark); }
        .calc-btn-apply:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(245,158,11,.35); }

        /* TOAST */
        .toast { position: fixed; top: 64px; left: 50%; transform: translateX(-50%); z-index: 999; padding: .8rem 1.2rem; border-radius: .5rem; font-size: .85rem; font-weight: 600; min-width: 280px; }
        .toast.success { background: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; }
        .toast.error { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; }

        @media (max-width: 768px) {
            .container { grid-template-columns: 1fr; }
            .checkout { border-top: 1px solid var(--border); border-right: none; }
            .grid { grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); }
        }
    </style>
</head>
<body>

<header class="header">
    @if ($tenantEmpresa && $tenantLojaAtual)
        <span class="clock" style="color: white">{{ $tenantEmpresa->nome }} - {{ $tenantLojaAtual->nome }}</span>
    @endif
    <span class="spacer"></span>
    @if ($tenantLojas->count() > 1)
        <form method="POST" action="{{ route('lojas.switch') }}" style="display:inline-flex;" class="js-loja-switch-form" data-require-password="{{ auth()->user()?->troca_loja_senha ? '1' : '0' }}">
            @csrf
            <select name="loja_id" data-current-loja-id="{{ $tenantLojaAtual?->id }}" class="input" onchange="window.handleLojaSwitchChange && window.handleLojaSwitchChange(this)" style="width: 180px; padding: .38rem .55rem; font-size: .8rem;">
                @foreach ($tenantLojas as $loja)
                    <option value="{{ $loja->id }}" @selected($tenantLojaAtual && $tenantLojaAtual->id === $loja->id)>{{ $loja->nome }}</option>
                @endforeach
            </select>
        </form>
    @endif
    <span class="clock" id="clock"></span>
    <a href="{{ route('estoque.index') }}" class="btn-exit">Estoque</a>
    <button class="btn-fullscreen" id="btn-fullscreen" title="Fullscreen">⛶</button>
    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
        @csrf
        <button class="btn-exit">Sair</button>
    </form>
</header>

@if (session('success'))
    <div class="toast success" id="toast">✓ {{ session('success') }}</div>
@endif
@if ($errors->any())
    <div class="toast error" id="toast">✕ @foreach($errors->all() as $e){{ $e }}@endforeach</div>
@endif

<div class="container">
    <!-- PRODUTOS -->
    <div class="productos">
        <div class="prod-header">
            <span class="prod-title">Produtos</span>
            <input type="text" class="search" id="search" placeholder="Buscar..." autocomplete="off">
        </div>
        <div class="grid" id="grid">
            @foreach ($produtos as $p)
                <div class="card {{ $p->quantidade == 0 ? 'out-of-stock' : '' }}" data-id="{{ $p->id }}" data-name="{{ $p->nome }}" data-price="{{ number_format($p->preco_unitario, 2, '.', '') }}" data-stock="{{ $p->quantidade }}">
                    <div class="card-name" title="{{ $p->nome }}">{{ $p->nome }}</div>
                    <div class="card-price">R$ {{ number_format($p->preco_unitario, 2, ',', '.') }}</div>
                    <div class="card-stock {{ $p->quantidade > 0 && $p->quantidade <= 5 ? 'low' : '' }}">{{ $p->quantidade > 0 ? 'Estoque: '.$p->quantidade : 'Sem estoque' }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- CHECKOUT -->
    <div class="checkout">
        <div class="co-header">
            <div class="co-title">Ponto de Venda</div>
            <div class="co-heading">Nova Venda</div>
        </div>
        <div style="display:contents;">
            <div class="co-body">
                <div>
                    <label class="label">Produto</label>
                    <div class="product-box" id="pbox">
                        <span class="prod-placeholder" id="pph">Clique em um produto</span>
                        <span class="prod-name" id="pname" style="display:none;"></span>
                        <span class="prod-price" id="pprice" style="display:none;"></span>
                    </div>
                </div>
                <div>
                    <label class="label" for="qty">Quantidade</label>
                    <div class="qty-row">
                        <input id="qty" name="quantidade" type="number" min="1" value="1" class="input large" required>
                        <button type="button" class="btn-add" id="btn-add" disabled>+ Item</button>
                    </div>
                </div>
                <div>
                    <label class="label">Itens da venda</label>
                    <div class="cart">
                        <div class="cart-empty" id="cart-empty">Nenhum item adicionado.</div>
                        <ul class="cart-list" id="cart-list"></ul>
                    </div>
                </div>
                <div>
                    <label class="label" for="disc">Desconto (R$)</label>
                    <input id="disc" name="desconto" type="number" step="0.01" min="0" value="0" class="input medium" required>
                </div>
                <button type="button" class="btn-submit" id="btn" disabled>✓ Próximo</button>
                <button type="button" class="btn-calc" id="btn-calc">Calcular Desconto</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL CALCULADORA DE DESCONTO -->
<div class="calc-overlay" id="calcOverlay">
    <div class="calc-box">
        <div class="calc-title">
             Calculadora de Desconto
            <button class="calc-close" id="calcClose">✕</button>
        </div>
        <div class="calc-total-display">
            <div class="calc-total-label">Total do Carrinho</div>
            <div class="calc-total-value" id="calcTotalDisplay">R$ 0,00</div>
        </div>
        <div class="calc-row">
            <div class="calc-field">
                <label>Desconto (%)</label>
                <input type="number" id="calcPct" min="0" max="100" step="0.1" value="" placeholder="ex: 10">
            </div>
            <div class="calc-field">
                <label>Desconto (R$)</label>
                <input type="number" id="calcVal" min="0" step="0.01" value="" placeholder="ex: 50,00">
            </div>
        </div>
        <div class="calc-result">
            <div>
                <div class="calc-result-label">Desconto calculado</div>
                <div class="calc-result-final" id="calcFinalLine">Total final: —</div>
            </div>
            <div class="calc-result-val" id="calcResultVal">R$ 0,00</div>
        </div>
        <div class="calc-actions">
            <button class="calc-btn calc-btn-cancel" id="calcCancel">Fechar</button>
            <button class="calc-btn calc-btn-apply" id="calcApply">Aplicar Desconto</button>
        </div>
    </div>
</div>

<div id="modalSenhaLoja" style="display:none; position: fixed; inset: 0; background: rgba(15, 23, 42, .55); z-index: 1100; align-items: center; justify-content: center; padding: 1rem;">
    <div style="width: 100%; max-width: 420px; background: #fff; border-radius: .95rem; padding: 1.1rem; box-shadow: 0 18px 45px rgba(15, 23, 42, .25);">
        <h3 style="margin: 0 0 .45rem; font-size: 1.08rem; color: #0f172a;">Confirmar troca de loja</h3>
        <p style="margin: 0 0 .8rem; color: #64748b; font-size: .9rem;">Digite a senha de segurança para continuar.</p>
        <input id="inputSenhaLoja" type="password" placeholder="Senha de segurança" style="width: 100%; border: 1px solid #cbd5e1; border-radius: .65rem; padding: .62rem .7rem; margin-bottom: .8rem;">
        <div style="display: flex; justify-content: flex-end; gap: .55rem;">
            <button type="button" id="btnCancelarSenhaLoja" style="border: 1px solid #cbd5e1; background: #fff; border-radius: .6rem; padding: .52rem .75rem; cursor: pointer;">Cancelar</button>
            <button type="button" id="btnConfirmarSenhaLoja" style="border: none; background: linear-gradient(135deg,#fbbf24,#f59e0b); color: #111827; border-radius: .6rem; padding: .52rem .75rem; font-weight: 700; cursor: pointer;">Confirmar</button>
        </div>
    </div>
</div>

<script>
(function(){
    const modalSenhaLoja = document.getElementById('modalSenhaLoja');
    const inputSenhaLoja = document.getElementById('inputSenhaLoja');
    const btnCancelarSenhaLoja = document.getElementById('btnCancelarSenhaLoja');
    const btnConfirmarSenhaLoja = document.getElementById('btnConfirmarSenhaLoja');
    let formPendente = null;
    let selectPendente = null;
    let lojaAnterior = null;

    function fecharModalLoja() {
        if (!modalSenhaLoja) {
            return;
        }
        modalSenhaLoja.style.display = 'none';
        if (inputSenhaLoja) {
            inputSenhaLoja.value = '';
        }
        if (selectPendente && lojaAnterior) {
            selectPendente.value = lojaAnterior;
        }
        formPendente = null;
        selectPendente = null;
        lojaAnterior = null;
    }

    window.handleLojaSwitchChange = function (select) {
        const form = select.form;
        if (!form) {
            return;
        }

        const requirePassword = form.dataset.requirePassword === '1';
        const atual = String(select.dataset.currentLojaId || '');
        const escolhida = String(select.value || '');

        if (escolhida === atual) {
            return;
        }

        if (!requirePassword) {
            form.submit();
            return;
        }

        formPendente = form;
        selectPendente = select;
        lojaAnterior = atual;
        if (modalSenhaLoja) {
            modalSenhaLoja.style.display = 'flex';
        }
        if (inputSenhaLoja) {
            setTimeout(() => inputSenhaLoja.focus(), 30);
        }
    };

    if (btnCancelarSenhaLoja) {
        btnCancelarSenhaLoja.addEventListener('click', fecharModalLoja);
    }

    if (modalSenhaLoja) {
        modalSenhaLoja.addEventListener('click', (event) => {
            if (event.target === modalSenhaLoja) {
                fecharModalLoja();
            }
        });
    }

    if (btnConfirmarSenhaLoja) {
        btnConfirmarSenhaLoja.addEventListener('click', () => {
            if (!formPendente || !inputSenhaLoja) {
                return;
            }

            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'senha_loja';
            hidden.value = inputSenhaLoja.value;
            formPendente.appendChild(hidden);
            formPendente.submit();
        });
    }

    // === CALCULADORA DE DESCONTO ===
    (function(){
        const overlay = document.getElementById('calcOverlay');
        const btnOpenCalc = document.getElementById('btn-calc');
        const btnClose = document.getElementById('calcClose');
        const btnCancel = document.getElementById('calcCancel');
        const btnApply = document.getElementById('calcApply');
        const inputPct = document.getElementById('calcPct');
        const inputVal = document.getElementById('calcVal');
        const totalDisplay = document.getElementById('calcTotalDisplay');
        const resultVal = document.getElementById('calcResultVal');
        const finalLine = document.getElementById('calcFinalLine');
        const fmtCalc = v => 'R$ ' + v.toLocaleString('pt-BR', {minimumFractionDigits:2, maximumFractionDigits:2});

        function getCartTotal() {
            let total = 0;
            Array.from(cart.values()).forEach(item => total += item.preco * item.quantidade);
            const desc = parseFloat(document.getElementById('disc').value) || 0;
            return Math.max(0, total - desc);
        }

        function updateCalc() {
            const cartTotal = getCartTotal();
            totalDisplay.textContent = fmtCalc(cartTotal);
            const pct = parseFloat(inputPct.value);
            const val = parseFloat(inputVal.value);
            let desconto = 0;
            if (!isNaN(pct) && inputPct.value !== '') {
                desconto = cartTotal * (pct / 100);
            } else if (!isNaN(val) && inputVal.value !== '') {
                desconto = val;
            }
            desconto = Math.min(desconto, cartTotal);
            const final = cartTotal - desconto;
            resultVal.textContent = fmtCalc(desconto);
            finalLine.textContent = 'Total final: ' + fmtCalc(final);
        }

        inputPct.addEventListener('input', function() {
            if (this.value !== '') inputVal.value = '';
            updateCalc();
        });
        inputVal.addEventListener('input', function() {
            if (this.value !== '') inputPct.value = '';
            updateCalc();
        });

        function openCalc() {
            inputPct.value = '';
            inputVal.value = '';
            updateCalc();
            overlay.classList.add('open');
            inputPct.focus();
        }
        function closeCalc() { overlay.classList.remove('open'); }

        btnOpenCalc.addEventListener('click', openCalc);
        btnClose.addEventListener('click', closeCalc);
        btnCancel.addEventListener('click', closeCalc);
        overlay.addEventListener('click', e => { if (e.target === overlay) closeCalc(); });

        btnApply.addEventListener('click', function() {
            const cartTotal = getCartTotal();
            const pct = parseFloat(inputPct.value);
            const val = parseFloat(inputVal.value);
            let desconto = 0;
            if (!isNaN(pct) && inputPct.value !== '') {
                desconto = cartTotal * (pct / 100);
            } else if (!isNaN(val) && inputVal.value !== '') {
                desconto = val;
            }
            desconto = Math.min(Math.max(0, desconto), cartTotal);
            document.getElementById('disc').value = desconto.toFixed(2);
            closeCalc();
        });
    })();
    // === FIM CALCULADORA ===

    const clock=document.getElementById('clock');
    function tick(){const d=new Date();clock.textContent=d.toLocaleDateString('pt-BR')+' '+d.toLocaleTimeString('pt-BR');}
    tick();setInterval(tick,1000);
    
    const toast=document.getElementById('toast');
    if(toast)setTimeout(()=>toast.style.opacity='0',3500);
    
    let selectedProduct=null;
    const cart=new Map();
    const qty=document.getElementById('qty'),disc=document.getElementById('disc'),pbox=document.getElementById('pbox'),pph=document.getElementById('pph'),pname=document.getElementById('pname'),pprice=document.getElementById('pprice'),btn=document.getElementById('btn'),btnAdd=document.getElementById('btn-add'),cartList=document.getElementById('cart-list'),cartEmpty=document.getElementById('cart-empty');
    const fmt=v=>'R$ '+v.toLocaleString('pt-BR',{minimumFractionDigits:2,maximumFractionDigits:2});

    function selectedQtyInCart(productId){
        const key=String(productId);
        return cart.has(key)?cart.get(key).quantidade:0;
    }

    function renderCart(){
        cartList.innerHTML='';
        if(cart.size===0){
            cartEmpty.style.display='block';
            btn.disabled=true;
            return;
        }
        cartEmpty.style.display='none';
        Array.from(cart.values()).forEach(item=>{
            const li=document.createElement('li');
            li.className='cart-item';
            li.innerHTML=`<div><div class="cart-name">${item.nome}</div><div class="cart-meta">${item.quantidade}x ${fmt(item.preco)}</div></div><div class="cart-total">${fmt(item.preco*item.quantidade)}</div><button type="button" class="cart-remove" data-remove="${item.id}">Remover</button>`;
            cartList.appendChild(li);
        });
        btn.disabled=false;
    }

    function updateAddButton(){
        if(!selectedProduct){
            btnAdd.disabled=true;
            return;
        }
        const q=parseInt(qty.value)||0;
        const reservado=selectedQtyInCart(selectedProduct.id);
        btnAdd.disabled=q<1||(reservado+q)>selectedProduct.stock;
    }
    
    document.querySelectorAll('.card').forEach(c=>c.addEventListener('click',()=>{
        document.querySelectorAll('.card').forEach(x=>x.classList.remove('active'));
        c.classList.add('active');
        selectedProduct={
            id:parseInt(c.dataset.id),
            nome:c.dataset.name,
            preco:parseFloat(c.dataset.price),
            stock:parseInt(c.dataset.stock),
        };
        pph.style.display='none';
        pname.style.display='inline';
        pprice.style.display='inline';
        pname.textContent=selectedProduct.nome;
        pprice.textContent=fmt(selectedProduct.preco);
        pbox.classList.add('active');
        updateAddButton();
        qty.select();
    }));

    btnAdd.addEventListener('click',()=>{
        if(!selectedProduct){
            return;
        }
        const q=parseInt(qty.value)||0;
        if(q<1){
            return;
        }
        const key=String(selectedProduct.id);
        const existente=cart.get(key);
        const qtdAtual=existente?existente.quantidade:0;
        if((qtdAtual+q)>selectedProduct.stock){
            return;
        }
        cart.set(key,{
            id:selectedProduct.id,
            nome:selectedProduct.nome,
            preco:selectedProduct.preco,
            quantidade:qtdAtual+q,
        });
        qty.value='1';
        renderCart();
        updateAddButton();
    });

    cartList.addEventListener('click',(event)=>{
        const target=event.target;
        if(!(target instanceof HTMLElement)){
            return;
        }
        const removeId=target.getAttribute('data-remove');
        if(!removeId){
            return;
        }
        cart.delete(removeId);
        renderCart();
        updateAddButton();
    });

    btn.addEventListener('click',()=>{
        if(cart.size===0){
            return;
        }
        const items=Array.from(cart.values()).map(item=>({
            produto_id:String(item.id),
            quantidade:String(item.quantidade),
        }));
        const desconto=document.getElementById('disc').value;
        const form=document.createElement('form');
        form.method='POST';
        form.action="{{ route('vendas.checkout') }}";
        
        const tokenInput=document.createElement('input');
        tokenInput.type='hidden';
        tokenInput.name='_token';
        tokenInput.value="{{ csrf_token() }}";
        form.appendChild(tokenInput);
        
        const itemsInput=document.createElement('input');
        itemsInput.type='hidden';
        itemsInput.name='items_json';
        itemsInput.value=JSON.stringify(items);
        form.appendChild(itemsInput);
        
        const descontoInput=document.createElement('input');
        descontoInput.type='hidden';
        descontoInput.name='desconto';
        descontoInput.value=desconto;
        form.appendChild(descontoInput);
        
        document.body.appendChild(form);
        if (document.fullscreenElement) {
            document.exitFullscreen().then(() => form.submit()).catch(() => form.submit());
        } else {
            form.submit();
        }
    });
    
    qty.addEventListener('input',updateAddButton);
    
    document.getElementById('search').addEventListener('input',function(){const q=this.value.toLowerCase();document.querySelectorAll('.card').forEach(c=>c.style.display=c.dataset.name.toLowerCase().includes(q)?'':'none');});

    const btnFullscreen=document.getElementById('btn-fullscreen');
    btnFullscreen.addEventListener('click',()=>{
        if(!document.fullscreenElement){
            document.documentElement.requestFullscreen().catch(err=>{console.error(`Erro ao ativar fullscreen: ${err.message}`);});
        } else {
            document.exitFullscreen();
        }
    });

    renderCart();
})();
</script>
</body>
</html>
