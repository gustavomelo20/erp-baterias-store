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

    .bp-nav-store-switch .form-select {
        min-height: 44px;
        border-radius: .85rem;
        padding: .7rem .9rem;
    }

    .bp-nav-logout-form {
        width: 100%;
        margin: 0;
    }

    @media (max-width: 960px) {
        body {
            padding-left: 0 !important;
        }

        .bp-topbar {
            position: static;
            width: 100%;
            height: auto;
            box-shadow: 0 14px 32px rgba(2, 6, 23, 0.14);
            border-right: none;
            border-bottom: 1px solid rgba(148, 163, 184, 0.18);
        }

        .bp-nav-wrap {
            padding: 1rem .75rem;
        }

        .bp-nav {
            min-height: auto;
        }

        .bp-nav-actions {
            flex-direction: row;
            flex-wrap: wrap;
        }

        .bp-nav-menu {
            margin: 0;
        }

        .bp-nav-link,
        .bp-nav-logout {
            width: 100%;
            min-width: 0;
            justify-content: flex-start;
        }

        .bp-nav-footer {
            margin-top: 0;
            padding-top: 0;
            border-top: none;
        }

        .bp-nav-spacer {
            display: none;
        }
    }

    @media (max-width: 640px) {
        .bp-nav-wrap {
            padding: .9rem;
        }

        .bp-nav-link,
        .bp-nav-logout,
        .js-loja-switch-form {
            width: 100%;
        }

        .bp-nav-actions {
            flex-direction: column;
        }

        .bp-nav-footer {
            width: 100%;
        }
    }
</style>

<div class="bp-topbar">
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
                        <form method="POST" action="{{ route('lojas.switch') }}" style="display:block;" class="js-loja-switch-form" data-require-password="{{ auth()->user()?->troca_loja_senha ? '1' : '0' }}">
                            @csrf
                            <select
                                name="loja_id"
                                data-current-loja-id="{{ $tenantLojaAtual?->id }}"
                                class="form-select form-select-sm"
                                onchange="window.handleLojaSwitchChange && window.handleLojaSwitchChange(this)"
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

<div id="modalSenhaLoja" style="display:none; position: fixed; inset: 0; background: rgba(15, 23, 42, .55); z-index: 1100; align-items: center; justify-content: center; padding: 1rem;">
    <div style="width: 100%; max-width: 420px; background: #fff; border-radius: .95rem; padding: 1.1rem; box-shadow: 0 18px 45px rgba(15, 23, 42, .25);">
        <h3 style="margin: 0 0 .45rem; font-size: 1.08rem; color: #0f172a;">Confirmar troca de loja</h3>
        <p style="margin: 0 0 .8rem; color: #64748b; font-size: .9rem;">Digite a senha de segurança para continuar.</p>
        <input id="inputSenhaLoja" type="password" placeholder="Senha de segurança" style="width: 100%; border: 1px solid #cbd5e1; border-radius: .65rem; padding: .62rem .7rem; margin-bottom: .8rem;">
        <div style="display: flex; justify-content: flex-end; gap: .55rem;">
            <button type="button" id="btnCancelarSenhaLoja" style="border: 1px solid #cbd5e1; background: #fff; border-radius: .6rem; padding: .52rem .75rem; cursor: pointer;">Cancelar</button>
            <button type="button" id="btnConfirmarSenhaLoja" style="border: none; background: linear-gradient(135deg,#fbbf24,#f59e0b); color: #111827; border-radius: .6rem; padding: .52rem .75rem; font-weight: 700; cursor: pointer;">Confirmar</button>
        </div>
    </div>
</div>

<script>
(() => {
    const modal = document.getElementById('modalSenhaLoja');
    const inputSenha = document.getElementById('inputSenhaLoja');
    const btnCancelar = document.getElementById('btnCancelarSenhaLoja');
    const btnConfirmar = document.getElementById('btnConfirmarSenhaLoja');
    if (!modal || !inputSenha || !btnCancelar || !btnConfirmar) {
        return;
    }

    let formPendente = null;
    let selectPendente = null;
    let lojaAnterior = null;

    function fecharModal() {
        modal.style.display = 'none';
        inputSenha.value = '';
        if (selectPendente && lojaAnterior) {
            selectPendente.value = lojaAnterior;
        }
        formPendente = null;
        selectPendente = null;
        lojaAnterior = null;
    }

    window.handleLojaSwitchChange = function (select) {
        const form = select.form;
        if (!form) {
            return;
        }

        const requirePassword = form.dataset.requirePassword === '1';
        const atual = String(select.dataset.currentLojaId || '');
        const escolhida = String(select.value || '');

        if (escolhida === atual) {
            return;
        }

        if (!requirePassword) {
            form.submit();
            return;
        }

        formPendente = form;
        selectPendente = select;
        lojaAnterior = atual;
        modal.style.display = 'flex';
        setTimeout(() => inputSenha.focus(), 30);
    };

    btnCancelar.addEventListener('click', fecharModal);
    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            fecharModal();
        }
    });

    btnConfirmar.addEventListener('click', () => {
        if (!formPendente) {
            return;
        }

        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'senha_loja';
        hidden.value = inputSenha.value;
        formPendente.appendChild(hidden);
        formPendente.submit();
    });
})();
</script>
