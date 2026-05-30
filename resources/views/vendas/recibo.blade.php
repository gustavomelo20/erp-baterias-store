<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recibo | Baterias ERP</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg: #f3f4f6;
            --white: #ffffff;
            --dark: #1f2937;
            --gold: #f59e0b;
            --green: #10b981;
            --border: #e5e7eb;
            --muted: #6b7280;
        }

        html, body {
            height: 100%;
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg);
            color: var(--dark);
        }

        .header {
            height: 56px;
            background: var(--dark);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            gap: 1rem;
        }
        .brand { font-weight: 900; font-size: 1.1rem; color: var(--gold); }
        .clock { font-size: .85rem; font-weight: 600; color: #e5e7eb; }

        .page-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem 1rem 3rem;
            gap: 1.5rem;
        }

        .success-banner {
            background: #d1fae5;
            border: 1.5px solid #6ee7b7;
            border-radius: .75rem;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            gap: .75rem;
            font-weight: 600;
            color: #065f46;
            max-width: 380px;
            width: 100%;
        }
        .success-icon { font-size: 1.4rem; }

        /* --- CUPOM FISCAL / RECIBO --- */
        .cupom {
            background: var(--white);
            width: 100%;
            max-width: 380px;
            border-radius: .75rem;
            box-shadow: 0 4px 24px rgba(0,0,0,.08);
            padding: 1.5rem;
            font-family: 'Courier New', Courier, monospace;
            font-size: .85rem;
        }

        .cupom-header {
            text-align: center;
            border-bottom: 2px dashed #d1d5db;
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }
        .cupom-empresa { font-size: 1rem; font-weight: 900; text-transform: uppercase; letter-spacing: .05em; }
        .cupom-loja { font-size: .8rem; color: var(--muted); margin-top: .2rem; }
        .cupom-info { font-size: .75rem; color: var(--muted); margin-top: .5rem; }

        .cupom-title {
            text-align: center;
            font-weight: 700;
            font-size: .8rem;
            text-transform: uppercase;
            letter-spacing: .1em;
            border-bottom: 1px dashed #d1d5db;
            padding-bottom: .75rem;
            margin-bottom: .75rem;
        }

        .cupom-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: .5rem;
            margin-bottom: .5rem;
            font-size: .82rem;
        }
        .cupom-item-nome { flex: 1; }
        .cupom-item-qty { color: var(--muted); font-size: .75rem; }
        .cupom-item-preco { font-weight: 700; white-space: nowrap; }

        .cupom-divider { border: none; border-top: 1px dashed #d1d5db; margin: .75rem 0; }

        .cupom-linha {
            display: flex;
            justify-content: space-between;
            font-size: .82rem;
            margin-bottom: .35rem;
            color: var(--muted);
        }
        .cupom-linha.destaque {
            font-weight: 900;
            font-size: 1rem;
            color: var(--dark);
            margin-top: .5rem;
        }
        .cupom-linha.destaque span:last-child { color: var(--green); }

        .cupom-pagamento {
            background: #f9fafb;
            border-radius: .5rem;
            padding: .6rem .8rem;
            margin-top: .75rem;
            font-size: .82rem;
            display: flex;
            justify-content: space-between;
        }
        .cupom-pagamento span:first-child { color: var(--muted); }
        .cupom-pagamento span:last-child { font-weight: 700; }

        .cupom-footer {
            text-align: center;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 2px dashed #d1d5db;
            font-size: .75rem;
            color: var(--muted);
            line-height: 1.6;
        }

        /* --- BOTÕES DE AÇÃO --- */
        .action-buttons {
            display: flex;
            gap: .75rem;
            max-width: 380px;
            width: 100%;
        }
        .btn {
            flex: 1;
            border: none;
            border-radius: .5rem;
            padding: .85rem;
            font-family: 'Inter', system-ui, sans-serif;
            font-weight: 700;
            font-size: .9rem;
            text-transform: uppercase;
            cursor: pointer;
            transition: all .15s;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }
        .btn-secondary {
            background: #e5e7eb;
            color: var(--dark);
        }
        .btn-secondary:hover { background: #d1d5db; }
        .btn-print {
            background: linear-gradient(135deg, var(--gold) 0%, #d97706 100%);
            color: var(--dark);
        }
        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245,158,11,.3);
        }
        .btn-icon { margin-right: .4rem; }

        /* ============================================
           ESTILOS PARA IMPRESSÃO TÉRMICA (80mm)
           ============================================ */
        @media print {
            @page {
                size: 80mm auto;
                margin: 4mm;
            }

            html, body {
                background: #fff !important;
                font-family: 'Courier New', Courier, monospace !important;
                font-size: 11px !important;
                color: #000 !important;
                width: 80mm;
            }

            .header,
            .success-banner,
            .action-buttons,
            .no-print {
                display: none !important;
            }

            .page-wrap {
                padding: 0 !important;
                gap: 0 !important;
                align-items: flex-start;
            }

            .cupom {
                box-shadow: none !important;
                border-radius: 0 !important;
                padding: 0 !important;
                max-width: 100% !important;
                width: 100% !important;
                font-size: 11px !important;
            }

            .cupom-empresa { font-size: 13px !important; }
            .cupom-linha.destaque { font-size: 13px !important; }

            .cupom-pagamento {
                background: none !important;
                border: 1px solid #000 !important;
                border-radius: 0 !important;
            }

            .cupom-footer { font-size: 10px !important; }
        }
    </style>
</head>
<body>

<header class="header no-print">
    <span class="clock">{{ $tenantEmpresa->nome }} - {{ $tenantLojaAtual->nome }}</span>
</header>

<div class="page-wrap">

    <div class="success-banner no-print">
        <span>Venda registrada com sucesso!</span>
    </div>

    <!-- CUPOM -->
    <div class="cupom" id="cupom">
        <div class="cupom-header">
            <div class="cupom-empresa">{{ $empresa->nome_fantasia ?? $empresa->razao_social ?? $empresa->nome ?? 'Baterias ERP' }}</div>
            @if ($loja)
                <div class="cupom-loja">{{ $loja->nome }}</div>
            @endif
            @if (!empty($empresa->cnpj))
                <div class="cupom-info">CNPJ: {{ $empresa->cnpj }}</div>
            @endif
            @if (!empty($empresa->endereco) || !empty($empresa->logradouro))
                <div class="cupom-info">
                    {{ $empresa->logradouro ?? '' }}
                    @if (!empty($empresa->numero)), {{ $empresa->numero }}@endif
                    @if (!empty($empresa->bairro)) — {{ $empresa->bairro }}@endif
                </div>
            @endif
            @if (!empty($empresa->telefone))
                <div class="cupom-info">Tel: {{ $empresa->telefone }}</div>
            @endif
        </div>

        <div class="cupom-title">Cupom de Venda</div>

        <div style="font-size:.75rem;color:var(--muted);margin-bottom:.75rem;">
            Data: {{ $recibo['data_hora'] }}
        </div>

        @foreach ($recibo['itens'] as $item)
            <div class="cupom-item">
                <div class="cupom-item-nome">
                    {{ $item['nome'] }}
                    <div class="cupom-item-qty">{{ $item['quantidade'] }} x R$ {{ number_format($item['preco_unitario'], 2, ',', '.') }}</div>
                </div>
                <div class="cupom-item-preco">R$ {{ number_format($item['subtotal'], 2, ',', '.') }}</div>
            </div>
        @endforeach

        <hr class="cupom-divider">

        <div class="cupom-linha">
            <span>Subtotal</span>
            <span>R$ {{ number_format($recibo['subtotal'], 2, ',', '.') }}</span>
        </div>
        @if ($recibo['desconto'] > 0)
            <div class="cupom-linha">
                <span>Desconto</span>
                <span>-R$ {{ number_format($recibo['desconto'], 2, ',', '.') }}</span>
            </div>
        @endif
        <div class="cupom-linha destaque">
            <span>TOTAL</span>
            <span>R$ {{ number_format($recibo['total'], 2, ',', '.') }}</span>
        </div>

        <div class="cupom-pagamento">
            <span>Pagamento</span>
            <span>{{ $recibo['forma_pagamento'] }}</span>
        </div>

        @if (!empty($recibo['email_cliente']))
            <div style="font-size:.75rem;color:var(--muted);margin-top:.75rem;">
                Email: {{ $recibo['email_cliente'] }}
            </div>
        @endif

        <div class="cupom-footer">
            Obrigado pela preferência!<br>
            Volte sempre 
        </div>
    </div>

    <!-- BOTÕES -->
    <div class="action-buttons no-print">
        <a href="{{ route('welcome') }}" class="btn btn-secondary">PDV</a>
        <button class="btn btn-print" onclick="imprimirCupom()">
            <span class="btn-icon">🖨️</span> Imprimir
        </button>
    </div>

</div>

<script>
    function imprimirCupom() {
        window.print();
    }
</script>

</body>
</html>
