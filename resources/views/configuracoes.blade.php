<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Configurações | Baterias ERP</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f3f4f6;
            --nav-bg: #1f2937;
            --text: #0f172a;
            --muted: #64748b;
            --card: #ffffff;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
            padding-top: 86px;
        }

        .bp-topbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            z-index: 1030;
            background: var(--nav-bg);
            border-bottom: 1px solid rgba(148, 163, 184, 0.22);
            box-shadow: 0 12px 26px rgba(2, 6, 23, 0.35);
        }
        .bp-nav-wrap { max-width: 1200px; margin: 0 auto; padding: .7rem 1rem; }
        .bp-nav { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: .8rem; }
        .bp-nav-title { font-size: 1.15rem; font-weight: 800; line-height: 1.2; margin: 0; color: #f8fafc; }
        .bp-nav-subtitle { color: #cbd5e1; margin: .3rem 0 0; font-size: .88rem; }
        .bp-nav-actions { display: flex; gap: .6rem; flex-wrap: wrap; }
        .bp-nav-link {
            color: #e2e8f0;
            text-decoration: none;
            font-weight: 600;
            padding: .2rem .1rem;
            border-bottom: 2px solid transparent;
            transition: color .15s ease, border-color .15s ease;
        }
        .bp-nav-link:hover { color: #fbbf24; border-bottom-color: #fbbf24; }
        .bp-nav-link.active { color: #fbbf24; border-bottom-color: #fbbf24; }
        .bp-nav-logout {
            background: transparent;
            border: none;
            color: #fecaca;
            font-weight: 600;
            padding: .2rem .1rem;
            border-bottom: 2px solid transparent;
            cursor: pointer;
        }
        .bp-nav-logout:hover { color: #fca5a5; border-bottom-color: #fca5a5; }

        .wrapper { max-width: 1200px; margin: 0 auto; padding: 2rem 1rem; }
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1rem;
        }
        .card {
            background: var(--card);
            border-radius: .9rem;
            border: 1px solid rgba(8, 28, 51, 0.08);
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
            padding: 1.4rem;
        }
        .card.form-card {
            grid-column: 1 / -1;
        }
        .card.link {
            text-decoration: none;
            color: inherit;
            transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease;
        }
        .card.link:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.12);
            border-color: rgba(251, 191, 36, 0.55);
        }
        .title { margin: 0 0 .4rem; font-size: 1.25rem; }
        .muted { margin: 0; color: var(--muted); }
        .arrow {
            margin-top: .8rem;
            color: #b45309;
            font-weight: 700;
            font-size: .9rem;
        }
        .section-title {
            margin: 0 0 .8rem;
            font-size: 1.05rem;
            color: #0b2441;
        }
        .grid-form {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: .8rem;
        }
        .field {
            display: flex;
            flex-direction: column;
            gap: .35rem;
        }
        .field label {
            font-size: .8rem;
            font-weight: 700;
            color: #1e3a5f;
        }
        .field input,
        .field select {
            border: 1px solid #d4deea;
            border-radius: .65rem;
            padding: .62rem .68rem;
            font: inherit;
            background: #fff;
        }
        .col-1 { grid-column: span 1; }
        .col-2 { grid-column: span 2; }
        .col-3 { grid-column: span 3; }
        .col-4 { grid-column: span 4; }
        .divider {
            border-top: 1px solid #e2e8f0;
            margin: 1rem 0;
        }
        .actions {
            margin-top: 1rem;
            display: flex;
            justify-content: flex-end;
        }
        .actions.left {
            justify-content: flex-start;
        }
        .btn-save {
            border: none;
            border-radius: .7rem;
            padding: .72rem 1rem;
            font-weight: 700;
            color: #111827;
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            cursor: pointer;
        }
        .errors {
            margin-bottom: 1rem;
            padding: .8rem .95rem;
            border-radius: .75rem;
            border: 1px solid #fecaca;
            background: #fef2f2;
            color: #991b1b;
        }
        .errors ul {
            margin: 0;
            padding-left: 1rem;
        }
        @media (max-width: 900px) {
            .grid-form { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .col-3, .col-4 { grid-column: span 2; }
        }
        @media (max-width: 640px) {
            .grid-form { grid-template-columns: 1fr; }
            .col-1, .col-2, .col-3, .col-4 { grid-column: span 1; }
        }
    </style>
</head>
<body>
<x-topbar title="Configurações"  active="configuracoes" />

<div class="wrapper">
    @if (session('success'))
        <div class="card" style="margin-bottom: 1rem; border-color: rgba(16, 185, 129, .35);">
            <p class="muted" style="color: #047857; margin: 0; font-weight: 600;">{{ session('success') }}</p>
        </div>
    @endif

    <section class="cards">
        <article class="card form-card">
            <h2 class="title">Dados fiscais da empresa</h2>
            <p class="muted">Preencha esses dados para preparar a emissão de NF-e no futuro.</p>

            @if ($errors->any())
                <div class="errors">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('configuracoes.empresa.update') }}">
                @csrf
                @method('PUT')

                <h3 class="section-title">Identificação da empresa</h3>
                <div class="grid-form">
                    <div class="field col-2">
                        <label for="razao_social">Razão social</label>
                        <input id="razao_social" name="razao_social" type="text" value="{{ old('razao_social', $empresa->razao_social) }}" required>
                    </div>
                    <div class="field col-2">
                        <label for="nome_fantasia">Nome fantasia</label>
                        <input id="nome_fantasia" name="nome_fantasia" type="text" value="{{ old('nome_fantasia', $empresa->nome_fantasia) }}">
                    </div>

                    <div class="field col-1">
                        <label for="cnpj">CNPJ</label>
                        <input id="cnpj" name="cnpj" type="text" value="{{ old('cnpj', $empresa->cnpj) }}" placeholder="00.000.000/0000-00" required>
                    </div>
                    <div class="field col-1">
                        <label for="inscricao_estadual">Inscrição estadual</label>
                        <input id="inscricao_estadual" name="inscricao_estadual" type="text" value="{{ old('inscricao_estadual', $empresa->inscricao_estadual) }}">
                    </div>
                    <div class="field col-1">
                        <label for="inscricao_municipal">Inscrição municipal</label>
                        <input id="inscricao_municipal" name="inscricao_municipal" type="text" value="{{ old('inscricao_municipal', $empresa->inscricao_municipal) }}">
                    </div>
                    <div class="field col-1">
                        <label for="regime_tributario">Regime tributário</label>
                        <select id="regime_tributario" name="regime_tributario" required>
                            <option value="simples" @selected(old('regime_tributario', $empresa->regime_tributario) === 'simples')>Simples Nacional</option>
                            <option value="presumido" @selected(old('regime_tributario', $empresa->regime_tributario) === 'presumido')>Lucro Presumido</option>
                            <option value="real" @selected(old('regime_tributario', $empresa->regime_tributario) === 'real')>Lucro Real</option>
                        </select>
                    </div>

                    <div class="field col-2">
                        <label for="cnae_principal">CNAE principal</label>
                        <input id="cnae_principal" name="cnae_principal" type="text" value="{{ old('cnae_principal', $empresa->cnae_principal) }}" placeholder="4530-7/03">
                    </div>
                    <div class="field col-1">
                        <label for="email_fiscal">Email fiscal</label>
                        <input id="email_fiscal" name="email_fiscal" type="email" value="{{ old('email_fiscal', $empresa->email_fiscal) }}">
                    </div>
                    <div class="field col-1">
                        <label for="telefone">Telefone</label>
                        <input id="telefone" name="telefone" type="text" value="{{ old('telefone', $empresa->telefone) }}">
                    </div>
                </div>

                <div class="divider"></div>

                <h3 class="section-title">Endereço fiscal</h3>
                <div class="grid-form">
                    <div class="field col-1">
                        <label for="cep">CEP</label>
                        <input id="cep" name="cep" type="text" value="{{ old('cep', $empresa->cep) }}" placeholder="00000-000">
                    </div>
                    <div class="field col-2">
                        <label for="logradouro">Logradouro</label>
                        <input id="logradouro" name="logradouro" type="text" value="{{ old('logradouro', $empresa->logradouro) }}">
                    </div>
                    <div class="field col-1">
                        <label for="numero">Número</label>
                        <input id="numero" name="numero" type="text" value="{{ old('numero', $empresa->numero) }}">
                    </div>

                    <div class="field col-2">
                        <label for="complemento">Complemento</label>
                        <input id="complemento" name="complemento" type="text" value="{{ old('complemento', $empresa->complemento) }}">
                    </div>
                    <div class="field col-1">
                        <label for="bairro">Bairro</label>
                        <input id="bairro" name="bairro" type="text" value="{{ old('bairro', $empresa->bairro) }}">
                    </div>
                    <div class="field col-1">
                        <label for="cidade">Cidade</label>
                        <input id="cidade" name="cidade" type="text" value="{{ old('cidade', $empresa->cidade) }}">
                    </div>

                    <div class="field col-1">
                        <label for="uf">UF</label>
                        <input id="uf" name="uf" type="text" value="{{ old('uf', $empresa->uf) }}" maxlength="2">
                    </div>
                    <div class="field col-1">
                        <label for="codigo_municipio_ibge">Código IBGE do município</label>
                        <input id="codigo_municipio_ibge" name="codigo_municipio_ibge" type="text" value="{{ old('codigo_municipio_ibge', $empresa->codigo_municipio_ibge) }}">
                    </div>
                </div>

                <div class="actions">
                    <button type="submit" class="btn-save">Salvar dados fiscais</button>
                </div>
            </form>
        </article>

        <article class="card">
            <h2 class="title">Segurança de troca de loja</h2>
            <p class="muted">
                @if (auth()->user()?->troca_loja_senha)
                    A senha de segurança está ativa. Ela será pedida ao trocar de loja.
                @else
                    Nenhuma senha de segurança cadastrada. A troca de loja acontece sem confirmação.
                @endif
            </p>

            <form method="POST" action="{{ route('configuracoes.seguranca-loja.update') }}" style="margin-top: 1rem;">
                @csrf
                @method('PUT')
                <div class="grid-form">
                    <div class="field col-2">
                        <label for="senha_troca_loja">Nova senha de segurança</label>
                        <input id="senha_troca_loja" name="senha_troca_loja" type="password" minlength="6" placeholder="Minimo 6 caracteres">
                    </div>
                    <div class="field col-2">
                        <label for="senha_troca_loja_confirmation">Confirmar senha</label>
                        <input id="senha_troca_loja_confirmation" name="senha_troca_loja_confirmation" type="password" minlength="6">
                    </div>
                </div>
                <p class="muted" style="margin-top: .7rem; font-size: .85rem;">Para remover a senha, deixe os dois campos vazios e clique em salvar.</p>
                <div class="actions left">
                    <button type="submit" class="btn-save">Salvar segurança da loja</button>
                </div>
            </form>
        </article>

        <a href="{{ route('usuarios.create') }}" class="card link">
            <h2 class="title">Cadastrar usuário da empresa</h2>
            <p class="muted">Crie um usuário novo já atrelado à sua empresa e selecione quais lojas ele pode acessar.</p>
            <div class="arrow">Abrir cadastro de usuário →</div>
        </a>
    </section>
</div>
</body>
</html>
