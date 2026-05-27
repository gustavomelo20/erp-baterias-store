@props([
    'title',
    'subtitle' => null,
    'active' => null,
])

<div class="bp-topbar">
    <div class="bp-nav-wrap">
        <div class="bp-nav">
            <div>
                <h1 class="bp-nav-title">{{ $title }}</h1>
                @if ($tenantEmpresa && $tenantLojaAtual)
                    <p class="bp-nav-subtitle mb-0">{{ $tenantEmpresa->nome }} - Loja: {{ $tenantLojaAtual->nome }}</p>
                @endif
                @if ($subtitle)
                    <p class="bp-nav-subtitle">{{ $subtitle }}</p>
                @endif
            </div>
            <div class="bp-nav-actions">
                @if ($tenantLojas->count() > 1)
                    <form method="POST" action="{{ route('lojas.switch') }}" style="display:inline-flex;" class="js-loja-switch-form" data-require-password="{{ auth()->user()?->troca_loja_senha ? '1' : '0' }}">
                        @csrf
                        <select
                            name="loja_id"
                            data-current-loja-id="{{ $tenantLojaAtual?->id }}"
                            class="form-select form-select-sm"
                            onchange="window.handleLojaSwitchChange && window.handleLojaSwitchChange(this)"
                            style="min-width: 180px; background: #111827; border-color: #374151; color: #f8fafc;"
                        >
                            @foreach ($tenantLojas as $loja)
                                <option value="{{ $loja->id }}" @selected($tenantLojaAtual && $tenantLojaAtual->id === $loja->id)>
                                    {{ $loja->nome }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                @endif
                <a href="{{ route('welcome') }}" class="bp-nav-link {{ $active === 'pdv' ? 'active' : '' }}">PDV</a>
                <a href="{{ route('painel.index') }}" class="bp-nav-link {{ $active === 'painel' ? 'active' : '' }}">Painel</a>
                <a href="{{ route('estoque.index') }}" class="bp-nav-link {{ $active === 'estoque' ? 'active' : '' }}">Estoque</a>
                <a href="{{ route('configuracoes.index') }}" class="bp-nav-link {{ $active === 'configuracoes' ? 'active' : '' }}">Configurações</a>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
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
