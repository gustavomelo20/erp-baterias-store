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
                @if ($subtitle)
                    <p class="bp-nav-subtitle">{{ $subtitle }}</p>
                @endif
            </div>
            <div class="bp-nav-actions">
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
