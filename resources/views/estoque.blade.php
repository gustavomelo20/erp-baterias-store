<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Estoque | Baterias ERP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --bp-navy: #081c33;
            --bp-navy-2: #10284b;
            --bp-gold: #f2c300;
            --bp-gold-dark: #c89500;
            --bp-orange: #ff7a00;
            --bp-red: #c0392b;
            --bp-bg: #f5f7fb;
            --bp-text: #0f172a;
            --bp-muted: #64748b;
        }

        body {
            background:
                radial-gradient(circle at top right, rgba(242, 195, 0, 0.12), transparent 28%),
                radial-gradient(circle at left center, rgba(255, 122, 0, 0.08), transparent 30%),
                linear-gradient(180deg, #fbfcfe 0%, var(--bp-bg) 100%);
            color: var(--bp-text);
        }

        .bp-shell { max-width: 1280px; }
        .bp-card { border: none; box-shadow: 0 4px 6px -1px rgba(0,0,0,.07), 0 10px 28px rgba(15,23,42,.06); border-radius: 1.35rem; overflow: hidden; transition: transform .18s ease, box-shadow .18s ease; }
        .bp-card:hover { transform: translateY(-2px); box-shadow: 0 8px 16px -2px rgba(0,0,0,.10), 0 20px 40px rgba(15,23,42,.09); }
        .bp-card-header-cadastro { background: linear-gradient(135deg, var(--bp-navy) 0%, #173b6d 100%); color: #fff; border-bottom: none; padding: 1.4rem 1.6rem; }
        .bp-card-header-reposicao { background: linear-gradient(135deg, #1a4731 0%, #166534 100%); color: #fff; border-bottom: none; padding: 1.4rem 1.6rem; }
        .bp-card-header-tabela { background: linear-gradient(90deg, rgba(8,28,51,.04), rgba(242,195,0,.08)); border-bottom: 1px solid rgba(8,28,51,.07); }
        .bp-card-icon { width: 44px; height: 44px; border-radius: .85rem; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0; }
        .bp-card-icon-cadastro { background: rgba(242,195,0,.18); color: var(--bp-gold); }
        .bp-card-icon-reposicao { background: rgba(74,222,128,.18); color: #4ade80; }
        .bp-label { color: #374151; font-weight: 600; font-size: .85rem; margin-bottom: .3rem; letter-spacing: .01em; }
        .bp-control { border-radius: .75rem; border: 1.5px solid #e2e8f0; padding: .65rem 1rem; background: #fafbfc; transition: border-color .15s, box-shadow .15s, background .15s; font-size: .93rem; }
        .bp-control:focus { border-color: var(--bp-gold); box-shadow: 0 0 0 3px rgba(242,195,0,.15); background: #fff; outline: none; }
        .bp-input-group .bp-prefix { background: #f1f5f9; border: 1.5px solid #e2e8f0; border-right: none; border-radius: .75rem 0 0 .75rem; padding: .65rem .9rem; color: var(--bp-muted); font-weight: 700; font-size: .85rem; }
        .bp-input-group .bp-control { border-radius: 0 .75rem .75rem 0; }
        .bp-input-group .bp-control:focus ~ .bp-prefix, .bp-input-group .bp-prefix:has(+ .bp-control:focus) { border-color: var(--bp-gold); }
        .bp-btn-primary { background: linear-gradient(135deg, var(--bp-gold) 0%, #f59e0b 100%); color: var(--bp-navy); border: none; font-weight: 800; border-radius: .85rem; box-shadow: 0 4px 14px rgba(242,195,0,.35); letter-spacing: .02em; transition: filter .15s, box-shadow .15s, transform .12s; }
        .bp-btn-primary:hover { filter: brightness(1.06); box-shadow: 0 6px 20px rgba(242,195,0,.45); transform: translateY(-1px); color: var(--bp-navy); }
        .bp-btn-primary:active { transform: translateY(0); }
        .bp-btn-green { background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); color: #fff; border: none; font-weight: 800; border-radius: .85rem; box-shadow: 0 4px 14px rgba(34,197,94,.3); letter-spacing: .02em; transition: filter .15s, box-shadow .15s, transform .12s; }
        .bp-btn-green:hover { filter: brightness(1.06); box-shadow: 0 6px 20px rgba(34,197,94,.4); transform: translateY(-1px); color: #fff; }
        .bp-btn-green:active { transform: translateY(0); }
        .bp-btn-secondary { background: #fff; color: var(--bp-navy); border: 1.5px solid #e2e8f0; font-weight: 700; border-radius: .85rem; transition: background .15s, border-color .15s; }
        .bp-btn-secondary:hover { background: #f8fafc; border-color: #cbd5e1; color: var(--bp-navy); }
        .bp-badge-gold { background: rgba(242,195,0,.14); color: #92400e; border: 1px solid rgba(242,195,0,.28); font-size: .78rem; font-weight: 700; }
        .bp-badge-red { background: rgba(239,68,68,.10); color: #991b1b; border: 1px solid rgba(239,68,68,.20); font-size: .78rem; font-weight: 700; }
        .bp-badge-green { background: rgba(34,197,94,.10); color: #166534; border: 1px solid rgba(34,197,94,.20); font-size: .78rem; font-weight: 700; }
        .hero { background: linear-gradient(135deg, var(--bp-navy) 0%, var(--bp-navy-2) 55%, #173b6d 100%); color: #fff; box-shadow: 0 18px 50px rgba(8, 28, 51, 0.22); }
        .bp-form-section-title { font-size: .72rem; text-transform: uppercase; letter-spacing: .08em; font-weight: 700; color: rgba(255,255,255,.55); margin-bottom: .15rem; }
        .bp-form-title { font-size: 1.1rem; font-weight: 800; color: #fff; margin: 0; line-height: 1.25; }
        .bp-hint { font-size: .78rem; color: var(--bp-muted); margin-top: .25rem; }
        table thead th { font-size: .78rem; text-transform: uppercase; letter-spacing: .06em; color: var(--bp-muted); font-weight: 700; border-bottom: 2px solid #f1f5f9 !important; padding-top: 1rem; padding-bottom: 1rem; }
        table tbody tr { transition: background .12s; }
        table tbody tr:hover { background: rgba(242,195,0,.04); }
        table tbody td { border-color: #f1f5f9; padding-top: .85rem; padding-bottom: .85rem; }
        .bp-topbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            z-index: 1030;
            background: #1f2937;
            border-bottom: 1px solid rgba(148, 163, 184, 0.22);
            box-shadow: 0 12px 26px rgba(2, 6, 23, 0.35);
        }
        .bp-nav-wrap { max-width: 1280px; margin: 0 auto; padding: .7rem 1rem; }
        .bp-nav {
            background: transparent;
            border: none;
            border-radius: 0;
            box-shadow: none;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: .8rem;
        }
        .bp-nav-actions { display: flex; gap: .6rem; flex-wrap: wrap; }
        .bp-nav-eyebrow { color: #fbbf24; font-size: .74rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; }
        .bp-nav-title { font-size: 1.15rem; font-weight: 800; line-height: 1.2; margin-top: .18rem; color: #f8fafc; }
        .bp-nav-subtitle { color: #cbd5e1; margin: .3rem 0 0; font-size: .88rem; }
        .bp-nav-link {
            color: #e2e8f0;
            text-decoration: none;
            font-weight: 600;
            padding: .2rem .1rem;
            border-bottom: 2px solid transparent;
            transition: color .15s ease, border-color .15s ease;
        }
        .bp-nav-link:hover {
            color: #fbbf24;
            border-bottom-color: #fbbf24;
        }
        .bp-nav-link.active {
            color: #fbbf24;
            border-bottom-color: #fbbf24;
        }
        .bp-nav-logout {
            background: transparent;
            border: none;
            color: #fecaca;
            font-weight: 600;
            padding: .2rem .1rem;
            border-bottom: 2px solid transparent;
            cursor: pointer;
            transition: color .15s ease, border-color .15s ease;
        }
        .bp-nav-logout:hover {
            color: #fca5a5;
            border-bottom-color: #fca5a5;
        }
    </style>
</head>
<body>
<x-topbar title="Estoque" active="estoque" />

<div class="container-fluid px-3 px-md-4 py-3 py-md-4 bp-shell">

    @if (session('success'))
        <div class="alert alert-success border-0 rounded-4 shadow-sm">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger border-0 rounded-4 shadow-sm">
            <div class="fw-bold mb-2">Nao foi possivel concluir a operacao:</div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('xml_resultado'))
        @php $res = session('xml_resultado'); @endphp
        <div class="card border-0 rounded-4 shadow-sm mb-2" style="overflow:hidden;">
            <div class="card-header d-flex align-items-center gap-2 border-0" style="background:linear-gradient(135deg,#064e3b 0%,#065f46 100%);color:#fff;padding:1rem 1.4rem;">
                <i class="bi bi-file-earmark-check-fill fs-5"></i>
                <div class="flex-grow-1">
                    <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.08em;opacity:.65;">Resultado da importação NF-e</div>
                    <div class="fw-bold" style="font-size:.98rem;">
                        NF {{ $res['nfe']['numero'] }} · Série {{ $res['nfe']['serie'] }}
                        @if($res['fornecedor']) · <span style="opacity:.8;">{{ $res['fornecedor']['nome'] }}</span> @endif
                    </div>
                </div>
                <span class="badge rounded-pill px-3" style="background:rgba(74,222,128,.18);color:#4ade80;font-size:.8rem;">
                    {{ count($res['importados']) }} importado(s)
                </span>
                @if(count($res['nao_mapeados']) > 0)
                    <span class="badge rounded-pill px-3" style="background:rgba(251,191,36,.18);color:#fbbf24;font-size:.8rem;">
                        {{ count($res['nao_mapeados']) }} sem mapeamento
                    </span>
                @endif
            </div>
            @if(count($res['importados']) > 0)
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0 align-middle" style="font-size:.88rem;">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">SKU Fornecedor</th>
                                <th>Descrição</th>
                                <th>Produto no Sistema</th>
                                <th>Qtd.</th>
                                <th>Custo Unit.</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($res['importados'] as $item)
                                <tr>
                                    <td class="ps-4"><span class="badge rounded-pill px-2" style="background:rgba(34,197,94,.1);color:#166534;font-family:monospace;font-size:.8rem;">{{ $item['sku'] }}</span></td>
                                    <td>{{ $item['nome'] }}</td>
                                    <td class="fw-semibold">{{ $item['produto'] }}</td>
                                    <td>{{ number_format($item['quantidade'], 0, ',', '.') }}</td>
                                    <td>R$ {{ number_format($item['custo'], 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
            @if(count($res['nao_mapeados']) > 0)
            <div class="card-footer border-0 p-3" style="background:#fffbeb;">
                <div class="d-flex align-items-start gap-2">
                    <i class="bi bi-exclamation-triangle-fill text-warning mt-1"></i>
                    <div>
                        <div class="fw-bold text-warning-emphasis mb-1" style="font-size:.85rem;">SKUs sem mapeamento — configure o De-Para para importar estes itens:</div>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($res['nao_mapeados'] as $nm)
                                <span class="badge rounded-pill px-3" style="background:rgba(251,191,36,.15);color:#92400e;border:1px solid rgba(251,191,36,.3);font-family:monospace;">{{ $nm['sku'] }}</span>
                            @endforeach
                        </div>
                        <a href="{{ route('sku-depara.index') }}" class="btn btn-sm mt-2" style="background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;border-radius:.65rem;font-weight:700;">
                            <i class="bi bi-arrow-left-right me-1"></i>Configurar SKU De-Para
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    @endif



    <section class="row g-4">
        <div class="col-12">
            <div class="d-flex flex-wrap gap-2">
                <button type="button" class="btn bp-btn-primary d-flex align-items-center gap-2 px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalCadastrarProduto">
                    <i class="bi bi-plus-circle-fill fs-5"></i> Novo produto
                </button>
                <button type="button" class="btn bp-btn-green d-flex align-items-center gap-2 px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalReposicao">
                    <i class="bi bi-arrow-repeat fs-5"></i> Repor estoque
                </button>
                <button type="button" class="btn d-flex align-items-center gap-2 px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalImportarXml"
                    style="background:linear-gradient(135deg,#0284c7 0%,#0369a1 100%);color:#fff;border:none;font-weight:800;border-radius:.85rem;box-shadow:0 4px 14px rgba(2,132,199,.3);letter-spacing:.02em;">
                    <i class="bi bi-filetype-xml fs-5"></i> Importar NF-e XML
                </button>
                <a href="{{ route('sku-depara.index') }}" class="btn bp-btn-secondary d-flex align-items-center gap-2 px-4 py-2">
                    <i class="bi bi-arrow-left-right fs-5"></i> SKU De-Para
                </a>
            </div>
        </div>

        <div class="col-12">
            <div class="card bp-card h-100">
                <div class="card-header bp-card-header-tabela px-4 py-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bp-card-icon" style="background:rgba(8,28,51,.07);color:var(--bp-navy);">
                            <i class="bi bi-table"></i>
                        </div>
                        <div>
                            <div class="text-uppercase small fw-bold" style="font-size:.7rem;letter-spacing:.08em;color:var(--bp-muted);">Inventário atual</div>
                            <h2 class="h5 mb-0 fw-bold" style="color: var(--bp-navy);">Produtos cadastrados</h2>
                        </div>
                    </div>
                    <span class="badge bp-badge-gold rounded-pill px-3 py-2"><i class="bi bi-boxes me-1"></i>{{ $produtos->count() }} itens</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Produto</th>
                                    <th>SKU</th>
                                    <th>Qtd.</th>
                                    <th>Custo</th>
                                    <th>Venda</th>
                                    <th class="pe-4 text-end">Status</th>
                                    <th class="pe-4 text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($produtos as $produto)
                                    <tr>
                                        <td class="ps-4 fw-semibold">{{ $produto->nome }}</td>
                                        <td>
                                            @if($produto->sku)
                                                <span class="badge rounded-pill px-2" style="background:rgba(8,28,51,.07);color:var(--bp-navy);font-family:monospace;font-size:.8rem;">{{ $produto->sku }}</span>
                                            @else
                                                <span class="text-muted" style="font-size:.82rem;">—</span>
                                            @endif
                                        </td>
                                        <td>{{ $produto->quantidade }}</td>
                                        <td>R$ {{ number_format($produto->preco_custo, 2, ',', '.') }}</td>
                                        <td>R$ {{ number_format($produto->preco_unitario, 2, ',', '.') }}</td>
                                        <td class="pe-4 text-end">
                                            @if ($produto->quantidade <= 5)
                                                <span class="badge bp-badge-red rounded-pill"><i class="bi bi-exclamation-triangle-fill me-1"></i>Baixo estoque</span>
                                            @else
                                                <span class="badge bp-badge-green rounded-pill"><i class="bi bi-check-circle-fill me-1"></i>Normal</span>
                                            @endif
                                        </td>
                                        <td class="pe-4 text-end">
                                            <button
                                                type="button"
                                                class="btn btn-sm bp-btn-secondary js-editar-preco"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalEditarPreco"
                                                data-produto-id="{{ $produto->id }}"
                                                data-produto-nome="{{ $produto->nome }}"
                                                data-produto-preco="{{ number_format($produto->preco_unitario, 2, '.', '') }}"
                                            >
                                                <i class="bi bi-pencil me-1"></i>Editar preço
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center text-muted py-4">Nenhum produto cadastrado.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

{{-- Modal: Cadastrar Produto --}}
<div class="modal fade" id="modalCadastrarProduto" tabindex="-1" aria-labelledby="modalCadastrarProdutoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius:1.25rem;overflow:hidden;">
            <div class="modal-header bp-card-header-cadastro d-flex align-items-center gap-3 border-0">
                <div class="bp-card-icon bp-card-icon-cadastro">
                    <i class="bi bi-box-seam-fill"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="bp-form-section-title">Novo produto</div>
                    <h5 class="bp-form-title mb-0" id="modalCadastrarProdutoLabel">Cadastrar no estoque</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <form method="POST" action="{{ route('produtos.store') }}">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="nome" class="bp-label"><i class="bi bi-tag me-1 text-primary opacity-75"></i>Nome do produto</label>
                        <input id="nome" name="nome" type="text" value="{{ old('nome') }}" required placeholder="Ex: Bateria 60Ah" class="form-control bp-control">
                    </div>
                    <div class="mb-3">
                        <label for="sku" class="bp-label"><i class="bi bi-upc me-1 text-primary opacity-75"></i>SKU interno <span class="text-muted fw-normal">(opcional)</span></label>
                        <input id="sku" name="sku" type="text" value="{{ old('sku') }}" placeholder="Ex: BAT-60AH-001" class="form-control bp-control" maxlength="100" style="font-family:monospace;">
                    </div>
                    <div class="mb-3">
                        <label for="quantidade" class="bp-label"><i class="bi bi-stack me-1 text-primary opacity-75"></i>Quantidade inicial</label>
                        <div class="input-group bp-input-group">
                            <span class="bp-prefix"><i class="bi bi-hash"></i></span>
                            <input id="quantidade" name="quantidade" type="number" min="0" value="{{ old('quantidade') }}" required placeholder="0" class="form-control bp-control">
                        </div>
                    </div>
                    <div class="row g-3 mb-1">
                        <div class="col-12 col-md-6">
                            <label for="preco_custo" class="bp-label"><i class="bi bi-arrow-down-circle me-1 text-danger opacity-75"></i>Preço de custo</label>
                            <div class="input-group bp-input-group">
                                <span class="bp-prefix">R$</span>
                                <input id="preco_custo" name="preco_custo" type="number" step="0.01" min="0" value="{{ old('preco_custo') }}" required placeholder="0,00" class="form-control bp-control">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="preco_unitario" class="bp-label"><i class="bi bi-arrow-up-circle me-1 text-success opacity-75"></i>Preço de venda</label>
                            <div class="input-group bp-input-group">
                                <span class="bp-prefix">R$</span>
                                <input id="preco_unitario" name="preco_unitario" type="number" step="0.01" min="0" value="{{ old('preco_unitario') }}" required placeholder="0,00" class="form-control bp-control">
                            </div>
                        </div>
                    </div>
                    <div class="bp-hint"><i class="bi bi-info-circle me-1"></i>A margem será calculada automaticamente com base nos preços informados.</div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                    <button type="button" class="btn bp-btn-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn bp-btn-primary px-4 d-flex align-items-center gap-2">
                        <i class="bi bi-plus-circle-fill"></i> Cadastrar produto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal: Reposição de Estoque --}}
<div class="modal fade" id="modalReposicao" tabindex="-1" aria-labelledby="modalReposicaoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius:1.25rem;overflow:hidden;">
            <div class="modal-header bp-card-header-reposicao d-flex align-items-center gap-3 border-0">
                <div class="bp-card-icon" style="background:rgba(74,222,128,.18);color:#4ade80;">
                    <i class="bi bi-arrow-repeat"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="bp-form-section-title">Reposição rápida</div>
                    <h5 class="bp-form-title mb-0" id="modalReposicaoLabel">Aumentar estoque</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <form method="POST" action="{{ route('estoque.entrada') }}">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="produto_entrada" class="bp-label"><i class="bi bi-search me-1 text-primary opacity-75"></i>Produto já cadastrado</label>
                        <select id="produto_entrada" name="produto_id" required class="form-select bp-control">
                            <option value="">Selecione o produto...</option>
                            @foreach ($produtos as $produto)
                                <option value="{{ $produto->id }}" data-custo="{{ number_format($produto->preco_custo, 2, '.', '') }}" @selected(old('produto_id') == $produto->id)>
                                    {{ $produto->nome }} — {{ $produto->quantidade }} un. em estoque
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-3 mb-1">
                        <div class="col-12 col-md-6">
                            <label for="quantidade_entrada" class="bp-label"><i class="bi bi-plus-slash-minus me-1 text-primary opacity-75"></i>Qtd. a adicionar</label>
                            <div class="input-group bp-input-group">
                                <span class="bp-prefix"><i class="bi bi-hash"></i></span>
                                <input id="quantidade_entrada" name="quantidade" type="number" min="1" value="{{ old('quantidade') }}" required placeholder="0" class="form-control bp-control">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="preco_custo_entrada" class="bp-label"><i class="bi bi-receipt me-1 text-primary opacity-75"></i>Preço de custo</label>
                            <div class="input-group bp-input-group">
                                <span class="bp-prefix">R$</span>
                                <input id="preco_custo_entrada" name="preco_custo" type="number" step="0.01" min="0" value="{{ old('preco_custo') }}" required placeholder="0,00" class="form-control bp-control">
                            </div>
                        </div>
                    </div>
                    <div class="bp-hint"><i class="bi bi-calculator me-1"></i>O custo será recalculado como <strong>preço médio ponderado</strong> entre o estoque atual e a entrada.</div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                    <button type="button" class="btn bp-btn-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn bp-btn-green px-4 d-flex align-items-center gap-2">
                        <i class="bi bi-plus-lg"></i> Somar ao estoque
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditarPreco" tabindex="-1" aria-labelledby="modalEditarPrecoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarPrecoLabel">Editar valor de venda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <form id="formEditarPreco" method="POST" action="{{ route('produtos.preco-venda.update', ['produto' => 0]) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p class="mb-3 text-secondary" id="modalProdutoNome"></p>
                    <div class="mb-0">
                        <label for="modalPrecoUnitario" class="bp-label">Novo valor de venda</label>
                        <input id="modalPrecoUnitario" name="preco_unitario" type="number" step="0.01" min="0" required class="form-control bp-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bp-btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn bp-btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal: Importar NF-e XML --}}
<div class="modal fade" id="modalImportarXml" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:1.25rem;overflow:hidden;">
            <div class="modal-header border-0 d-flex align-items-center gap-3" style="background:linear-gradient(135deg,#0284c7 0%,#0369a1 100%);">
                <div style="width:44px;height:44px;border-radius:.85rem;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,.15);color:#fff;font-size:1.25rem;">
                    <i class="bi bi-filetype-xml"></i>
                </div>
                <div class="flex-grow-1">
                    <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.08em;font-weight:700;color:rgba(255,255,255,.55);">Ingestão de NF-e</div>
                    <h5 class="mb-0" style="font-size:1.1rem;font-weight:800;color:#fff;">Importar XML da Nota Fiscal</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('estoque.importar-xml') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="xml_nfe" class="bp-label"><i class="bi bi-upload me-1 text-primary opacity-75"></i>Arquivo XML da NF-e</label>
                        <input id="xml_nfe" name="xml_nfe" type="file" accept=".xml" required class="form-control bp-control">
                        <div class="form-text text-muted mt-1" style="font-size:.78rem;">
                            <i class="bi bi-info-circle me-1"></i>Formato <code>nfeProc</code> versão 4.00. Máx. 5 MB.
                        </div>
                    </div>
                    <div class="alert border-0 rounded-3 mb-0 d-flex gap-2 align-items-start" style="background:rgba(2,132,199,.07);font-size:.85rem;">
                        <i class="bi bi-lightbulb-fill text-info mt-1"></i>
                        <div>
                            O sistema usará o <strong>CNPJ do emitente</strong> para identificar o fornecedor e
                            o <strong>cProd</strong> de cada item para localizar o produto via tabela
                            <a href="{{ route('sku-depara.index') }}" target="_blank" class="fw-bold">SKU De-Para</a>.
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                    <button type="button" class="btn bp-btn-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn px-4 d-flex align-items-center gap-2"
                        style="background:linear-gradient(135deg,#0284c7 0%,#0369a1 100%);color:#fff;border:none;font-weight:800;border-radius:.85rem;">
                        <i class="bi bi-cloud-upload-fill"></i> Processar XML
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
(() => {
    const selectProduto = document.getElementById('produto_entrada');
    const inputCusto = document.getElementById('preco_custo_entrada');

    if (!selectProduto || !inputCusto) {
        return;
    }

    const custoAnterior = inputCusto.value;

    function aplicarUltimoCusto() {
        const opcaoSelecionada = selectProduto.options[selectProduto.selectedIndex];
        if (!opcaoSelecionada) {
            return;
        }

        const custo = opcaoSelecionada.dataset.custo;
        if (custo) {
            inputCusto.value = custo;
        }
    }

    selectProduto.addEventListener('change', aplicarUltimoCusto);

    if (!custoAnterior) {
        aplicarUltimoCusto();
    }

    // Auto-abrir modal ao retornar com erros de validação
    @if ($errors->has('nome') || $errors->has('quantidade') || $errors->has('preco_custo') || $errors->has('preco_unitario'))
        new bootstrap.Modal(document.getElementById('modalCadastrarProduto')).show();
    @elseif ($errors->has('produto_id') || $errors->has('quantidade_entrada'))
        new bootstrap.Modal(document.getElementById('modalReposicao')).show();
    @elseif ($errors->has('xml_nfe'))
        new bootstrap.Modal(document.getElementById('modalImportarXml')).show();
    @endif

    const modal = document.getElementById('modalEditarPreco');
    const formEditarPreco = document.getElementById('formEditarPreco');
    const modalProdutoNome = document.getElementById('modalProdutoNome');
    const modalPrecoUnitario = document.getElementById('modalPrecoUnitario');

    if (!modal || !formEditarPreco || !modalProdutoNome || !modalPrecoUnitario) {
        return;
    }

    modal.addEventListener('show.bs.modal', (event) => {
        const button = event.relatedTarget;
        if (!(button instanceof HTMLElement)) {
            return;
        }

        const produtoId = button.dataset.produtoId;
        const produtoNome = button.dataset.produtoNome || '';
        const produtoPreco = button.dataset.produtoPreco || '';

        if (!produtoId) {
            return;
        }

        formEditarPreco.action = `{{ url('/produtos') }}/${produtoId}/preco-venda`;
        modalProdutoNome.textContent = `Produto: ${produtoNome}`;
        modalPrecoUnitario.value = produtoPreco;
    });
})();
</script>
</body>
</html>
