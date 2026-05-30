@props([
    'title',
    'subtitle' => null,
    'active' => null,
])

@php
    $adminLinks = [
        ['route' => 'welcome', 'key' => 'pdv', 'label' => 'Abrir PDV'],
        ['route' => 'painel.index', 'key' => 'painel', 'label' => 'Painel'],
        ['route' => 'estoque.index', 'key' => 'estoque', 'label' => 'Estoque'],
        ['route' => 'fornecedores.index', 'key' => 'fornecedores', 'label' => 'Fornecedores'],
        ['route' => 'sku-depara.index', 'key' => 'sku-depara', 'label' => 'SKU De-Para'],
        ['route' => 'configuracoes.index', 'key' => 'configuracoes', 'label' => 'Configurações'],
    ];
@endphp

<style>
    body {
        padding-top: 0 !important;
        padding-left: 304px !important;
    }

    .bp-topbar {
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        width: 304px;
        z-index: 1030;
        background: linear-gradient(180deg, #0f172a 0%, #111827 45%, #1f2937 100%);
        border-right: 1px solid rgba(148, 163, 184, 0.18);
        box-shadow: 18px 0 40px rgba(2, 6, 23, 0.18);
        overflow-y: auto;
    }

    .bp-nav-wrap {
        height: 100%;
        padding: 1rem .55rem;
    }

    .bp-nav {
        min-height: 100%;
        display: flex;
        flex-direction: column;
        align-items: stretch;
        gap: 1.2rem;
    }

    .bp-nav-brand {
        padding: .4rem;
        border-radius: 1rem;
        background: rgba(15, 23, 42, 0.32);
        border: 1px solid rgba(148, 163, 184, 0.14);
    }

    .bp-nav-eyebrow {
        color: #fbbf24;
        font-size: .72rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .08em;
    }

    .bp-nav-title {
        margin: .28rem 0 0;
        font-size: 1.35rem;
        font-weight: 800;
        line-height: 1.2;
        color: #f8fafc;
    }

    .bp-nav-subtitle {
        color: #cbd5e1;
        margin: .35rem 0 0;
        font-size: .88rem;
        line-height: 1.45;
    }

    .bp-nav-tools {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .bp-nav-menu {
        width: 100%;
    }

    .bp-nav-section-title {
        margin: 0 0 .5rem;
        color: #94a3b8;
        font-size: .74rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
    }

    .bp-nav-actions {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: .65rem;
    }

    .bp-nav-link,
    .bp-nav-logout {
        width: 100%;
        box-sizing: border-box;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        min-height: 44px;
        border-radius: .9rem;
        padding: .85rem 1.05rem;
        text-decoration: none;
        font-weight: 700;
        border: 1px solid transparent;
        transition: background-color .16s ease, color .16s ease, border-color .16s ease, transform .16s ease;
    }

    .bp-nav-link {
        color: #e2e8f0;
        background: rgba(148, 163, 184, 0.06);
    }

    .bp-nav-link:hover {
        color: #f8fafc;
        background: rgba(251, 191, 36, 0.12);
        border-color: rgba(251, 191, 36, 0.25);
        transform: translateX(2px);
    }

    .bp-nav-link.active {
        color: #0f172a;
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        border-color: transparent;
        box-shadow: 0 14px 30px rgba(245, 158, 11, 0.24);
    }

    .bp-nav-logout {
        background: rgba(127, 29, 29, 0.12);
        color: #fecaca;
        cursor: pointer;
    }

    .bp-nav-logout:hover {
        color: #fff;
        background: rgba(239, 68, 68, 0.18);
        border-color: rgba(248, 113, 113, 0.22);
    }

    .bp-nav-spacer {
        flex: 1;
        min-height: .5rem;
    }

    .bp-nav-footer {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: .8rem;
        margin-top: auto;
        padding-top: .8rem;
        border-top: 1px solid rgba(148, 163, 184, 0.12);
    }

    .bp-nav-store-switch {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: .45rem;
    }

    .bp-nav-store-switch select {
        min-height: 44px;
        border-radius: .85rem;
        padding: .7rem .9rem;
        width: 100%;
        min-width: 0;
        background: #111827;
        border: 1px solid #374151;
        color: #f8fafc;
        font-family: inherit;
        font-size: .9rem;
        appearance: auto;
    }

    .bp-nav-store-switch .form-select {
        min-height: 44px;
        border-radius: .85rem;
        padding: .7rem .9rem;
    }

    .bp-nav-logout-form {
        width: 100%;
        margin: 0;
    }

    /* ── Mobile hamburger ── */
    .bp-mobile-bar {
        display: none;
    }

    .bp-drawer-backdrop {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(2, 6, 23, 0.55);
        z-index: 1025;
        backdrop-filter: blur(2px);
        opacity: 0;
        transition: opacity .25s ease;
    }

    .bp-drawer-backdrop.open {
        opacity: 1;
    }

    @media (max-width: 960px) {
        body {
            padding-left: 0 !important;
            padding-top: 60px !important;
        }

        /* Mobile top bar */
        .bp-mobile-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            z-index: 1035;
            background: linear-gradient(90deg, #0f172a 0%, #1f2937 100%);
            border-bottom: 1px solid rgba(148, 163, 184, 0.18);
            box-shadow: 0 4px 16px rgba(2, 6, 23, 0.22);
            padding: 0 1rem;
            gap: .75rem;
        }

        .bp-mobile-brand {
            display: flex;
            flex-direction: column;
            gap: 0;
            flex: 1;
            min-width: 0;
        }

        .bp-mobile-eyebrow {
            color: #fbbf24;
            font-size: .65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .08em;
            line-height: 1;
        }

        .bp-mobile-title {
            color: #f8fafc;
            font-size: 1rem;
            font-weight: 800;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .bp-hamburger {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 5px;
            width: 40px;
            height: 40px;
            background: rgba(148, 163, 184, 0.1);
            border: 1px solid rgba(148, 163, 184, 0.2);
            border-radius: .65rem;
            cursor: pointer;
            flex-shrink: 0;
            transition: background .15s;
        }

        .bp-hamburger:hover {
            background: rgba(251, 191, 36, 0.15);
            border-color: rgba(251, 191, 36, 0.3);
        }

        .bp-hamburger span {
            display: block;
            width: 20px;
            height: 2px;
            background: #e2e8f0;
            border-radius: 2px;
            transition: transform .22s ease, opacity .22s ease;
            transform-origin: center;
        }

        .bp-hamburger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
        .bp-hamburger.open span:nth-child(2) { opacity: 0; transform: scaleX(0); }
        .bp-hamburger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

        /* Sidebar becomes off-canvas drawer */
        .bp-topbar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 280px;
            transform: translateX(-100%);
            transition: transform .28s cubic-bezier(.4, 0, .2, 1);
            z-index: 1030;
        }

        .bp-topbar.open {
            transform: translateX(0);
        }

        .bp-drawer-backdrop {
            display: block;
        }

        .bp-nav-spacer {
            display: none;
        }
    }
</style>

{{-- Mobile top bar --}}
<div class="bp-mobile-bar">
    <div class="bp-mobile-brand">
        <span class="bp-mobile-title">{{ $title }}</span>
    </div>
    <button type="button" class="bp-hamburger" id="bp-hamburger-btn" aria-label="Abrir menu" aria-expanded="false" aria-controls="bp-sidebar">
        <span></span>
        <span></span>
        <span></span>
    </button>
</div>

{{-- Backdrop --}}
<div class="bp-drawer-backdrop" id="bp-drawer-backdrop"></div>

<div class="bp-topbar" id="bp-sidebar">
    <div class="bp-nav-wrap">
        <div class="bp-nav">
            <div class="bp-nav-brand">
                <p class="bp-nav-eyebrow mb-0">Admin</p>
                <h1 class="bp-nav-title">{{ $title }}</h1>
                @if ($tenantEmpresa && $tenantLojaAtual)
                    <p class="bp-nav-subtitle mb-0">{{ $tenantEmpresa->nome }} - Loja: {{ $tenantLojaAtual->nome }}</p>
                @endif
                @if ($subtitle)
                    <p class="bp-nav-subtitle">{{ $subtitle }}</p>
                @endif
            </div>

            <div class="bp-nav-tools">
                <div class="bp-nav-menu">
                    <p class="bp-nav-section-title">Navegação</p>
                    <div class="bp-nav-actions">
                        @foreach ($adminLinks as $link)
                            <a href="{{ route($link['route']) }}" class="bp-nav-link {{ $active === $link['key'] ? 'active' : '' }}">{{ $link['label'] }}</a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="bp-nav-spacer"></div>

            <div class="bp-nav-footer">
                @if ($tenantLojas->count() > 1)
                    <div class="bp-nav-store-switch">
                        <p class="bp-nav-section-title">Loja ativa</p>
                        <form method="POST" action="{{ route('lojas.switch') }}" style="display:block;" id="js-loja-switch-form">
                            @csrf
                            <select
                                name="loja_id"
                                onchange="this.form.submit()"
                                style="width: 100%; min-width: 0; background: #111827; border-color: #374151; color: #f8fafc;"
                            >
                                @foreach ($tenantLojas as $loja)
                                    <option value="{{ $loja->id }}" @selected($tenantLojaAtual && $tenantLojaAtual->id === $loja->id)>
                                        {{ $loja->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                @endif

                <form method="POST" action="{{ route('logout') }}" class="bp-nav-logout-form">
                    @csrf
                    <button type="submit" class="bp-nav-logout">Sair</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
(() => {
    const btn = document.getElementById('bp-hamburger-btn');
    const sidebar = document.getElementById('bp-sidebar');
    const backdrop = document.getElementById('bp-drawer-backdrop');
    if (!btn || !sidebar || !backdrop) return;

    function openMenu() {
        sidebar.classList.add('open');
        backdrop.classList.add('open');
        btn.classList.add('open');
        btn.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
    }

    function closeMenu() {
        sidebar.classList.remove('open');
        backdrop.classList.remove('open');
        btn.classList.remove('open');
        btn.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    }

    btn.addEventListener('click', () => {
        sidebar.classList.contains('open') ? closeMenu() : openMenu();
    });

    backdrop.addEventListener('click', closeMenu);

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeMenu();
    });
})();
</script>
