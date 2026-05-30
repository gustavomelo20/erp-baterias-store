<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Novo Usuário | Baterias ERP</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --bg: #f3f4f6;
            --card: #ffffff;
            --text: #0f172a;
            --muted: #64748b;
            --navy: #081c33;
            --gold: #fbbf24;
            --border: #dbe3ee;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        .wrapper {
            max-width: 860px;
            margin: 0 auto;
            padding: 1.5rem 1rem 2rem;
        }

        .card {
            background: var(--card);
            border-radius: 1rem;
            border: 1px solid rgba(8, 28, 51, 0.08);
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.08);
            padding: 1.4rem;
        }

        h2 {
            margin: 0 0 .35rem;
            color: var(--navy);
            font-size: 1.35rem;
        }

        .muted {
            margin: 0 0 1.2rem;
            color: var(--muted);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .9rem;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: .35rem;
        }

        .field.full {
            grid-column: 1 / -1;
        }

        label {
            font-size: .84rem;
            font-weight: 700;
            color: var(--navy);
        }

        input {
            border: 1px solid var(--border);
            border-radius: .75rem;
            padding: .7rem .8rem;
            font: inherit;
        }

        .lojas {
            border: 1px solid var(--border);
            border-radius: .85rem;
            padding: .75rem;
            background: #fafcff;
        }

        .loja-item {
            display: flex;
            align-items: center;
            gap: .55rem;
            padding: .35rem .1rem;
            font-size: .95rem;
        }

        .actions {
            margin-top: 1.1rem;
            display: flex;
            gap: .6rem;
            flex-wrap: wrap;
        }

        .btn {
            border: none;
            border-radius: .75rem;
            padding: .7rem 1rem;
            text-decoration: none;
            font-weight: 700;
            cursor: pointer;
            font-size: .9rem;
        }

        .btn.primary {
            background: linear-gradient(135deg, var(--gold), #f59e0b);
            color: #1f2937;
        }

        .btn.secondary {
            background: #e5e7eb;
            color: #1f2937;
        }

        .errors {
            margin: 0 0 1rem;
            padding: .8rem .95rem;
            background: #fee2e2;
            border: 1px solid #fecaca;
            border-radius: .75rem;
            color: #991b1b;
        }

        .errors ul {
            margin: 0;
            padding-left: 1rem;
        }

        @media (max-width: 720px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<x-topbar title="Novo Usuário" active="configuracoes" />

<div class="wrapper">
    <section class="card">
        <h2>Cadastrar usuário da empresa</h2>
        <p class="muted">Esse usuário será criado dentro da sua empresa e com acesso apenas às lojas selecionadas.</p>

        @if ($errors->any())
            <div class="errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('usuarios.store') }}">
            @csrf
            <div class="grid">
                <div class="field">
                    <label for="name">Nome</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required>
                </div>

                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                </div>

                <div class="field">
                    <label for="password">Senha</label>
                    <input id="password" name="password" type="password" required>
                </div>

                <div class="field">
                    <label for="password_confirmation">Confirmar senha</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required>
                </div>

                <div class="field full">
                    <label>Lojas com acesso</label>
                    <div class="lojas">
                        @foreach ($lojas as $loja)
                            <label class="loja-item">
                                <input type="checkbox" name="lojas[]" value="{{ $loja->id }}" @checked(in_array($loja->id, old('lojas', [])))>
                                <span>{{ $loja->nome }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="actions">
                <button type="submit" class="btn primary">Criar usuário</button>
                <a href="{{ route('configuracoes.index') }}" class="btn secondary">Voltar</a>
            </div>
        </form>
    </section>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
