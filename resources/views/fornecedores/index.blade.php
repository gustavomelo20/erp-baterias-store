<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fornecedores | Baterias ERP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --bp-navy: #081c33; --bp-navy-2: #10284b; --bp-gold: #f2c300;
            --bp-gold-dark: #c89500; --bp-orange: #ff7a00; --bp-bg: #f5f7fb;
            --bp-text: #0f172a; --bp-muted: #64748b;
        }
        body { background: radial-gradient(circle at top right,rgba(242,195,0,.12),transparent 28%), radial-gradient(circle at left center,rgba(255,122,0,.08),transparent 30%), linear-gradient(180deg,#fbfcfe 0%,var(--bp-bg) 100%); color: var(--bp-text); }
        .bp-shell { max-width: 1280px; }
        .bp-card { border: none; box-shadow: 0 4px 6px -1px rgba(0,0,0,.07), 0 10px 28px rgba(15,23,42,.06); border-radius: 1.35rem; overflow: hidden; }
        .bp-card-header { background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%); color: #fff; border-bottom: none; padding: 1.4rem 1.6rem; }
        .bp-card-header-tabela { background: linear-gradient(90deg,rgba(8,28,51,.04),rgba(124,58,237,.08)); border-bottom: 1px solid rgba(8,28,51,.07); }
        .bp-card-icon { width: 44px; height: 44px; border-radius: .85rem; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0; background: rgba(255,255,255,.15); color: #fff; }
        .bp-label { color: #374151; font-weight: 600; font-size: .85rem; margin-bottom: .3rem; letter-spacing: .01em; }
        .bp-control { border-radius: .75rem; border: 1.5px solid #e2e8f0; padding: .65rem 1rem; background: #fafbfc; transition: border-color .15s, box-shadow .15s; font-size: .93rem; }
        .bp-control:focus { border-color: #7c3aed; box-shadow: 0 0 0 3px rgba(124,58,237,.15); background: #fff; outline: none; }
        .bp-btn-primary { background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%); color: #fff; border: none; font-weight: 800; border-radius: .85rem; box-shadow: 0 4px 14px rgba(124,58,237,.3); letter-spacing: .02em; transition: filter .15s, box-shadow .15s, transform .12s; }
        .bp-btn-primary:hover { filter: brightness(1.08); color: #fff; transform: translateY(-1px); }
        .bp-btn-secondary { background: #fff; color: var(--bp-navy); border: 1.5px solid #e2e8f0; font-weight: 700; border-radius: .85rem; transition: background .15s, border-color .15s; }
        .bp-btn-secondary:hover { background: #f8fafc; border-color: #cbd5e1; color: var(--bp-navy); }
        .bp-btn-danger { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: #fff; border: none; font-weight: 700; border-radius: .75rem; transition: filter .15s; }
        .bp-btn-danger:hover { filter: brightness(1.08); color: #fff; }
        .bp-form-section-title { font-size: .72rem; text-transform: uppercase; letter-spacing: .08em; font-weight: 700; color: rgba(255,255,255,.55); margin-bottom: .15rem; }
        .bp-form-title { font-size: 1.1rem; font-weight: 800; color: #fff; margin: 0; line-height: 1.25; }
        table thead th { font-size: .78rem; text-transform: uppercase; letter-spacing: .06em; color: var(--bp-muted); font-weight: 700; border-bottom: 2px solid #f1f5f9 !important; padding: 1rem; }
        table tbody tr { transition: background .12s; }
        table tbody tr:hover { background: rgba(124,58,237,.03); }
        table tbody td { border-color: #f1f5f9; padding: .85rem 1rem; }
    </style>
</head>
<body>
<x-topbar title="Fornecedores" active="fornecedores" />

<div class="container-fluid px-3 px-md-4 py-3 py-md-4 bp-shell">

    @if (session('success'))
        <div class="alert alert-success border-0 rounded-4 shadow-sm">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger border-0 rounded-4 shadow-sm">
            <div class="fw-bold mb-2">Não foi possível concluir a operação:</div>
            <ul class="mb-0 ps-3">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <section class="row g-4">
        <div class="col-12">
            <div class="d-flex flex-wrap gap-2">
                <button type="button" class="btn bp-btn-primary d-flex align-items-center gap-2 px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalNovoFornecedor">
                    <i class="bi bi-plus-circle-fill fs-5"></i> Novo fornecedor
                </button>
            </div>
        </div>

        <div class="col-12">
            <div class="card bp-card">
                <div class="card-header bp-card-header-tabela px-4 py-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bp-card-icon" style="background:rgba(124,58,237,.1);color:#7c3aed;">
                            <i class="bi bi-building"></i>
                        </div>
                        <div>
                            <div class="text-uppercase small fw-bold" style="font-size:.7rem;letter-spacing:.08em;color:var(--bp-muted);">Cadastro</div>
                            <h2 class="h5 mb-0 fw-bold" style="color:var(--bp-navy);">Fornecedores</h2>
                        </div>
                    </div>
                    <span class="badge rounded-pill px-3 py-2" style="background:rgba(124,58,237,.12);color:#6d28d9;font-size:.78rem;font-weight:700;">
                        <i class="bi bi-building me-1"></i>{{ $fornecedores->count() }} cadastrados
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Razão Social</th>
                                    <th>CNPJ</th>
                                    <th>Município / UF</th>
                                    <th>IE</th>
                                    <th class="pe-4 text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($fornecedores as $f)
                                    <tr>
                                        <td class="ps-4 fw-semibold">{{ $f->nome }}</td>
                                        <td class="text-monospace" style="font-family:monospace;">
                                            {{ substr($f->cnpj,0,2).'.'.substr($f->cnpj,2,3).'.'.substr($f->cnpj,5,3).'/'.substr($f->cnpj,8,4).'-'.substr($f->cnpj,12,2) }}
                                        </td>
                                        <td>{{ $f->municipio ? $f->municipio.' / '.$f->uf : '—' }}</td>
                                        <td>{{ $f->ie ?: '—' }}</td>
                                        <td class="pe-4 text-end">
                                            <button type="button" class="btn btn-sm bp-btn-secondary me-1 js-editar-fornecedor"
                                                data-bs-toggle="modal" data-bs-target="#modalEditarFornecedor"
                                                data-id="{{ $f->id }}"
                                                data-cnpj="{{ $f->cnpj }}"
                                                data-nome="{{ $f->nome }}"
                                                data-ie="{{ $f->ie }}"
                                                data-logradouro="{{ $f->logradouro }}"
                                                data-numero="{{ $f->numero }}"
                                                data-bairro="{{ $f->bairro }}"
                                                data-municipio="{{ $f->municipio }}"
                                                data-uf="{{ $f->uf }}"
                                                data-cep="{{ $f->cep }}">
                                                <i class="bi bi-pencil me-1"></i>Editar
                                            </button>
                                            <form method="POST" action="{{ route('fornecedores.destroy', $f) }}" class="d-inline" onsubmit="return confirm('Remover este fornecedor?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm bp-btn-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted py-4">Nenhum fornecedor cadastrado.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

{{-- Modal: Novo Fornecedor --}}
<div class="modal fade" id="modalNovoFornecedor" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius:1.25rem;overflow:hidden;">
            <div class="modal-header bp-card-header d-flex align-items-center gap-3 border-0" style="background:linear-gradient(135deg,#7c3aed 0%,#6d28d9 100%);">
                <div class="bp-card-icon"><i class="bi bi-building-add"></i></div>
                <div class="flex-grow-1">
                    <div class="bp-form-section-title">Cadastro</div>
                    <h5 class="bp-form-title mb-0">Novo fornecedor</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('fornecedores.store') }}">
                @csrf
                @include('fornecedores._form')
                <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                    <button type="button" class="btn bp-btn-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn bp-btn-primary px-4"><i class="bi bi-plus-circle-fill me-1"></i>Cadastrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal: Editar Fornecedor --}}
<div class="modal fade" id="modalEditarFornecedor" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius:1.25rem;overflow:hidden;">
            <div class="modal-header bp-card-header d-flex align-items-center gap-3 border-0" style="background:linear-gradient(135deg,#7c3aed 0%,#6d28d9 100%);">
                <div class="bp-card-icon"><i class="bi bi-pencil-square"></i></div>
                <div class="flex-grow-1">
                    <div class="bp-form-section-title">Editar</div>
                    <h5 class="bp-form-title mb-0">Atualizar fornecedor</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="formEditarFornecedor" action="">
                @csrf @method('PUT')
                @include('fornecedores._form', ['editar' => true])
                <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                    <button type="button" class="btn bp-btn-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn bp-btn-primary px-4"><i class="bi bi-check-lg me-1"></i>Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
document.querySelectorAll('.js-editar-fornecedor').forEach(btn => {
    btn.addEventListener('click', () => {
        const f = btn.dataset;
        const form = document.getElementById('formEditarFornecedor');
        form.action = `/fornecedores/${f.id}`;
        form.querySelector('[name=cnpj]').value      = f.cnpj;
        form.querySelector('[name=nome]').value      = f.nome;
        form.querySelector('[name=ie]').value        = f.ie ?? '';
        form.querySelector('[name=logradouro]').value= f.logradouro ?? '';
        form.querySelector('[name=numero]').value    = f.numero ?? '';
        form.querySelector('[name=bairro]').value    = f.bairro ?? '';
        form.querySelector('[name=municipio]').value = f.municipio ?? '';
        form.querySelector('[name=uf]').value        = f.uf ?? '';
        form.querySelector('[name=cep]').value       = f.cep ?? '';
    });
});

@if ($errors->any())
    @if (old('_method') === 'PUT')
        new bootstrap.Modal(document.getElementById('modalEditarFornecedor')).show();
    @else
        new bootstrap.Modal(document.getElementById('modalNovoFornecedor')).show();
    @endif
@endif
</script>
</body>
</html>
