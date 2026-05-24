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
                    <form method="POST" action="{{ route('lojas.switch') }}" style="display:inline-flex;">
                        @csrf
                        <select
                            name="loja_id"
                            class="form-select form-select-sm"
                            onchange="this.form.submit()"
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
