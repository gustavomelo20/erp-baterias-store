<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SKU De-Para | Baterias ERP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --bp-navy: #081c33; --bp-gold: #f2c300; --bp-bg: #f5f7fb;
            --bp-text: #0f172a; --bp-muted: #64748b; --bp-teal: #0d9488;
        }
        body { background: radial-gradient(circle at top right,rgba(13,148,136,.1),transparent 28%), radial-gradient(circle at left center,rgba(242,195,0,.08),transparent 30%), linear-gradient(180deg,#fbfcfe 0%,var(--bp-bg) 100%); color: var(--bp-text); }
        .bp-shell { max-width: 1280px; }
        .bp-card { border: none; box-shadow: 0 4px 6px -1px rgba(0,0,0,.07), 0 10px 28px rgba(15,23,42,.06); border-radius: 1.35rem; overflow: hidden; }
        .bp-card-header-tabela { background: linear-gradient(90deg,rgba(8,28,51,.04),rgba(13,148,136,.08)); border-bottom: 1px solid rgba(8,28,51,.07); }
        .bp-label { color: #374151; font-weight: 600; font-size: .85rem; margin-bottom: .3rem; }
        .bp-control { border-radius: .75rem; border: 1.5px solid #e2e8f0; padding: .65rem 1rem; background: #fafbfc; transition: border-color .15s, box-shadow .15s; font-size: .93rem; }
        .bp-control:focus { border-color: var(--bp-teal); box-shadow: 0 0 0 3px rgba(13,148,136,.15); background: #fff; outline: none; }
        .bp-btn-teal { background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%); color: #fff; border: none; font-weight: 800; border-radius: .85rem; box-shadow: 0 4px 14px rgba(13,148,136,.3); letter-spacing: .02em; transition: filter .15s, transform .12s; }
        .bp-btn-teal:hover { filter: brightness(1.08); color: #fff; transform: translateY(-1px); }
        .bp-btn-secondary { background: #fff; color: var(--bp-navy); border: 1.5px solid #e2e8f0; font-weight: 700; border-radius: .85rem; transition: background .15s; }
        .bp-btn-secondary:hover { background: #f8fafc; color: var(--bp-navy); }
        .bp-btn-danger { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: #fff; border: none; font-weight: 700; border-radius: .75rem; transition: filter .15s; }
        .bp-btn-danger:hover { filter: brightness(1.08); color: #fff; }
        .bp-form-section-title { font-size: .72rem; text-transform: uppercase; letter-spacing: .08em; font-weight: 700; color: rgba(255,255,255,.55); margin-bottom: .15rem; }
        .bp-form-title { font-size: 1.1rem; font-weight: 800; color: #fff; margin: 0; }
        table thead th { font-size: .78rem; text-transform: uppercase; letter-spacing: .06em; color: var(--bp-muted); font-weight: 700; border-bottom: 2px solid #f1f5f9 !important; padding: 1rem; }
        table tbody tr:hover { background: rgba(13,148,136,.03); }
        table tbody td { border-color: #f1f5f9; padding: .85rem 1rem; }
        .badge-sku { background: rgba(13,148,136,.12); color: #0f766e; border: 1px solid rgba(13,148,136,.22); font-size: .8rem; font-weight: 700; font-family: monospace; }
    </style>
</head>
<body>
<x-topbar title="SKU De-Para" active="sku-depara" />

<div class="container-fluid px-3 px-md-4 py-3 py-md-4 bp-shell">

    @if (session('success'))
        <div class="alert alert-success border-0 rounded-4 shadow-sm">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger border-0 rounded-4 shadow-sm">
            <div class="fw-bold mb-2">Não foi possível concluir:</div>
            <ul class="mb-0 ps-3">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    @if ($fornecedores->isEmpty())
        <div class="alert alert-warning border-0 rounded-4 shadow-sm d-flex align-items-center gap-3">
            <i class="bi bi-exclamation-triangle-fill fs-4"></i>
            <div>
                Nenhum fornecedor cadastrado.
                <a href="{{ route('fornecedores.index') }}" class="fw-bold">Cadastre um fornecedor</a> antes de criar mapeamentos SKU.
            </div>
        </div>
    @endif

    <section class="row g-4">
        <div class="col-12">
            <div class="d-flex flex-wrap gap-2">
                <button type="button" class="btn bp-btn-teal d-flex align-items-center gap-2 px-4 py-2"
                    data-bs-toggle="modal" data-bs-target="#modalNovoDepara" {{ $fornecedores->isEmpty() ? 'disabled' : '' }}>
                    <i class="bi bi-plus-circle-fill fs-5"></i> Novo mapeamento
                </button>
                <a href="{{ route('fornecedores.index') }}" class="btn bp-btn-secondary d-flex align-items-center gap-2 px-4 py-2">
                    <i class="bi bi-building fs-5"></i> Gerenciar fornecedores
                </a>
            </div>
        </div>

        <div class="col-12">
            <div class="card bp-card">
                <div class="card-header bp-card-header-tabela px-4 py-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:44px;height:44px;border-radius:.85rem;display:flex;align-items:center;justify-content:center;background:rgba(13,148,136,.1);color:var(--bp-teal);font-size:1.25rem;">
                            <i class="bi bi-arrow-left-right"></i>
                        </div>
                        <div>
                            <div class="text-uppercase small fw-bold" style="font-size:.7rem;letter-spacing:.08em;color:var(--bp-muted);">Mapeamento</div>
                            <h2 class="h5 mb-0 fw-bold" style="color:var(--bp-navy);">SKU Fornecedor → Produto</h2>
                        </div>
                    </div>
                    <span class="badge rounded-pill px-3 py-2" style="background:rgba(13,148,136,.12);color:#0f766e;font-size:.78rem;font-weight:700;">
                        <i class="bi bi-arrow-left-right me-1"></i>{{ $depara->count() }} mapeamentos
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Fornecedor</th>
                                    <th>SKU Fornecedor</th>
                                    <th><i class="bi bi-arrow-right me-1"></i>Produto no Sistema</th>
                                    <th>SKU Produto</th>
                                    <th class="pe-4 text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($depara as $d)
                                    <tr>
                                        <td class="ps-4 fw-semibold">{{ $d->fornecedor->nome }}</td>
                                        <td><span class="badge badge-sku rounded-pill px-3">{{ $d->sku_fornecedor }}</span></td>
                                        <td>{{ $d->produto->nome }}</td>
                                        <td>{{ $d->produto->sku ?: '—' }}</td>
                                        <td class="pe-4 text-end">
                                            <form method="POST" action="{{ route('sku-depara.destroy', $d) }}" class="d-inline" onsubmit="return confirm('Remover este mapeamento?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm bp-btn-danger"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted py-4">Nenhum mapeamento cadastrado.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

{{-- Modal: Novo Mapeamento --}}
<div class="modal fade" id="modalNovoDepara" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:1.25rem;overflow:hidden;">
            <div class="modal-header border-0 d-flex align-items-center gap-3" style="background:linear-gradient(135deg,#0d9488 0%,#0f766e 100%);">
                <div style="width:44px;height:44px;border-radius:.85rem;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,.15);color:#fff;font-size:1.25rem;">
                    <i class="bi bi-arrow-left-right"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="bp-form-section-title">Mapeamento</div>
                    <h5 class="bp-form-title mb-0">Novo SKU De-Para</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('sku-depara.store') }}">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="bp-label"><i class="bi bi-building me-1 text-primary opacity-75"></i>Fornecedor</label>
                        <select name="fornecedor_id" required class="form-select bp-control">
                            <option value="">Selecione o fornecedor...</option>
                            @foreach ($fornecedores as $f)
                                <option value="{{ $f->id }}" @selected(old('fornecedor_id') == $f->id)>{{ $f->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="bp-label"><i class="bi bi-upc-scan me-1 text-primary opacity-75"></i>SKU do Fornecedor (cProd no XML)</label>
                        <input name="sku_fornecedor" type="text" value="{{ old('sku_fornecedor') }}" required
                            placeholder="Ex: MOURA60" class="form-control bp-control" maxlength="100" style="font-family:monospace;">
                        <div class="form-text text-muted" style="font-size:.78rem;margin-top:.25rem;">
                            <i class="bi bi-info-circle me-1"></i>Corresponde ao campo <code>&lt;cProd&gt;</code> no XML da NF-e.
                        </div>
                    </div>
                    <div class="mb-1">
                        <label class="bp-label"><i class="bi bi-box-seam me-1 text-primary opacity-75"></i>Produto no Sistema</label>
                        <select name="produto_id" required class="form-select bp-control">
                            <option value="">Selecione o produto...</option>
                            @foreach ($produtos as $p)
                                <option value="{{ $p->id }}" @selected(old('produto_id') == $p->id)>
                                    {{ $p->nome }}{{ $p->sku ? ' — SKU: '.$p->sku : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                    <button type="button" class="btn bp-btn-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn bp-btn-teal px-4"><i class="bi bi-plus-circle-fill me-1"></i>Salvar mapeamento</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
@if ($errors->any())
    new bootstrap.Modal(document.getElementById('modalNovoDepara')).show();
@endif
</script>
</body>
</html>
