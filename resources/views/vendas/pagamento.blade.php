<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forma de Pagamento | Baterias ERP</title>
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
        
        .header { height: 56px; background: var(--dark); display: flex; align-items: center; padding: 0 1.5rem; gap: 1rem; position: sticky; top: 0; z-index: 100; }
        .brand { font-weight: 900; font-size: 1.1rem; color: var(--gold); }
        .clock { font-size: .85rem; font-weight: 600; color: #e5e7eb; }
        .spacer { flex: 1; }
        
        .container { display: flex; height: calc(100vh - 56px); }
        .aside { flex: 0 0 300px; background: var(--white); border-right: 1px solid var(--border); overflow-y: auto; padding: 1.5rem; }
        .content { flex: 1; display: flex; align-items: center; justify-content: center; padding: 2rem; }
        
        .modal { background: var(--white); border-radius: 1rem; box-shadow: 0 10px 40px rgba(0,0,0,.1); padding: 2rem; max-width: 400px; width: 100%; }
        
        .aside-title { font-size: .75rem; font-weight: 700; text-transform: uppercase; color: var(--muted); margin-bottom: 1rem; letter-spacing: .05em; }
        .aside-item { padding: .8rem; background: var(--bg); border-radius: .5rem; margin-bottom: .75rem; font-size: .85rem; }
        .aside-item-name { font-weight: 700; margin-bottom: .2rem; }
        .aside-item-qty { color: var(--muted); font-size: .8rem; }
        .aside-item-price { font-weight: 900; color: var(--gold); margin-top: .3rem; }
        
        .aside-summary { background: var(--dark); color: var(--white); border-radius: .5rem; padding: 1rem; margin-top: 1.5rem; }
        .summary-line { display: flex; justify-content: space-between; font-size: .85rem; margin-bottom: .5rem; color: #d1d5db; }
        .summary-div { border-top: 1px solid #374151; margin: .75rem 0; }
        .summary-total { display: flex; justify-content: space-between; align-items: baseline; margin-top: .75rem; }
        .summary-label { font-size: .7rem; text-transform: uppercase; color: #9ca3af; font-weight: 700; }
        .summary-value { font-size: 1.5rem; font-weight: 900; color: var(--green); }
        
        .modal-title { font-size: 1.5rem; font-weight: 900; margin-bottom: 1.5rem; }
        .modal-section { margin-bottom: 1.5rem; }
        .label { font-size: .75rem; font-weight: 700; text-transform: uppercase; color: var(--muted); margin-bottom: .5rem; letter-spacing: .05em; display: block; }
        
        .radio-group { display: flex; flex-direction: column; gap: .75rem; }
        .radio-item { display: flex; align-items: center; gap: .75rem; }
        .radio-item input[type="radio"] { width: 20px; height: 20px; cursor: pointer; accent-color: var(--gold); }
        .radio-item label { flex: 1; cursor: pointer; font-weight: 500; }
        
        .input { background: var(--bg); border: 1.5px solid var(--border); border-radius: .5rem; font-family: inherit; font-weight: 500; color: var(--dark); padding: .65rem .9rem; outline: none; width: 100%; transition: border-color .15s; }
        .input:focus { border-color: var(--gold); }
        
        .button-group { display: flex; gap: .75rem; }
        .btn { flex: 1; border: none; border-radius: .5rem; padding: .85rem; font-family: inherit; font-weight: 700; font-size: .9rem; text-transform: uppercase; cursor: pointer; transition: all .15s; }
        .btn-secondary { background: var(--border); color: var(--dark); }
        .btn-secondary:hover { background: #d1d5db; }
        .btn-primary { background: linear-gradient(135deg, var(--gold) 0%, #d97706 100%); color: var(--dark); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(245,158,11,.3); }
    </style>
</head>
<body>

<header class="header">
    <span class="clock">{{ $tenantEmpresa->nome }} - {{ $tenantLojaAtual->nome }}</span>
    <span class="spacer"></span>
    <a href="{{ route('welcome') }}" style="color: #e5e7eb; text-decoration: none;">← Voltar</a>
</header>

<div class="container">
    <!-- RESUMO DA VENDA -->
    <div class="aside">
        <div class="aside-title">Itens da Venda</div>
        @foreach ($items as $item)
            <div class="aside-item">
                <div class="aside-item-name">{{ $item['nome'] }}</div>
                <div class="aside-item-qty">{{ $item['quantidade'] }}x R$ {{ number_format($item['preco_unitario'], 2, ',', '.') }}</div>
                <div class="aside-item-price">R$ {{ number_format($item['subtotal'], 2, ',', '.') }}</div>
            </div>
        @endforeach
        
        <div class="aside-summary">
            <div class="summary-line"><span>Subtotal</span><span>R$ {{ number_format($subtotal, 2, ',', '.') }}</span></div>
            <div class="summary-line"><span>Desconto</span><span>-R$ {{ number_format($desconto, 2, ',', '.') }}</span></div>
            <div class="summary-div"></div>
            <div class="summary-total">
                <span class="summary-label">Total</span>
                <span class="summary-value">R$ {{ number_format($total, 2, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- FORMULÁRIO DE PAGAMENTO -->
    <div class="content">
        <div class="modal">
            <h1 class="modal-title">Forma de Pagamento</h1>
            
            <form method="POST" action="{{ route('vendas.confirmar') }}">
                @csrf
                
                <div class="modal-section">
                    <label class="label">Escolha a forma de pagamento</label>
                    <div class="radio-group">
                        @foreach ($formasPagamento as $key => $label)
                            <div class="radio-item">
                                <input type="radio" id="pag_{{ $key }}" name="forma_pagamento" value="{{ $key }}" required>
                                <label for="pag_{{ $key }}">{{ $label }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="modal-section">
                    <label class="label" for="email">Email do Cliente (opcional)</label>
                    <input type="email" id="email" name="email_cliente" class="input" placeholder="cliente@exemplo.com">
                </div>

                <div class="button-group">
                    <a href="{{ route('welcome') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
