<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Criar Empresa | Baterias ERP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        :root {
            --bp-navy: #081c33;
            --bp-navy-2: #10284b;
            --bp-gold: #f2c300;
        }

        body {
            background: linear-gradient(135deg, var(--bp-navy) 0%, var(--bp-navy-2) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .register-card {
            max-width: 620px;
            width: 100%;
            background: white;
            border-radius: 1.25rem;
            box-shadow: 0 20px 60px rgba(8, 28, 51, 0.3);
            padding: 2rem;
        }

        .form-label {
            color: var(--bp-navy);
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 0.45rem;
        }

        .form-control {
            border-radius: 0.85rem;
            border: 1px solid rgba(8, 28, 51, 0.16);
            padding: 0.8rem 1rem;
        }

        .form-control:focus {
            border-color: var(--bp-gold);
            box-shadow: 0 0 0 0.25rem rgba(242, 195, 0, 0.18);
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--bp-gold) 0%, #ffb300 100%);
            color: var(--bp-navy);
            border: none;
            font-weight: 800;
            border-radius: 0.9rem;
            padding: 0.9rem;
            width: 100%;
        }

        .alert-danger ul {
            margin-bottom: 0;
            padding-left: 1.2rem;
        }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="mb-4">
            <h1 class="h3 fw-bold" style="color: var(--bp-navy);">Criar conta da empresa</h1>
            <p class="text-secondary mb-0">Cadastre sua empresa, loja inicial e usuário administrador.</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('empresa.store') }}">
            @csrf

            <div class="row g-3 mb-2">
                <div class="col-12 col-md-7">
                    <label for="empresa_nome" class="form-label">Nome da empresa</label>
                    <input id="empresa_nome" type="text" name="empresa_nome" class="form-control" value="{{ old('empresa_nome') }}" required>
                </div>
                <div class="col-12 col-md-5">
                    <label for="loja_nome" class="form-label">Nome da loja inicial</label>
                    <input id="loja_nome" type="text" name="loja_nome" class="form-control" value="{{ old('loja_nome', 'Loja Matriz') }}" required>
                </div>
            </div>

            <div class="row g-3 mb-2">
                <div class="col-12 col-md-6">
                    <label for="name" class="form-label">Nome do administrador</label>
                    <input id="name" type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="col-12 col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <label for="password" class="form-label">Senha</label>
                    <input id="password" type="password" name="password" class="form-control" required>
                </div>
                <div class="col-12 col-md-6">
                    <label for="password_confirmation" class="form-label">Confirmar senha</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
                </div>
            </div>

            <button type="submit" class="btn btn-submit mt-4">Criar empresa e entrar</button>
        </form>

        <div class="mt-3 text-center">
            <a href="{{ route('login') }}" class="text-decoration-none">Ja tenho conta</a>
        </div>
    </div>
</body>
</html>
