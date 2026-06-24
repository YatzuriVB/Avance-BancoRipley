<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Créditos — Banco Ripley</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f7f5fb; display: flex; min-height: 100vh; }
        .sidebar { width: 220px; background: #5B2D8E; display: flex; flex-direction: column; position: fixed; top: 0; left: 0; height: 100vh; }
        .sidebar-logo { padding: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-logo-text { color: #fff; font-size: 16px; font-weight: 700; }
        .sidebar-logo-text span { color: #E30613; }
        .sidebar-user { padding: 14px 20px; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 10px; }
        .avatar { width: 34px; height: 34px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 12px; font-weight: 700; flex-shrink: 0; }
        .sidebar-user-name { color: #fff; font-size: 13px; font-weight: 500; }
        .sidebar-user-sub { color: rgba(255,255,255,0.5); font-size: 11px; }
        .nav-section { padding: 12px 0 4px; }
        .nav-label { color: rgba(255,255,255,0.35); font-size: 10px; font-weight: 600; letter-spacing: .06em; padding: 0 20px 6px; text-transform: uppercase; }
        .nav-item { display: flex; align-items: center; gap: 10px; padding: 9px 20px; color: rgba(255,255,255,0.65); font-size: 13px; cursor: pointer; text-decoration: none; }
        .nav-item:hover { background: rgba(255,255,255,0.08); color: #fff; }
        .nav-item.active { background: rgba(255,255,255,0.12); color: #fff; }
        .nav-item i { font-size: 16px; }
        .sidebar-bottom { margin-top: auto; padding: 16px 20px; border-top: 1px solid rgba(255,255,255,0.1); }
        .nav-logout { display: flex; align-items: center; gap: 10px; color: rgba(255,255,255,0.5); font-size: 13px; cursor: pointer; text-decoration: none; background: none; border: none; width: 100%; }
        .nav-logout:hover { color: #fff; }

        .main { margin-left: 220px; flex: 1; padding: 28px; }
        .topbar { margin-bottom: 24px; }
        .topbar-title { font-size: 22px; font-weight: 700; color: #1a1a2e; }
        .topbar-sub { font-size: 13px; color: #888; margin-top: 4px; }

        .section { background: #fff; border-radius: 12px; border: 1px solid #e8e0f0; padding: 20px 24px; }
        .account-item { display: flex; align-items: center; justify-content: space-between; padding: 16px 0; border-bottom: 1px solid #f5f0ff; text-decoration: none; }
        .account-item:last-child { border-bottom: none; }
        .account-left { display: flex; align-items: center; gap: 14px; }
        .account-icon { width: 42px; height: 42px; background: #f0e8ff; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .account-icon i { font-size: 20px; color: #5B2D8E; }
        .account-code { font-size: 15px; font-weight: 600; color: #1a1a2e; }
        .account-type { font-size: 12px; color: #888; margin-top: 3px; }
        .account-badge { font-size: 10px; font-weight: 600; padding: 3px 10px; border-radius: 20px; background: #e8f5ee; color: #27a65a; margin-top: 6px; display: inline-block; }
        .account-badge.amber { background: #faeeda; color: #ba7517; }
        .account-amount { text-align: right; }
        .account-saldo { font-size: 18px; font-weight: 700; color: #E30613; }
        .account-arrow { font-size: 18px; color: #ccc; margin-left: 12px; }
        .empty-state { text-align: center; padding: 40px 0; color: #888; font-size: 14px; }
    </style>
</head>
<body>

@include('partials.sidebar')

<div class="main">
    <div class="topbar">
        <div class="topbar-title">Mis Créditos</div>
        <div class="topbar-sub">Todos tus préstamos activos en Banco Ripley</div>
    </div>

    <div class="section">
        @forelse($creditos as $credito)
        <a href="{{ route('credito.show', $credito->pkcuentacredito) }}" class="account-item">
            <div class="account-left">
                <div class="account-icon"><i class="ti ti-file-invoice"></i></div>
                <div>
                    <div class="account-code">{{ $credito->codcuentacredito }}</div>
                    <div class="account-type">Crédito de consumo</div>
                    <span class="account-badge {{ $credito->pkestadocredito == 1 ? '' : 'amber' }}">
                        {{ $credito->desestadocredito ?? 'Vigente' }}
                    </span>
                </div>
            </div>
            <div style="display:flex;align-items:center;">
                <div class="account-amount">
                    <div class="account-saldo">S/ {{ number_format($credito->montoaprobadocredito - $credito->montocapitalpagado, 2) }}</div>
                </div>
                <i class="ti ti-chevron-right account-arrow"></i>
            </div>
        </a>
        @empty
        <div class="empty-state">No tienes créditos registrados</div>
        @endforelse
    </div>
</div>

</body>
</html>