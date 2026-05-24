<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Estoque | Baterias ERP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
            padding-top: 88px;
        }

        .bp-shell { max-width: 1280px; }
        .bp-card { border: 1px solid rgba(8, 28, 51, 0.08); box-shadow: 0 14px 30px rgba(15, 23, 42, 0.06); border-radius: 1.25rem; overflow: hidden; }
        .bp-card .card-header { background: linear-gradient(90deg, rgba(8, 28, 51, 0.05), rgba(242, 195, 0, 0.10)); border-bottom: 1px solid rgba(8, 28, 51, 0.08); }
        .bp-label { color: var(--bp-navy); font-weight: 700; font-size: .9rem; margin-bottom: .35rem; }
        .bp-control { border-radius: .95rem; border: 1px solid rgba(8, 28, 51, 0.16); padding: .9rem 1rem; }
        .bp-control:focus { border-color: var(--bp-gold); box-shadow: 0 0 0 .25rem rgba(242, 195, 0, 0.18); }
        .bp-btn-primary { background: linear-gradient(135deg, var(--bp-gold) 0%, #ffb300 100%); color: var(--bp-navy); border: none; font-weight: 800; border-radius: .95rem; box-shadow: 0 10px 24px rgba(242, 195, 0, 0.25); }
        .bp-btn-secondary { background: #fff; color: var(--bp-navy); border: 1px solid rgba(8, 28, 51, 0.15); font-weight: 700; border-radius: .95rem; }
        .bp-badge-gold { background: rgba(242, 195, 0, 0.15); color: #7a5700; border: 1px solid rgba(242, 195, 0, 0.24); }
        .bp-badge-red { background: rgba(192, 57, 43, 0.12); color: #8f2419; border: 1px solid rgba(192, 57, 43, 0.18); }
        .hero { background: linear-gradient(135deg, var(--bp-navy) 0%, var(--bp-navy-2) 55%, #173b6d 100%); color: #fff; box-shadow: 0 18px 50px rgba(8, 28, 51, 0.22); }
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



    <section class="row g-4">
        <div class="col-12 col-lg-6">
            <div class="card bp-card h-100">
                <div class="card-header px-4 py-3">
                    <div class="text-uppercase text-secondary small fw-semibold">Novo produto</div>
                    <h2 class="h5 mb-0 fw-bold" style="color: var(--bp-navy);">Cadastrar no estoque</h2>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('produtos.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="nome" class="bp-label">Nome do produto</label>
                            <input id="nome" name="nome" type="text" value="{{ old('nome') }}" required class="form-control bp-control">
                        </div>
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="quantidade" class="bp-label">Quantidade</label>
                                <input id="quantidade" name="quantidade" type="number" min="0" value="{{ old('quantidade') }}" required class="form-control bp-control">
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="preco_custo" class="bp-label">Preco de custo</label>
                                <input id="preco_custo" name="preco_custo" type="number" step="0.01" min="0" value="{{ old('preco_custo') }}" required class="form-control bp-control">
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="preco_unitario" class="bp-label">Preco venda</label>
                                <input id="preco_unitario" name="preco_unitario" type="number" step="0.01" min="0" value="{{ old('preco_unitario') }}" required class="form-control bp-control">
                            </div>
                        </div>
                        <button type="submit" class="btn bp-btn-primary w-100 mt-3 py-3">Cadastrar produto</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card bp-card h-100">
                <div class="card-header px-4 py-3">
                    <div class="text-uppercase text-secondary small fw-semibold">Reposição rápida</div>
                    <h2 class="h5 mb-0 fw-bold" style="color: var(--bp-navy);">Aumentar estoque</h2>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('estoque.entrada') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="produto_entrada" class="bp-label">Produto já cadastrado</label>
                            <select id="produto_entrada" name="produto_id" required class="form-select bp-control">
                                <option value="">Selecione o produto...</option>
                                @foreach ($produtos as $produto)
                                    <option value="{{ $produto->id }}" data-custo="{{ number_format($produto->preco_custo, 2, '.', '') }}" @selected(old('produto_id') == $produto->id)>
                                        {{ $produto->nome }} — atual {{ $produto->quantidade }} un. / custo R$ {{ number_format($produto->preco_custo, 2, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="quantidade_entrada" class="bp-label">Qtd. a adicionar</label>
                                <input id="quantidade_entrada" name="quantidade" type="number" min="1" value="{{ old('quantidade') }}" required class="form-control bp-control">
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="preco_custo_entrada" class="bp-label">Preço de custo</label>
                                <input id="preco_custo_entrada" name="preco_custo" type="number" step="0.01" min="0" value="{{ old('preco_custo') }}" required class="form-control bp-control">
                            </div>
                        </div>
                        <p class="text-secondary mt-2 mb-0" style="font-size:.82rem;">O custo do produto será recalculado como preço médio ponderado.</p>
                        <button type="submit" class="btn bp-btn-primary w-100 mt-3 py-3">Somar ao estoque</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card bp-card h-100">
                <div class="card-header px-4 py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase text-secondary small fw-semibold">Inventário atual</div>
                        <h2 class="h5 mb-0 fw-bold" style="color: var(--bp-navy);">Produtos cadastrados</h2>
                    </div>
                    <span class="badge bp-badge-gold rounded-pill px-3 py-2">{{ $produtos->count() }} itens</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Produto</th>
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
                                        <td>{{ $produto->quantidade }}</td>
                                        <td>R$ {{ number_format($produto->preco_custo, 2, ',', '.') }}</td>
                                        <td>R$ {{ number_format($produto->preco_unitario, 2, ',', '.') }}</td>
                                        <td class="pe-4 text-end">
                                            @if ($produto->quantidade <= 5)
                                                <span class="badge bp-badge-red rounded-pill">Baixo estoque</span>
                                            @else
                                                <span class="badge bp-badge-gold rounded-pill">Ok</span>
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
                                                Editar preço
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center text-muted py-4">Nenhum produto cadastrado.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
(() => {
    const selectProduto = document.getElementById('produto_entrada');
    const inputCusto = document.getElementById('preco_custo_entrada');
    const custoAnterior = inputCusto.value;

    if (!selectProduto || !inputCusto) {
        return;
    }

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
