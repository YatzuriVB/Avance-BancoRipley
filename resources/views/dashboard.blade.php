<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Banco Ripley</title>
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
        .nav-logout { display: flex; align-items: center; gap: 10px; color: rgba(255,255,255,0.5); font-size: 13px; cursor: pointer; text-decoration: none; }
        .nav-logout:hover { color: #fff; }
        .main { margin-left: 220px; flex: 1; padding: 28px; }
        .topbar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; }
        .topbar-title { font-size: 20px; font-weight: 700; color: #1a1a2e; }
        .topbar-date { font-size: 13px; color: #888; margin-top: 2px; }
        .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 20px; }
        .stat-card { background: #fff; border-radius: 12px; border: 1px solid #e8e0f0; padding: 16px 18px; }
        .stat-label { font-size: 12px; color: #888; margin-bottom: 6px; display: flex; align-items: center; gap: 6px; }
        .stat-label i { font-size: 14px; color: #5B2D8E; }
        .stat-val { font-size: 22px; font-weight: 700; color: #1a1a2e; }
        .stat-sub { font-size: 11px; color: #27a65a; margin-top: 4px; }
        .stat-sub.red { color: #E30613; }
        .quick-actions { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 20px; }
        .qa { background: #fff; border: 1px solid #e8e0f0; border-radius: 12px; padding: 16px 10px; text-align: center; cursor: pointer; text-decoration: none; display: block; }
        .qa:hover { background: #f0e8ff; border-color: #5B2D8E; }
        .qa i { font-size: 24px; color: #5B2D8E; display: block; margin-bottom: 8px; }
        .qa span { font-size: 12px; color: #444; font-weight: 500; }
        .grid2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .card { background: #fff; border-radius: 12px; border: 1px solid #e8e0f0; padding: 18px; }
        .card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
        .card-title { font-size: 14px; font-weight: 700; color: #1a1a2e; }
        .card-link { font-size: 12px; color: #5B2D8E; text-decoration: none; }
        .mov-item { display: flex; align-items: center; gap: 12px; padding: 8px 0; border-bottom: 1px solid #f5f0ff; }
        .mov-item:last-child { border-bottom: none; }
        .mov-icon { width: 34px; height: 34px; background: #f0e8ff; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .mov-icon i { font-size: 16px; color: #5B2D8E; }
        .mov-info { flex: 1; }
        .mov-name { font-size: 13px; font-weight: 500; color: #1a1a2e; }
        .mov-date { font-size: 11px; color: #888; }
        .mov-amount { font-size: 13px; font-weight: 700; }
        .mov-amount.green { color: #27a65a; }
        .mov-amount.red { color: #E30613; }
        .credit-item { padding: 10px 0; border-bottom: 1px solid #f5f0ff; }
        .credit-item:last-child { border-bottom: none; }
        .credit-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; }
        .credit-name { font-size: 13px; font-weight: 500; color: #1a1a2e; }
        .credit-badge { font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 20px; background: #e8f5ee; color: #27a65a; }
        .credit-badge.amber { background: #faeeda; color: #ba7517; }
        .progress-bar { height: 5px; background: #f0e8ff; border-radius: 3px; overflow: hidden; }
        .progress-fill { height: 100%; background: #5B2D8E; border-radius: 3px; }
        .credit-sub { display: flex; justify-content: space-between; margin-top: 5px; }
        .credit-sub span { font-size: 11px; color: #888; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-logo">
        <div class="sidebar-logo-text">banco ripley<span>.</span></div>
    </div>
    <div class="sidebar-user">
        <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
        <div>
            <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
            <div class="sidebar-user-sub">Cliente</div>
        </div>
    </div>
    <div class="nav-section">
        <div class="nav-label">Principal</div>
        <a href="{{ route('dashboard') }}" class="nav-item active"><i class="ti ti-layout-dashboard"></i> Dashboard</a>
        <a href="#" class="nav-item"><i class="ti ti-building-bank"></i> Mis cuentas</a>
        <a href="#" class="nav-item"><i class="ti ti-arrows-exchange"></i> Transferencias</a>
        <a href="#" class="nav-item"><i class="ti ti-receipt"></i> Pagos</a>
    </div>
    <div class="nav-section">
        <div class="nav-label">Productos</div>
        <a href="#" class="nav-item"><i class="ti ti-piggy-bank"></i> Ahorros</a>
        <a href="#" class="nav-item"><i class="ti ti-credit-card"></i> Créditos</a>
        <a href="#" class="nav-item"><i class="ti ti-chart-line"></i> Inversiones</a>
    </div>
    <div class="nav-section">
        <div class="nav-label">Mi cuenta</div>
        <a href="#" class="nav-item"><i class="ti ti-user"></i> Perfil</a>
        <a href="#" class="nav-item"><i class="ti ti-settings"></i> Configuración</a>
    </div>
    <div class="sidebar-bottom">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-logout">
                <i class="ti ti-logout"></i> Cerrar sesión
            </button>
        </form>
    </div>
</div>

<div class="main">
    <div class="topbar">
        <div>
            <div class="topbar-title">Bienvenida, {{ explode(' ', auth()->user()->name)[0] }}</div>
            <div class="topbar-date">{{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</div>
        </div>
    </div>

    <div class="stats">
        <div class="stat-card">
            <div class="stat-label"><i class="ti ti-wallet"></i> Saldo total</div>
            <div class="stat-val">S/ 4,250.00</div>
            <div class="stat-sub">↑ +S/ 200 este mes</div>
        </div>
        <div class="stat-card">
            <div class="stat-label"><i class="ti ti-credit-card"></i> Próxima cuota</div>
            <div class="stat-val">S/ 946.40</div>
            <div class="stat-sub red">Vence el 15/06/2026</div>
        </div>
        <div class="stat-card">
            <div class="stat-label"><i class="ti ti-arrows-exchange"></i> Transferencias</div>
            <div class="stat-val">8</div>
            <div class="stat-sub">Este mes</div>
        </div>
        <div class="stat-card">
            <div class="stat-label"><i class="ti ti-piggy-bank"></i> Ahorros</div>
            <div class="stat-val">S/ 1,800.00</div>
            <div class="stat-sub">↑ TEA 3.5%</div>
        </div>
    </div>

    <div class="quick-actions">
        <a href="#" class="qa"><i class="ti ti-send"></i><span>Transferir</span></a>
        <a href="#" class="qa"><i class="ti ti-file-invoice"></i><span>Pagar servicio</span></a>
        <a href="#" class="qa"><i class="ti ti-calculator"></i><span>Simular crédito</span></a>
        <a href="#" class="qa"><i class="ti ti-download"></i><span>Estado de cuenta</span></a>
    </div>

    <div class="grid2">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Últimos movimientos</div>
                <a href="#" class="card-link">Ver todos →</a>
            </div>
            <div class="mov-item">
                <div class="mov-icon"><i class="ti ti-shopping-cart"></i></div>
                <div class="mov-info"><div class="mov-name">Compra Ripley</div><div class="mov-date">Hoy, 10:24 am</div></div>
                <div class="mov-amount red">-S/ 150.00</div>
            </div>
            <div class="mov-item">
                <div class="mov-icon"><i class="ti ti-building"></i></div>
                <div class="mov-info"><div class="mov-name">Depósito sueldo</div><div class="mov-date">Ayer, 08:00 am</div></div>
                <div class="mov-amount green">+S/ 2,500.00</div>
            </div>
            <div class="mov-item">
                <div class="mov-icon"><i class="ti ti-send"></i></div>
                <div class="mov-info"><div class="mov-name">Transferencia BCP</div><div class="mov-date">10/05, 03:15 pm</div></div>
                <div class="mov-amount red">-S/ 200.00</div>
            </div>
            <div class="mov-item">
                <div class="mov-icon"><i class="ti ti-receipt"></i></div>
                <div class="mov-info"><div class="mov-name">Pago Claro</div><div class="mov-date">09/05, 11:00 am</div></div>
                <div class="mov-amount red">-S/ 89.90</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-title">Mis créditos</div>
                <a href="#" class="card-link">Ver todos →</a>
            </div>
            <div class="credit-item">
                <div class="credit-top">
                    <div class="credit-name">Préstamo personal</div>
                    <div class="credit-badge">Al día</div>
                </div>
                <div class="progress-bar"><div class="progress-fill" style="width:65%"></div></div>
                <div class="credit-sub"><span>S/ 6,500 pagado</span><span>S/ 3,500 restante</span></div>
            </div>
            <div class="credit-item">
                <div class="credit-top">
                    <div class="credit-name">Tarjeta Ripley</div>
                    <div class="credit-badge amber">Por vencer</div>
                </div>
                <div class="progress-bar"><div class="progress-fill" style="width:40%"></div></div>
                <div class="credit-sub"><span>S/ 946.40 usado</span><span>S/ 1,400 disponible</span></div>
            </div>
        </div>
    </div>
</div>

</body>
</html>