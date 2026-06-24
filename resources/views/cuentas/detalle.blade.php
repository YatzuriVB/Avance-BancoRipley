<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuenta {{ $cuenta->codcuentaahorro }} — Banco Ripley</title>
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

        /* BREADCRUMB */
        .breadcrumb { display: flex; align-items: center; gap: 6px; font-size: 13px; color: #888; margin-bottom: 20px; }
        .breadcrumb a { color: #5B2D8E; text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }

        /* CUENTA HEADER */
        .cuenta-header { background: linear-gradient(135deg, #5B2D8E, #4a2275); border-radius: 16px; padding: 28px 32px; margin-bottom: 20px; color: #fff; display: flex; align-items: center; justify-content: space-between; }
        .cuenta-icon { width: 56px; height: 56px; background: rgba(255,255,255,0.15); border-radius: 14px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .cuenta-icon i { font-size: 28px; color: #fff; }
        .cuenta-left { display: flex; align-items: center; gap: 20px; }
        .cuenta-cod { font-size: 22px; font-weight: 700; }
        .cuenta-tipo { font-size: 13px; color: rgba(255,255,255,0.7); margin-top: 3px; }
        .badge-estado { font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px; background: rgba(255,255,255,0.2); color: #fff; margin-top: 6px; display: inline-block; }
        .cuenta-right { text-align: right; }
        .saldo-label { font-size: 12px; color: rgba(255,255,255,0.7); }
        .saldo-val { font-size: 36px; font-weight: 800; margin-top: 4px; }
        .saldo-moneda { font-size: 18px; font-weight: 400; margin-right: 4px; }

        /* INFO CARDS */
        .info-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 20px; }
        .info-card { background: #fff; border-radius: 10px; border: 1px solid #e8e0f0; padding: 16px 18px; }
        .info-card-label { font-size: 11px; color: #888; text-transform: uppercase; letter-spacing: .04em; margin-bottom: 6px; }
        .info-card-val { font-size: 16px; font-weight: 700; color: #1a1a2e; }
        .info-card-val.purple { color: #5B2D8E; }

        /* MOVIMIENTOS */
        .section { background: #fff; border-radius: 12px; border: 1px solid #e8e0f0; padding: 20px 24px; }
        .section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
        .section-title { font-size: 15px; font-weight: 700; color: #1a1a2e; }
        .mov-table { width: 100%; border-collapse: collapse; }
        .mov-table th { font-size: 11px; color: #888; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; padding: 8px 12px; text-align: left; border-bottom: 1px solid #e8e0f0; }
        .mov-table td { padding: 12px; font-size: 13px; color: #444; border-bottom: 1px solid #f5f0ff; vertical-align: middle; }
        .mov-table tr:last-child td { border-bottom: none; }
        .mov-table tr:hover td { background: #faf8ff; }
        .badge-e { background: #fde8e8; color: #E30613; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 20px; }
        .badge-i { background: #e8f5ee; color: #27a65a; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 20px; }
        .monto-e { font-weight: 700; color: #E30613; }
        .monto-i { font-weight: 700; color: #27a65a; }
        .empty-state { text-align: center; padding: 40px; color: #888; font-size: 14px; }
        .empty-state i { font-size: 40px; color: #ddd; display: block; margin-bottom: 12px; }
    </style>
</head>
<body>
@include('partials.sidebar')

<div class="main">
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">Inicio</a>
        <i class="ti ti-chevron-right" style="font-size:12px;"></i>
        <span>Cuenta de Ahorro</span>
        <i class="ti ti-chevron-right" style="font-size:12px;"></i>
        <span>{{ $cuenta->codcuentaahorro }}</span>
    </div>

    <!-- Header de la cuenta -->
    <div class="cuenta-header">
        <div class="cuenta-left">
            <div class="cuenta-icon"><i class="ti ti-building-bank"></i></div>
            <div>
                <div class="cuenta-cod">{{ $cuenta->codcuentaahorro }}</div>
                <div class="cuenta-tipo">{{ $cuenta->destipoproducto }} · {{ $cuenta->destiposubproducto }}</div>
                <span class="badge-estado">{{ $cuenta->desestadocuenta }}</span>
            </div>
        </div>
        <div class="cuenta-right">
            <div class="saldo-label">Saldo disponible</div>
            <div class="saldo-val"><span class="saldo-moneda">{{ $cuenta->moneda }}</span>{{ number_format($cuenta->saldo, 2) }}</div>
            <div style="font-size:12px;color:rgba(255,255,255,0.6);margin-top:4px;">TEA {{ number_format($cuenta->tea, 2) }}% · {{ $cuenta->desmoneda }}</div>
        </div>
    </div>

    <!-- Info cards -->
    <div class="info-row">
        <div class="info-card">
            <div class="info-card-label">Fecha apertura</div>
            <div class="info-card-val">{{ \Carbon\Carbon::parse($cuenta->fechaaperturacuenta)->format('d/m/Y') }}</div>
        </div>
        <div class="info-card">
            <div class="info-card-label">Tipo de cuenta</div>
            <div class="info-card-val purple">{{ $cuenta->tipo_cuenta }}</div>
        </div>
        <div class="info-card">
            <div class="info-card-label">N° operaciones</div>
            <div class="info-card-val">{{ $cuenta->nrooperaciones ?? 0 }}</div>
        </div>
        <div class="info-card">
            <div class="info-card-label">Agencia</div>
            <div class="info-card-val" style="font-size:13px;">{{ $cuenta->desagencia }}</div>
        </div>
    </div>

    <!-- Estado de cuenta / movimientos -->
    <div class="section">
        <div class="section-header">
            <div class="section-title"><i class="ti ti-list" style="color:#5B2D8E;margin-right:6px;"></i>Estado de Cuenta</div>
            <span style="font-size:12px;color:#888;">Últimos {{ $movimientos->count() }} movimientos</span>
        </div>

        @if($movimientos->isEmpty())
            <div class="empty-state">
                <i class="ti ti-inbox"></i>
                No se encontraron movimientos registrados para esta cuenta.
            </div>
        @else
        <table class="mov-table">
            <thead>
                <tr>
                    <th>Fecha y hora</th>
                    <th>Concepto</th>
                    <th>Tipo operación</th>
                    <th style="text-align:center;">Tipo</th>
                    <th style="text-align:right;">Monto</th>
                </tr>
            </thead>
            <tbody>
                @foreach($movimientos as $mov)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($mov->fechahoraoperacion)->format('d/m/Y H:i') }}</td>
                    <td>{{ $mov->desconceptooperacion }}</td>
                    <td style="color:#888;">{{ $mov->destipooperacion }}</td>
                    <td style="text-align:center;">
                        @if($mov->codtipoegresoingreso === 'I')
                            <span class="badge-i">↑ Ingreso</span>
                        @else
                            <span class="badge-e">↓ Egreso</span>
                        @endif
                    </td>
                    <td style="text-align:right;" class="{{ $mov->codtipoegresoingreso === 'I' ? 'monto-i' : 'monto-e' }}">
                        {{ $mov->codtipoegresoingreso === 'I' ? '+' : '-' }}S/ {{ number_format($mov->montooperacion, 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
</body>
</html>
