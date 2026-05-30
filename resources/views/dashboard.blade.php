<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | Baterias ERP</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg: #f3f4f6;
            --white: #ffffff;
            --dark: #1f2937;
            --navy: #081c33;
            --navy-2: #10284b;
            --gold: #f59e0b;
            --green: #10b981;
            --red: #ef4444;
            --border: #e5e7eb;
            --muted: #6b7280;
        }
        html, body { height: 100%; font-family: 'Inter', system-ui, sans-serif; background: var(--bg); color: var(--dark); }

        /* CONTAINER */
        .wrapper { max-width: 1200px; margin: 0 auto; padding: 2rem 1rem; }
        .filters {
            background: var(--white);
            border-radius: .8rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,.08);
            display: flex;
            flex-wrap: wrap;
            gap: .75rem;
            align-items: end;
        }
        .filter-group { display: flex; flex-direction: column; gap: .35rem; }
        .filter-label { font-size: .75rem; color: var(--muted); font-weight: 700; text-transform: uppercase; letter-spacing: .04em; }
        .filter-input {
            border: 1px solid var(--border);
            border-radius: .55rem;
            padding: .55rem .65rem;
            font-family: inherit;
            color: var(--dark);
            background: #fff;
        }
        .filter-actions { display: flex; gap: .5rem; margin-left: auto; }
        .btn-filter {
            border: none;
            border-radius: .55rem;
            padding: .58rem .9rem;
            font-family: inherit;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .btn-filter-primary { background: var(--dark); color: #fff; }
        .btn-filter-light { background: #e5e7eb; color: #111827; }

        /* CARDS */
        .cards { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .card {
            background: var(--white);
            border-radius: .8rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,.08);
            border-left: 4px solid var(--gold);
        }
        .card.green { border-left-color: var(--green); }
        .card.red { border-left-color: var(--red); }
        .card-label { font-size: .8rem; color: var(--muted); text-transform: uppercase; letter-spacing: .05em; margin-bottom: .5rem; }
        .card-value { font-size: 2rem; font-weight: 900; color: var(--dark); font-variant-numeric: tabular-nums; }
        .card-sub { font-size: .85rem; color: var(--muted); margin-top: .5rem; }

        /* SECTIONS */
        .section { background: var(--white); border-radius: .8rem; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        .section h2 { font-size: 1.2rem; margin-bottom: 1rem; padding-bottom: .75rem; border-bottom: 1px solid var(--border); }
        .chart-wrap { position: relative; height: 320px; }
        .chart-empty { color: var(--muted); font-size: .95rem; }

        /* TABLE */
        table { width: 100%; border-collapse: collapse; font-size: .9rem; }
        thead th { background: var(--bg); padding: .75rem; text-align: left; font-weight: 700; color: var(--muted); text-transform: uppercase; font-size: .75rem; letter-spacing: .05em; border-bottom: 2px solid var(--border); }
        tbody td { padding: .75rem; border-bottom: 1px solid var(--border); }
        tbody tr:hover { background: var(--bg); }
        .td-name { font-weight: 600; color: var(--dark); }
        .td-qty { text-align: center; }
        .td-price { font-weight: 700; color: var(--gold); }
        .td-total { font-weight: 700; color: var(--green); }
        .td-time { color: var(--muted); font-size: .85rem; }

        @media (max-width: 768px) {
            .cards { grid-template-columns: 1fr; }
            table { font-size: .85rem; }
            thead th, tbody td { padding: .5rem; }
        }
    </style>
</head>
<body>

<x-topbar title="Dashboard" active="painel" />

<div class="wrapper">
    @php
        $graficoVendas = $vendasPeriodo
            ->groupBy(fn ($venda) => \Illuminate\Support\Carbon::parse($venda->created_at)->format('H:00'))
            ->map(fn ($grupo) => round((float) $grupo->sum('total'), 2))
            ->sortKeys();

        $labelsGrafico = $graficoVendas->keys()->values();
        $valoresGrafico = $graficoVendas->values();
    @endphp

    <form method="GET" action="{{ route('painel.index') }}" class="filters">
        <div class="filter-group">
            <label for="data_inicio" class="filter-label">Data inicial</label>
            <input id="data_inicio" name="data_inicio" type="date" value="{{ $filtros['data_inicio'] }}" class="filter-input">
        </div>
        <div class="filter-group">
            <label for="data_fim" class="filter-label">Data final</label>
            <input id="data_fim" name="data_fim" type="date" value="{{ $filtros['data_fim'] }}" class="filter-input">
        </div>
        <div class="filter-actions">
            <button type="submit" class="btn-filter btn-filter-primary">Filtrar</button>
            <a href="{{ route('painel.index') }}" class="btn-filter btn-filter-light">Hoje</a>
        </div>
    </form>

    <!-- KPIs -->
    <div class="cards">
        <div class="card">
            <div class="card-label">Vendas no Período</div>
            <div class="card-value">{{ $resumoPeriodo['pedidos'] }}</div>
            <div class="card-sub">Número de transações</div>
        </div>
        <div class="card green">
            <div class="card-label">Faturamento</div>
            <div class="card-value">R$ {{ number_format($resumoPeriodo['faturamento'], 2, ',', '.') }}</div>
            <div class="card-sub">Total filtrado</div>
        </div>
        <div class="card">
            <div class="card-label">Itens Vendidos</div>
            <div class="card-value">{{ $resumoPeriodo['itens'] }}</div>
            <div class="card-sub">Unidades no período</div>
        </div>
        <div class="card green">
            <div class="card-label">Lucro do Dia</div>
            <div class="card-value">R$ {{ number_format($resumoPeriodo['lucro'], 2, ',', '.') }}</div>
            <div class="card-sub">Vendas - custo dos produtos</div>
        </div>
    </div>

    <div class="section">
        <h2>Gráfico de Vendas (Período)</h2>
        @if ($labelsGrafico->isNotEmpty())
            <div class="chart-wrap">
                <canvas id="graficoVendasHoje"></canvas>
            </div>
        @else
            <p class="chart-empty">Sem vendas hoje para exibir no gráfico.</p>
        @endif
    </div>

    <div class="section">
        <h2>Estoque Calculado</h2>
        @php
            $totalQtdEstoque = 0;
            $totalValorCusto = 0.0;
            $totalValorVenda = 0.0;
            $totalLucroPotencial = 0.0;
        @endphp
        <table>
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Qtd. Estoque</th>
                    <th>Custo Un.</th>
                    <th>Venda Un.</th>
                    <th>Valor de Custo</th>
                    <th>Valor Final Venda</th>
                    <th>Lucro Potencial</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($produtosEstoque as $produto)
                    @php
                        $qtd = (int) $produto->quantidade;
                        $custoUn = (float) $produto->preco_custo;
                        $vendaUn = (float) $produto->preco_unitario;
                        $valorCusto = $qtd * $custoUn;
                        $valorVenda = $qtd * $vendaUn;
                        $lucroPotencial = $valorVenda - $valorCusto;

                        $totalQtdEstoque += $qtd;
                        $totalValorCusto += $valorCusto;
                        $totalValorVenda += $valorVenda;
                        $totalLucroPotencial += $lucroPotencial;
                    @endphp
                    <tr>
                        <td class="td-name">{{ $produto->nome }}</td>
                        <td class="td-qty">{{ $qtd }}</td>
                        <td>R$ {{ number_format($custoUn, 2, ',', '.') }}</td>
                        <td>R$ {{ number_format($vendaUn, 2, ',', '.') }}</td>
                        <td>R$ {{ number_format($valorCusto, 2, ',', '.') }}</td>
                        <td class="td-total">R$ {{ number_format($valorVenda, 2, ',', '.') }}</td>
                        <td>R$ {{ number_format($lucroPotencial, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="td-time">Sem produtos cadastrados no estoque.</td>
                    </tr>
                @endforelse
                @if ($produtosEstoque->isNotEmpty())
                    <tr>
                        <td class="td-name">Total</td>
                        <td class="td-qty"><strong>{{ $totalQtdEstoque }}</strong></td>
                        <td>—</td>
                        <td>—</td>
                        <td><strong>R$ {{ number_format($totalValorCusto, 2, ',', '.') }}</strong></td>
                        <td class="td-total"><strong>R$ {{ number_format($totalValorVenda, 2, ',', '.') }}</strong></td>
                        <td><strong>R$ {{ number_format($totalLucroPotencial, 2, ',', '.') }}</strong></td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- VENDAS NO PERIODO -->
    @if ($vendasPeriodo->isNotEmpty())
    <div class="section">
        <h2>Vendas no Período</h2>
        <table>
            <thead>
                <tr>
                    <th>Hora</th>
                    <th>Produto</th>
                    <th>Qtd.</th>
                    <th>Desconto</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($vendasPeriodo as $v)
                <tr>
                    <td class="td-time">{{ \Illuminate\Support\Carbon::parse($v->created_at)->format('H:i') }}</td>
                    <td class="td-name">{{ $v->produto->nome }}</td>
                    <td class="td-qty">{{ $v->quantidade }}</td>
                    <td>{{ $v->desconto > 0 ? '-R$ '.number_format($v->desconto,2,',','.') : '—' }}</td>
                    <td class="td-total">R$ {{ number_format($v->total, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- ÚLTIMAS VENDAS -->
    @if ($ultimasVendas->isNotEmpty())
    <div class="section">
        <h2>Últimas Vendas ({{ $ultimasVendas->count() }})</h2>
        <table>
            <thead>
                <tr>
                    <th>Data/Hora</th>
                    <th>Produto</th>
                    <th>Qtd.</th>
                    <th>Desconto</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ultimasVendas as $v)
                <tr>
                    <td class="td-time">{{ \Illuminate\Support\Carbon::parse($v->created_at)->format('d/m/Y H:i') }}</td>
                    <td class="td-name">{{ $v->produto->nome }}</td>
                    <td class="td-qty">{{ $v->quantidade }}</td>
                    <td>{{ $v->desconto > 0 ? '-R$ '.number_format($v->desconto,2,',','.') : '—' }}</td>
                    <td class="td-total">R$ {{ number_format($v->total, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(() => {
    const canvas = document.getElementById('graficoVendasHoje');
    if (!canvas) {
        return;
    }

    const labels = @json($labelsGrafico);
    const valores = @json($valoresGrafico);

    new Chart(canvas, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Faturamento por hora (R$)',
                data: valores,
                borderColor: '#f59e0b',
                backgroundColor: 'rgba(245, 158, 11, 0.2)',
                borderWidth: 2,
                fill: true,
                tension: 0.35,
                pointRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: true }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: (value) => `R$ ${Number(value).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`
                    }
                }
            }
        }
    });
})();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
