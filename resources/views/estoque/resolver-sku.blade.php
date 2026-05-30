<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resolver SKU | Baterias ERP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --bp-navy: #081c33; --bp-navy-2: #10284b; --bp-gold: #f2c300;
            --bp-bg: #f5f7fb; --bp-text: #0f172a; --bp-muted: #64748b;
            --bp-teal: #0d9488; --bp-amber: #f59e0b;
        }
        body {
            background: radial-gradient(circle at top right, rgba(242,195,0,.10), transparent 28%),
                        radial-gradient(circle at left center, rgba(13,148,136,.07), transparent 30%),
                        linear-gradient(180deg, #fbfcfe 0%, var(--bp-bg) 100%);
            color: var(--bp-text);
        }
        .bp-shell { max-width: 960px; }
        .bp-label { color: #374151; font-weight: 600; font-size: .85rem; margin-bottom: .3rem; }
        .bp-control { border-radius: .75rem; border: 1.5px solid #e2e8f0; padding: .6rem 1rem; background: #fafbfc; transition: border-color .15s, box-shadow .15s; font-size: .93rem; }
        .bp-control:focus { border-color: var(--bp-teal); box-shadow: 0 0 0 3px rgba(13,148,136,.15); background: #fff; outline: none; }
        .bp-control-sm { font-size: .86rem; padding: .5rem .85rem; }
        .bp-card { border: none; box-shadow: 0 4px 6px -1px rgba(0,0,0,.07), 0 10px 28px rgba(15,23,42,.06); border-radius: 1.25rem; overflow: hidden; }
        .bp-card-nfe { background: linear-gradient(135deg, var(--bp-navy) 0%, #1a3a6b 100%); color: #fff; }
        .item-card { border: none; border-radius: 1.25rem; overflow: hidden; box-shadow: 0 2px 8px rgba(15,23,42,.08); transition: box-shadow .18s; }
        .item-card:hover { box-shadow: 0 6px 20px rgba(15,23,42,.12); }
        .item-card-header { background: linear-gradient(90deg, rgba(8,28,51,.05), rgba(13,148,136,.06)); border-bottom: 1.5px solid rgba(13,148,136,.12); padding: 1rem 1.4rem; }
        .sku-badge { background: rgba(8,28,51,.09); color: var(--bp-navy); font-family: monospace; font-size: .82rem; font-weight: 700; border-radius: .5rem; padding: .2rem .55rem; border: 1px solid rgba(8,28,51,.12); }
        .option-panel { border-radius: .9rem; border: 1.5px solid #e2e8f0; padding: 1rem 1.1rem; transition: border-color .2s, background .2s; }
        .option-panel.active-existente { border-color: rgba(13,148,136,.5); background: rgba(13,148,136,.04); }
        .option-panel.active-novo { border-color: rgba(245,158,11,.5); background: rgba(245,158,11,.04); }
        .option-panel.active-ignorar { border-color: rgba(100,116,139,.35); background: rgba(100,116,139,.04); }
        .option-label { font-weight: 700; font-size: .9rem; color: var(--bp-navy); cursor: pointer; display: flex; align-items: center; gap: .5rem; }
        .form-check-input:checked { background-color: var(--bp-teal); border-color: var(--bp-teal); }
        .form-check-input[data-tipo="novo"]:checked { background-color: var(--bp-amber); border-color: var(--bp-amber); }
        .form-check-input[data-tipo="ignorar"]:checked { background-color: #94a3b8; border-color: #94a3b8; }
        .xml-info-row { display: flex; gap: 1.2rem; flex-wrap: wrap; }
        .xml-info-chip { background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.18); border-radius: .6rem; padding: .28rem .7rem; font-size: .8rem; }
        .bp-btn-confirm { background: linear-gradient(135deg, var(--bp-teal) 0%, #0f766e 100%); color: #fff; border: none; font-weight: 800; border-radius: .9rem; box-shadow: 0 4px 14px rgba(13,148,136,.3); letter-spacing: .02em; transition: filter .15s, transform .12s; }
        .bp-btn-confirm:hover { filter: brightness(1.08); color: #fff; transform: translateY(-1px); }
        .bp-btn-cancel { background: #fff; color: var(--bp-navy); border: 1.5px solid #e2e8f0; font-weight: 700; border-radius: .9rem; transition: background .15s; }
        .bp-btn-cancel:hover { background: #f8fafc; color: var(--bp-navy); }
        .progress-badge { background: rgba(245,158,11,.15); color: #92400e; border: 1px solid rgba(245,158,11,.25); font-size: .8rem; font-weight: 700; border-radius: .6rem; padding: .25rem .75rem; }
        .hint-text { font-size: .78rem; color: var(--bp-muted); margin-top: .25rem; }
    </style>
</head>
<body>
<x-topbar title="Resolver SKU" active="estoque" />

<div class="container-fluid px-3 px-md-4 py-3 py-md-4 bp-shell mx-auto">

    @if ($errors->any())
        <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-3">
            <div class="fw-bold mb-1">Corrija os erros abaixo:</div>
            <ul class="mb-0 ps-3">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    {{-- Header: NF-e info --}}
    <div class="bp-card bp-card-nfe mb-4 p-4">
        <div class="d-flex align-items-start gap-3">
            <div style="width:52px;height:52px;flex-shrink:0;border-radius:1rem;background:rgba(255,255,255,.12);display:flex;align-items:center;justify-content:center;font-size:1.5rem;">
                <i class="bi bi-filetype-xml"></i>
            </div>
            <div class="flex-grow-1">
                <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.08em;opacity:.6;font-weight:700;">
                    NF-e importada — revisão necessária
                </div>
                <div class="fw-bold mt-1" style="font-size:1.15rem;">
                    NF nº {{ $pendente['nfe']['numero'] }} · Série {{ $pendente['nfe']['serie'] }}
                </div>
                <div class="xml-info-row mt-2">
                    <div class="xml-info-chip">
                        <i class="bi bi-building me-1 opacity-70"></i>
                        {{ $pendente['fornecedor_nome'] ?: 'Fornecedor desconhecido' }}
                    </div>
                    @if($pendente['fornecedor_cnpj'])
                        <div class="xml-info-chip" style="font-family:monospace;">
                            {{ $pendente['fornecedor_cnpj'] }}
                        </div>
                    @endif
                    @if($pendente['fornecedor_novo'])
                        <div class="xml-info-chip" style="background:rgba(74,222,128,.18);color:#4ade80;border-color:rgba(74,222,128,.25);">
                            <i class="bi bi-check-circle-fill me-1"></i> Fornecedor cadastrado automaticamente
                        </div>
                    @endif
                </div>
            </div>
            <div>
                <span class="progress-badge">
                    <i class="bi bi-exclamation-circle me-1"></i>
                    {{ count($naoMapeados) }} SKU{{ count($naoMapeados) > 1 ? 's' : '' }} sem mapeamento
                </span>
            </div>
        </div>
    </div>

    {{-- Instruction --}}
    <div class="d-flex align-items-center gap-2 mb-3">
        <i class="bi bi-info-circle-fill text-primary opacity-75"></i>
        <span style="font-size:.88rem;color:var(--bp-muted);">
            Para cada item abaixo, <strong>vincule a um produto existente</strong>, <strong>crie um novo produto</strong> ou <strong>ignore</strong> o item nesta importação.
        </span>
    </div>

    {{-- Resolution form --}}
    <form method="POST" action="{{ route('estoque.confirmar-resolucao') }}" id="formResolver">
        @csrf

        @foreach($naoMapeados as $i => $item)
        <div class="card item-card mb-3" id="card-item-{{ $i }}">

            {{-- Item header --}}
            <div class="item-card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:38px;height:38px;border-radius:.75rem;background:rgba(13,148,136,.1);color:var(--bp-teal);display:flex;align-items:center;justify-content:center;font-size:1.1rem;">
                        <i class="bi bi-box"></i>
                    </div>
                    <div>
                        <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.06em;color:var(--bp-muted);font-weight:700;">
                            Item {{ $i + 1 }} de {{ count($naoMapeados) }}
                        </div>
                        <div class="fw-bold" style="color:var(--bp-navy);">{{ $item['nome_xml'] }}</div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="sku-badge">{{ $item['sku_fornecedor'] }}</span>
                    <span class="badge rounded-pill px-3" style="background:rgba(8,28,51,.07);color:var(--bp-navy);font-size:.8rem;">
                        {{ number_format($item['quantidade'], 0, ',', '.') }} un
                    </span>
                    <span class="badge rounded-pill px-3" style="background:rgba(34,197,94,.1);color:#166534;font-size:.8rem;">
                        R$ {{ number_format($item['custo_unitario'], 2, ',', '.') }}/un
                    </span>
                </div>
            </div>

            <input type="hidden" name="resolucao[{{ $i }}][sku_fornecedor]" value="{{ $item['sku_fornecedor'] }}">

            <div class="card-body p-4">
                <div class="row g-3">

                    {{-- Opção 1: Produto existente --}}
                    <div class="col-12">
                        <div class="option-panel active-existente" id="panel-existente-{{ $i }}">
                            <div class="d-flex align-items-center gap-2 mb-0" id="header-existente-{{ $i }}">
                                <input
                                    class="form-check-input mt-0 js-tipo-radio"
                                    type="radio"
                                    name="resolucao[{{ $i }}][tipo]"
                                    value="existente"
                                    id="tipo_existente_{{ $i }}"
                                    data-idx="{{ $i }}"
                                    data-tipo="existente"
                                    checked
                                >
                                <label class="option-label" for="tipo_existente_{{ $i }}">
                                    <i class="bi bi-link-45deg text-success"></i>
                                    Vincular a produto existente
                                </label>
                            </div>
                            <div class="mt-3 js-body-existente" id="body-existente-{{ $i }}">
                                <label class="bp-label" for="select_produto_{{ $i }}">
                                    <i class="bi bi-search me-1 text-primary opacity-75"></i>Produto no sistema
                                </label>
                                <select name="resolucao[{{ $i }}][produto_id]" id="select_produto_{{ $i }}" class="form-select bp-control bp-control-sm">
                                    <option value="">Selecione o produto...</option>
                                    @foreach($produtos as $p)
                                        <option value="{{ $p->id }}" @selected(old("resolucao.{$i}.produto_id") == $p->id)>
                                            {{ $p->nome }}{{ $p->sku ? ' — '.$p->sku : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                @if($produtos->isEmpty())
                                    <div class="hint-text text-warning mt-1">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        Nenhum produto cadastrado ainda. Use a opção "Criar novo produto".
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Opção 2: Novo produto --}}
                    <div class="col-12">
                        <div class="option-panel" id="panel-novo-{{ $i }}">
                            <div class="d-flex align-items-center gap-2 mb-0">
                                <input
                                    class="form-check-input mt-0 js-tipo-radio"
                                    type="radio"
                                    name="resolucao[{{ $i }}][tipo]"
                                    value="novo"
                                    id="tipo_novo_{{ $i }}"
                                    data-idx="{{ $i }}"
                                    data-tipo="novo"
                                >
                                <label class="option-label" for="tipo_novo_{{ $i }}">
                                    <i class="bi bi-plus-circle text-warning"></i>
                                    Criar novo produto
                                </label>
                            </div>
                            <div class="mt-3 d-none js-body-novo" id="body-novo-{{ $i }}">
                                <div class="row g-2">
                                    <div class="col-12 col-md-6">
                                        <label class="bp-label"><i class="bi bi-tag me-1 opacity-75"></i>Nome do produto</label>
                                        <input
                                            name="resolucao[{{ $i }}][nome]"
                                            type="text"
                                            value="{{ old("resolucao.{$i}.nome", $item['nome_sugerido']) }}"
                                            placeholder="Nome do produto"
                                            class="form-control bp-control bp-control-sm"
                                            maxlength="255"
                                        >
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="bp-label"><i class="bi bi-upc me-1 opacity-75"></i>SKU interno</label>
                                        <input
                                            name="resolucao[{{ $i }}][sku]"
                                            type="text"
                                            value="{{ old("resolucao.{$i}.sku", $item['sku_sugerido']) }}"
                                            placeholder="Ex: BAT-MOURA60"
                                            class="form-control bp-control bp-control-sm"
                                            style="font-family:monospace;"
                                            maxlength="100"
                                        >
                                        <div class="hint-text"><i class="bi bi-magic me-1"></i>Sugestão baseada no SKU do fornecedor</div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <label class="bp-label"><i class="bi bi-arrow-down-circle me-1 opacity-75 text-danger"></i>Preço de custo</label>
                                        <div class="input-group" style="border-radius:.75rem;overflow:hidden;">
                                            <span class="input-group-text" style="background:#f1f5f9;border:1.5px solid #e2e8f0;border-right:none;font-size:.82rem;font-weight:700;color:var(--bp-muted);">R$</span>
                                            <input
                                                name="resolucao[{{ $i }}][preco_custo]"
                                                type="number" step="0.01" min="0"
                                                value="{{ old("resolucao.{$i}.preco_custo", number_format($item['custo_unitario'], 2, '.', '')) }}"
                                                class="form-control bp-control bp-control-sm"
                                                style="border-radius:0 .75rem .75rem 0;"
                                            >
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <label class="bp-label"><i class="bi bi-arrow-up-circle me-1 opacity-75 text-success"></i>Preço de venda</label>
                                        <div class="input-group" style="border-radius:.75rem;overflow:hidden;">
                                            <span class="input-group-text" style="background:#f1f5f9;border:1.5px solid #e2e8f0;border-right:none;font-size:.82rem;font-weight:700;color:var(--bp-muted);">R$</span>
                                            <input
                                                name="resolucao[{{ $i }}][preco_unitario]"
                                                type="number" step="0.01" min="0"
                                                value="{{ old("resolucao.{$i}.preco_unitario") }}"
                                                placeholder="0,00"
                                                class="form-control bp-control bp-control-sm"
                                                style="border-radius:0 .75rem .75rem 0;"
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Opção 3: Ignorar --}}
                    <div class="col-12">
                        <div class="option-panel" id="panel-ignorar-{{ $i }}" style="padding:.75rem 1.1rem;">
                            <div class="d-flex align-items-center gap-2">
                                <input
                                    class="form-check-input mt-0 js-tipo-radio"
                                    type="radio"
                                    name="resolucao[{{ $i }}][tipo]"
                                    value="ignorar"
                                    id="tipo_ignorar_{{ $i }}"
                                    data-idx="{{ $i }}"
                                    data-tipo="ignorar"
                                >
                                <label class="option-label" style="color:var(--bp-muted);" for="tipo_ignorar_{{ $i }}">
                                    <i class="bi bi-x-circle opacity-60"></i>
                                    Ignorar este item nesta importação
                                </label>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Salvar mapeamento --}}
                <div class="mt-3 pt-3 border-top" style="border-color:#f1f5f9 !important;" id="row-salvar-{{ $i }}">
                    <div class="d-flex align-items-center gap-2">
                        <input type="hidden" name="resolucao[{{ $i }}][salvar_mapeamento]" value="0">
                        <input
                            type="checkbox"
                            class="form-check-input mt-0"
                            name="resolucao[{{ $i }}][salvar_mapeamento]"
                            value="1"
                            id="salvar_{{ $i }}"
                            checked
                        >
                        <label for="salvar_{{ $i }}" style="font-size:.83rem;color:var(--bp-muted);cursor:pointer;">
                            <i class="bi bi-bookmark-check me-1"></i>
                            Salvar este mapeamento SKU para futuras importações
                        </label>
                    </div>
                </div>

            </div>
        </div>
        @endforeach

        {{-- Already-mapped items summary --}}
        @php $jaMapeados = array_values(array_filter($pendente['itens'], fn($i) => $i['produto_id'] !== null)); @endphp
        @if(count($jaMapeados) > 0)
        <div class="card border-0 rounded-4 shadow-sm mb-4" style="background:rgba(34,197,94,.05);border:1.5px solid rgba(34,197,94,.15) !important;">
            <div class="card-body px-4 py-3">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-check-circle-fill text-success"></i>
                    <span class="fw-bold" style="font-size:.88rem;color:#166534;">
                        {{ count($jaMapeados) }} item(ns) já mapeado(s) — serão importados automaticamente
                    </span>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($jaMapeados as $jm)
                        <span class="badge rounded-pill px-3" style="background:rgba(34,197,94,.12);color:#166534;font-family:monospace;font-size:.8rem;">
                            {{ $jm['sku_fornecedor'] }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- Actions --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 pb-4">
            <a href="{{ route('estoque.index') }}"
               class="btn bp-btn-cancel px-4 py-2"
               onclick="return confirm('Cancelar esta importação? Os itens NÃO serão importados.')">
                <i class="bi bi-x-lg me-1"></i> Cancelar importação
            </a>
            <button type="submit" class="btn bp-btn-confirm px-5 py-2 d-flex align-items-center gap-2" style="font-size:1rem;">
                <i class="bi bi-cloud-upload-fill fs-5"></i>
                Confirmar e importar
            </button>
        </div>

    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
(() => {
    const TIPOS = ['existente', 'novo', 'ignorar'];

    function applyTipo(idx, tipo) {
        TIPOS.forEach(t => {
            const panel = document.getElementById(`panel-${t}-${idx}`);
            const body  = document.getElementById(`body-${t}-${idx}`);
            if (!panel) return;
            panel.classList.remove('active-existente', 'active-novo', 'active-ignorar');
            if (t === tipo) panel.classList.add(`active-${tipo}`);
            if (body) body.classList.toggle('d-none', t !== tipo);
        });

        // Hide "salvar mapeamento" when ignorar
        const rowSalvar = document.getElementById(`row-salvar-${idx}`);
        if (rowSalvar) rowSalvar.classList.toggle('d-none', tipo === 'ignorar');
    }

    document.querySelectorAll('.js-tipo-radio').forEach(radio => {
        radio.addEventListener('change', function () {
            if (this.checked) applyTipo(this.dataset.idx, this.value);
        });
    });

    // Init: apply state for checked radios (in case of back button / old())
    document.querySelectorAll('.js-tipo-radio:checked').forEach(r => {
        applyTipo(r.dataset.idx, r.value);
    });

    // Client-side validation
    document.getElementById('formResolver').addEventListener('submit', function (e) {
        let ok = true;

        document.querySelectorAll('.js-tipo-radio:checked').forEach(radio => {
            const idx  = radio.dataset.idx;
            const tipo = radio.value;

            if (tipo === 'existente') {
                const sel = document.querySelector(`[name="resolucao[${idx}][produto_id]"]`);
                if (sel && !sel.value) {
                    sel.classList.add('is-invalid');
                    ok = false;
                }
            } else if (tipo === 'novo') {
                const nome  = document.querySelector(`[name="resolucao[${idx}][nome]"]`);
                const preco = document.querySelector(`[name="resolucao[${idx}][preco_unitario]"]`);
                if (nome && !nome.value.trim()) { nome.classList.add('is-invalid'); ok = false; }
                if (preco && (!preco.value || parseFloat(preco.value) <= 0)) { preco.classList.add('is-invalid'); ok = false; }
            }
        });

        if (!ok) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
            const el = document.createElement('div');
            el.className = 'alert alert-danger border-0 rounded-4 shadow-sm mb-3';
            el.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-2"></i>Preencha os campos obrigatórios destacados em vermelho.';
            document.querySelector('.bp-shell').prepend(el);
        }
    });

    // Remove is-invalid on change
    document.querySelectorAll('select, input').forEach(el => {
        el.addEventListener('change', () => el.classList.remove('is-invalid'));
        el.addEventListener('input',  () => el.classList.remove('is-invalid'));
    });
})();
</script>
</body>
</html>
