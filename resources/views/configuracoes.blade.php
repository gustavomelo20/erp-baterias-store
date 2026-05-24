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
        .card {
            background: var(--card);
            border-radius: .9rem;
            border: 1px solid rgba(8, 28, 51, 0.08);
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
            padding: 1.4rem;
        }
        .title { margin: 0 0 .4rem; font-size: 1.25rem; }
        .muted { margin: 0; color: var(--muted); }
    </style>
</head>
<body>
<x-topbar title="Configurações"  active="configuracoes" />

<div class="wrapper">
    <section class="card">
        <h2 class="title">Página de Configurações</h2>
        <p class="muted">Estrutura inicial criada. Se quiser, eu já implemento as opções que você precisa aqui.</p>
    </section>
</div>
</body>
</html>
