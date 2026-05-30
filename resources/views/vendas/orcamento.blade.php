<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Orçamento {{ $numero }} | {{ $empresa['nome'] }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --purple:       #7c3aed;
            --purple-light: #ede9fe;
            --purple-dark:  #5b21b6;
            --gold:         #f59e0b;
            --dark:         #1f2937;
            --muted:        #6b7280;
            --border:       #e5e7eb;
            --bg:           #f9fafb;
            --white:        #ffffff;
            --green:        #059669;
        }

        html, body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg);
            color: var(--dark);
            min-height: 100vh;
        }

        /* ── TELA: barra superior ── */
        .screen-bar {
            background: var(--dark);
            padding: .75rem 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .screen-bar .brand { font-weight: 900; color: var(--gold); font-size: 1rem; }
        .screen-bar .spacer { flex: 1; }
        .btn-print {
            background: var(--purple);
            color: #fff;
            border: none;
            border-radius: .5rem;
            padding: .55rem 1.4rem;
            font-family: inherit;
            font-weight: 700;
            font-size: .9rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: .5rem;
            transition: background .15s;
        }
        .btn-print:hover { background: var(--purple-dark); }
        .btn-back {
            color: #d1d5db;
            text-decoration: none;
            font-size: .85rem;
            font-weight: 500;
        }
        .btn-back:hover { color: #fff; }

        /* ── WRAPPER ── */
        .page-wrap {
            max-width: 820px;
            margin: 2rem auto 3rem;
            padding: 0 1.5rem;
        }

        /* ── DOCUMENTO ── */
        .doc {
            background: var(--white);
            border-radius: .75rem;
            box-shadow: 0 4px 24px rgba(0,0,0,.08);
            overflow: hidden;
        }

        /* Cabeçalho do documento */
        .doc-header {
            background: linear-gradient(135deg, var(--purple-dark) 0%, var(--purple) 100%);
            color: #fff;
            padding: 2rem 2.5rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
        }
        .doc-header-left .company-name {
            font-size: 1.6rem;
            font-weight: 900;
            letter-spacing: -.02em;
        }
        .doc-header-left .company-loja {
            font-size: .9rem;
            color: #c4b5fd;
            font-weight: 500;
            margin-top: .2rem;
        }
        .doc-header-left .company-info {
            margin-top: 1rem;
            font-size: .78rem;
            color: #ddd6fe;
            line-height: 1.7;
        }
        .doc-header-right { text-align: right; }
        .doc-header-right .orcamento-badge {
            display: inline-block;
            background: rgba(255,255,255,.15);
            border: 1.5px solid rgba(255,255,255,.35);
            border-radius: .5rem;
            padding: .4rem 1rem;
            font-size: .75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #ede9fe;
            margin-bottom: .75rem;
        }
        .doc-header-right .orcamento-num {
            font-size: 1.8rem;
            font-weight: 900;
            letter-spacing: .05em;
        }
        .doc-header-right .orcamento-num span {
            font-size: 1rem;
            font-weight: 500;
            color: #c4b5fd;
        }

        /* Faixa de datas */
        .doc-dates {
            background: var(--purple-light);
            padding: .85rem 2.5rem;
            display: flex;
            gap: 2.5rem;
        }
        .doc-dates .date-item { }
        .doc-dates .date-label {
            font-size: .7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--purple-dark);
        }
        .doc-dates .date-value {
            font-size: .95rem;
            font-weight: 700;
            color: var(--purple-dark);
            margin-top: .1rem;
        }

        /* Cliente */
        .doc-client {
            padding: 1.5rem 2.5rem;
            border-bottom: 1px solid var(--border);
        }
        .doc-client .section-label {
            font-size: .7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--muted);
            margin-bottom: .35rem;
        }
        .doc-client .client-name {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--dark);
        }

        /* Tabela de itens */
        .doc-items { padding: 1.5rem 2.5rem; }
        .doc-items .section-label {
            font-size: .7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--muted);
            margin-bottom: 1rem;
        }
        table { width: 100%; border-collapse: collapse; }
        thead th {
            padding: .65rem .75rem;
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: var(--muted);
            border-bottom: 2px solid var(--border);
        }
        thead th:first-child { text-align: left; }
        thead th:not(:first-child) { text-align: right; }
        tbody tr { border-bottom: 1px solid var(--border); }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:nth-child(even) { background: #fafafa; }
        tbody td {
            padding: .8rem .75rem;
            font-size: .88rem;
        }
        tbody td:first-child { font-weight: 600; }
        tbody td:not(:first-child) { text-align: right; color: var(--muted); }
        tbody td.subtotal-cell { color: var(--dark); font-weight: 700; }

        /* Totais */
        .doc-totals {
            padding: 1.5rem 2.5rem 2rem;
            display: flex;
            justify-content: flex-end;
        }
        .totals-box {
            min-width: 260px;
            background: var(--bg);
            border-radius: .5rem;
            padding: 1.25rem 1.5rem;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            font-size: .88rem;
            margin-bottom: .55rem;
            color: var(--muted);
        }
        .totals-row.total-final {
            margin-top: .75rem;
            padding-top: .75rem;
            border-top: 2px solid var(--border);
            font-size: 1.15rem;
            font-weight: 900;
            color: var(--dark);
        }
        .totals-row.total-final .total-value { color: var(--green); }
        .totals-row span:last-child { font-weight: 600; color: var(--dark); }

        /* Rodapé do documento */
        .doc-footer {
            background: #f3f4f6;
            border-top: 1px solid var(--border);
            padding: 1.25rem 2.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }
        .doc-footer .footer-note {
            font-size: .78rem;
            color: var(--muted);
        }
        .doc-footer .footer-note strong { color: var(--dark); }
        .doc-footer .validity-badge {
            background: #fef3c7;
            color: #92400e;
            border-radius: .4rem;
            padding: .35rem .9rem;
            font-size: .78rem;
            font-weight: 700;
            white-space: nowrap;
        }

        /* ── IMPRESSÃO ── */
        @media print {
            html, body { background: #fff; }
            .screen-bar { display: none !important; }
            .page-wrap { margin: 0; padding: 0; max-width: 100%; }
            .doc {
                box-shadow: none;
                border-radius: 0;
            }
            .btn-print { display: none !important; }
        }

        @page {
            size: A4;
            margin: 10mm 12mm;
        }
    </style>
</head>
<body>

<!-- Barra de ações (some ao imprimir) -->
<div class="screen-bar">
    <span class="brand">{{ $empresa['nome'] }}</span>
    <span class="spacer"></span>
    <a href="{{ route('welcome') }}" class="btn-back">← Nova Venda</a>
    <button class="btn-print" onclick="window.print()">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
            <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/>
        </svg>
        Imprimir / Salvar PDF
    </button>
</div>

<div class="page-wrap">
    <div class="doc">

        {{-- Cabeçalho --}}
        <div class="doc-header">
            <div class="doc-header-left">
                <div class="company-name">{{ $empresa['nome'] }}</div>
                @if ($loja_nome)
                    <div class="company-loja">{{ $loja_nome }}</div>
                @endif
                <div class="company-info">
                    @if ($empresa['cnpj'])
                        CNPJ: {{ $empresa['cnpj'] }}<br>
                    @endif
                    @if ($empresa['logradouro'])
                        {{ $empresa['logradouro'] }}{{ $empresa['numero'] ? ', ' . $empresa['numero'] : '' }}
                        @if ($empresa['bairro']) – {{ $empresa['bairro'] }}@endif
                        @if ($empresa['cidade']) – {{ $empresa['cidade'] }}/{{ $empresa['uf'] }}@endif
                        <br>
                    @endif
                    @if ($empresa['telefone'])
                        Tel: {{ $empresa['telefone'] }}
                        @if ($empresa['email'])  |  @endif
                    @endif
                    @if ($empresa['email'])
                        {{ $empresa['email'] }}
                    @endif
                </div>
            </div>
            <div class="doc-header-right">
                <div class="orcamento-badge">Orçamento</div>
                <div class="orcamento-num">
                    <span>#</span>{{ $numero }}
                </div>
            </div>
        </div>

        {{-- Datas --}}
        <div class="doc-dates">
            <div class="date-item">
                <div class="date-label">Data de Emissão</div>
                <div class="date-value">{{ $data_emissao }}</div>
            </div>
            <div class="date-item">
                <div class="date-label">Válido até</div>
                <div class="date-value">{{ $validade }}</div>
            </div>
        </div>

        {{-- Cliente --}}
        @if ($nome_cliente)
        <div class="doc-client">
            <div class="section-label">Cliente</div>
            <div class="client-name">{{ $nome_cliente }}</div>
        </div>
        @endif

        {{-- Itens --}}
        <div class="doc-items">
            <div class="section-label">Itens do Orçamento</div>
            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Qtd.</th>
                        <th>Preço Unit.</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($itens as $item)
                    <tr>
                        <td>{{ $item['nome'] }}</td>
                        <td style="text-align:right; color: var(--dark);">{{ $item['quantidade'] }}</td>
                        <td>R$ {{ number_format($item['preco_unitario'], 2, ',', '.') }}</td>
                        <td class="subtotal-cell">R$ {{ number_format($item['subtotal'], 2, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Totais --}}
        <div class="doc-totals">
            <div class="totals-box">
                <div class="totals-row">
                    <span>Subtotal</span>
                    <span>R$ {{ number_format($subtotal, 2, ',', '.') }}</span>
                </div>
                @if ($desconto > 0)
                <div class="totals-row">
                    <span>Desconto</span>
                    <span style="color: #ef4444;">- R$ {{ number_format($desconto, 2, ',', '.') }}</span>
                </div>
                @endif
                <div class="totals-row total-final">
                    <span>Total</span>
                    <span class="total-value">R$ {{ number_format($total, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Rodapé --}}
        <div class="doc-footer">
            <p class="footer-note">
                Este documento é um <strong>orçamento</strong> e não representa uma venda confirmada.<br>
                Os preços são válidos conforme a data de validade indicada.
            </p>
            <span class="validity-badge">Válido até {{ $validade }}</span>
        </div>

    </div>
</div>

<script>
    // Abre o diálogo de impressão automaticamente ao carregar a página
    window.addEventListener('load', function () {
        setTimeout(function () { window.print(); }, 600);
    });
</script>

</body>
</html>
