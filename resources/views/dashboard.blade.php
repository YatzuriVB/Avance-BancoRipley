<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Banco Ripley</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f7f5fb; display: flex; min-height: 100vh; }

        /* SIDEBAR */
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

        /* MAIN */
        .main { margin-left: 220px; flex: 1; padding: 28px; }
        .topbar { margin-bottom: 24px; }
        .topbar-greeting { font-size: 22px; font-weight: 700; color: #1a1a2e; }
        .topbar-sub { font-size: 13px; color: #888; margin-top: 4px; }

        /* RESUMEN SUPERIOR */
        .summary-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px; }
        .summary-card { background: #fff; border-radius: 12px; border: 1px solid #e8e0f0; padding: 20px 24px; display: flex; align-items: center; gap: 16px; }
        .summary-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .summary-icon.green { background: #e8f5ee; }
        .summary-icon.purple { background: #f0e8ff; }
        .summary-icon i { font-size: 22px; }
        .summary-icon.green i { color: #27a65a; }
        .summary-icon.purple i { color: #5B2D8E; }
        .summary-label { font-size: 12px; color: #888; margin-bottom: 4px; }
        .summary-val { font-size: 24px; font-weight: 700; color: #1a1a2e; }
        .summary-sub { font-size: 12px; color: #888; margin-top: 2px; }

        /* ACCIONES RÁPIDAS */
        .quick-actions { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 24px; }
        .qa { background: #fff; border: 1px solid #e8e0f0; border-radius: 12px; padding: 16px 10px; text-align: center; cursor: pointer; text-decoration: none; display: block; transition: all .15s; }
        .qa:hover { background: #f0e8ff; border-color: #5B2D8E; }
        .qa i { font-size: 24px; color: #5B2D8E; display: block; margin-bottom: 8px; }
        .qa span { font-size: 12px; color: #444; font-weight: 500; }

        /* SECCIONES */
        .section { background: #fff; border-radius: 12px; border: 1px solid #e8e0f0; padding: 20px 24px; margin-bottom: 16px; }
        .section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
        .section-title { font-size: 15px; font-weight: 700; color: #1a1a2e; }
        .section-link { font-size: 12px; color: #5B2D8E; text-decoration: none; }

        /* ITEMS DE CUENTA */
        .account-item { display: flex; align-items: center; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f5f0ff; }
        .account-item:last-child { border-bottom: none; }
        .account-left { display: flex; align-items: center; gap: 12px; }
        .account-icon { width: 36px; height: 36px; background: #f0e8ff; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .account-icon i { font-size: 18px; color: #5B2D8E; }
        .account-code { font-size: 14px; font-weight: 600; color: #1a1a2e; }
        .account-type { font-size: 12px; color: #888; margin-top: 2px; }
        .account-badge { font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 20px; background: #e8f5ee; color: #27a65a; margin-top: 4px; display: inline-block; }
        .account-badge.red { background: #fde8e8; color: #E30613; }
        .account-badge.amber { background: #faeeda; color: #ba7517; }
        .account-amount { text-align: right; }
        .account-saldo { font-size: 16px; font-weight: 700; color: #1a1a2e; }
        .account-saldo.red { color: #E30613; }
        .account-arrow { font-size: 16px; color: #ccc; margin-left: 8px; }

        /* TOTAL */
        .total-row { display: flex; justify-content: space-between; align-items: center; padding-top: 12px; border-top: 1px solid #e8e0f0; margin-top: 4px; }
        .total-label { font-size: 13px; color: #888; }
        .total-val { font-size: 16px; font-weight: 700; color: #E30613; }

        /* PRÓXIMA CUOTA */
        .cuota-card { background: linear-gradient(135deg, #5B2D8E, #4a2275); border-radius: 12px; padding: 20px 24px; color: #fff; margin-bottom: 16px; }
        .cuota-label { font-size: 12px; color: rgba(255,255,255,0.7); margin-bottom: 6px; }
        .cuota-val { font-size: 28px; font-weight: 700; }
        .cuota-sub { font-size: 12px; color: rgba(255,255,255,0.7); margin-top: 6px; }
        .cuota-btn { margin-top: 14px; background: #fff; color: #5B2D8E; border: none; border-radius: 8px; padding: 8px 20px; font-size: 13px; font-weight: 700; cursor: pointer; }
    </style>
</head>
<body>

@include('partials.sidebar')

<div class="main">
    <div class="topbar">
        <div class="topbar-greeting">Hola, {{ explode(',', $cliente->nomcliente ?? 'Cliente')[0] }}</div>
        <div class="topbar-sub">Esta es la posición global de tus productos en Banco Ripley.</div>
    </div>

    <!-- Resumen -->
    <div class="summary-row">
        <div class="summary-card">
            <div class="summary-icon green"><i class="ti ti-piggy-bank"></i></div>
            <div>
                <div class="summary-label">↗ TOTAL EN AHORROS</div>
                <div class="summary-val">S/ {{ number_format($saldoTotal, 2) }}</div>
                <div class="summary-sub">{{ $cuentas->count() }} cuenta(s)</div>
            </div>
        </div>
        <div class="summary-card">
            <div class="summary-icon purple"><i class="ti ti-credit-card"></i></div>
            <div>
                <div class="summary-label">↘ DEUDA TOTAL DE CRÉDITOS</div>
                <div class="summary-val">S/ {{ number_format($creditos->sum('montoaprobadocredito') - $creditos->sum('montocapitalpagado'), 2) }}</div>
                <div class="summary-sub">{{ $creditos->count() }} crédito(s)</div>
            </div>
        </div>
    </div>

    <!-- Acciones rápidas -->
    <div class="quick-actions">
        <a href="{{ route('transferencias') }}" class="qa"><i class="ti ti-send"></i><span>Transferencias propias</span></a>
        <a href="{{ route('pagos.credito') }}" class="qa"><i class="ti ti-credit-card"></i><span>Pago de crédito</span></a>
        <a href="{{ route('pagos.servicios') }}" class="qa"><i class="ti ti-file-invoice"></i><span>Pago de servicios</span></a>
        <a href="{{ route('credito.simulador') }}" class="qa"><i class="ti ti-calculator"></i><span>Simulador crédito</span></a>
    </div>

    @if($proximaCuota)
    <!-- Próxima cuota -->
    <div class="cuota-card">
        <div class="cuota-label">Próxima cuota a pagar</div>
        <div class="cuota-val">S/ {{ number_format($proximaCuota->montocuota, 2) }}</div>
        <div class="cuota-sub">Vence el {{ \Carbon\Carbon::parse($proximaCuota->fechavencimientopagocuota)->format('d/m/Y') }}</div>
        <button class="cuota-btn">Pagar ahora</button>
    </div>
    @endif

    <!-- Cuentas de ahorro -->
    <div class="section" id="cuentas">
        <div class="section-header">
            <div class="section-title"><i class="ti ti-piggy-bank" style="color:#5B2D8E;margin-right:6px;"></i> Cuentas de Ahorro</div>
            <a href="{{ route('dashboard') }}#cuentas" class="section-link">Ver todas ›</a>
        </div>
        @forelse($cuentas as $cuenta)
        <a href="{{ route('cuenta.show', $cuenta->pkcuentaahorro) }}" class="account-item" style="text-decoration:none;">
            <div class="account-left">
                <div class="account-icon"><i class="ti ti-building-bank"></i></div>
                <div>
                    <div class="account-code">{{ $cuenta->codcuentaahorro }}</div>
                    <div class="account-type">Ahorro · TEA {{ number_format($cuenta->tea, 2) }}%</div>
                    <span class="account-badge">Activa</span>
                </div>
            </div>
            <div style="display:flex;align-items:center;">
                <div class="account-amount">
                    <div class="account-saldo">S/ {{ number_format($cuenta->saldo, 2) }}</div>
                </div>
                <i class="ti ti-chevron-right account-arrow"></i>
            </div>
        </a>
        @empty
        <p style="font-size:13px;color:#888;text-align:center;padding:16px 0;">No tienes cuentas de ahorro</p>
        @endforelse
        <div class="total-row">
            <span class="total-label">Saldo disponible total</span>
            <span class="total-val">S/ {{ number_format($saldoTotal, 2) }}</span>
        </div>
    </div>

    <!-- Préstamos -->
    <div class="section" id="creditos">
        <div class="section-header">
            <div class="section-title"><i class="ti ti-credit-card" style="color:#5B2D8E;margin-right:6px;"></i> Préstamos</div>
            <a href="{{ route('pagos.credito') }}" class="section-link">Ver todos ›</a>
        </div>
        @forelse($creditos as $credito)
        <a href="{{ route('credito.show', $credito->pkcuentacredito) }}" class="account-item" style="text-decoration:none;">
            <div class="account-left">
                <div class="account-icon"><i class="ti ti-file-invoice"></i></div>
                <div>
                    <div class="account-code">{{ $credito->codcuentacredito }}</div>
                    <div class="account-type">Consumo</div>
                    <span class="account-badge {{ $credito->pkestadocredito == 1 ? '' : 'amber' }}">
                        {{ $credito->desestadocredito ?? 'Vigente' }}
                    </span>
                </div>
            </div>
            <div style="display:flex;align-items:center;">
                <div class="account-amount">
                    <div class="account-saldo red">S/ {{ number_format($credito->montoaprobadocredito - $credito->montocapitalpagado, 2) }}</div>
                </div>
                <i class="ti ti-chevron-right account-arrow"></i>
            </div>
        </a>
        @empty
        <p style="font-size:13px;color:#888;text-align:center;padding:16px 0;">No tienes préstamos activos</p>
        @endforelse
        @if($creditos->count() > 0)
        <div class="total-row">
            <span class="total-label">Saldo pendiente total</span>
            <span class="total-val">S/ {{ number_format($creditos->sum('montoaprobadocredito') - $creditos->sum('montocapitalpagado'), 2) }}</span>
        </div>
        @endif
    </div>
</div>

</body>
</html>