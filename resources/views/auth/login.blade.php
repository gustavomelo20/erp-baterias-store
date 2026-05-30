<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Baterias ERP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        :root {
            --bp-navy: #081c33;
            --bp-navy-2: #10284b;
            --bp-gold: #f2c300;
            --bp-orange: #ff7a00;
            --bp-bg: #f5f7fb;
        }

        body {
            background: linear-gradient(135deg, var(--bp-navy) 0%, var(--bp-navy-2) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            max-width: 400px;
            width: 100%;
            background: white;
            border-radius: 1.5rem;
            box-shadow: 0 20px 60px rgba(8, 28, 51, 0.3);
            padding: 3rem 2rem;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .login-header h1 {
            color: var(--bp-navy);
            font-weight: 900;
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: #64748b;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            color: var(--bp-navy);
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border-radius: 0.95rem;
            border: 1px solid rgba(8, 28, 51, 0.16);
            padding: 0.9rem 1rem;
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: var(--bp-gold);
            box-shadow: 0 0 0 0.25rem rgba(242, 195, 0, 0.18);
        }

        .btn-login {
            background: linear-gradient(135deg, var(--bp-gold) 0%, #ffb300 100%);
            color: var(--bp-navy);
            border: none;
            font-weight: 800;
            border-radius: 0.95rem;
            padding: 0.9rem;
            width: 100%;
            box-shadow: 0 10px 24px rgba(242, 195, 0, 0.25);
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #ffd426 0%, var(--bp-gold) 100%);
            color: var(--bp-navy);
        }

        .alert-error {
            background: rgba(192, 57, 43, 0.12);
            color: #8f2419;
            border: 1px solid rgba(192, 57, 43, 0.18);
            border-radius: 0.95rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <h1>Baterias ERP</h1>
            <p>Acesso ao painel de controle</p>
        </div>

        @if ($errors->any())
            <div class="alert-error">
                {{ $errors->first('email') }}
            </div>
        @endif

        <form method="POST" action="{{ route('auth.login') }}">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    class="form-control"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    placeholder="seu@email.com"
                >
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Senha</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="form-control"
                    required
                    placeholder="••••••••"
                >
            </div>

            <button type="submit" class="btn btn-login">Entrar</button>
        </form>

        <div class="mt-4 pt-3 border-top text-center">
            <a href="{{ route('empresa.create') }}" class="d-inline-block mt-3 text-decoration-none fw-semibold" style="color: var(--bp-navy);">
                Criar conta da empresa
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
