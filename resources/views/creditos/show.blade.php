<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crédito {{ $credito->codcuentacredito }} — Banco Ripley</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f7f5fb; display: flex; min-height: 100vh; }
        .sidebar { width: 220px; background: #5B2D8E; display: flex; flex-direction: column; position: fixed; top: 0; left: 0; height: 100vh; overflow-y: auto; }
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
        .breadcrumb { display: flex; align-items: center; gap: 6px; font-size: 13px; color: #888; margin-bottom: 20px; }
        .breadcrumb a { color: #5B2D8E; text-decoration: none; }

        /* HEADER */
        .credito-header { background: linear-gradient(135deg, #1a1a2e, #2d2d5e); border-radius: 16px; padding: 28px 32px; margin-bottom: 20px; color: #fff; }
        .credito-header-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; }
        .credito-cod { font-size: 22px; font-weight: 700; }
        .credito-tipo { font-size: 13px; color: rgba(255,255,255,0.6); margin-top: 4px; }
        .badge-estado-cr { font-size: 11px; font-weight: 600; padding: 4px 12px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.3); color: rgba(255,255,255,0.9); }
        .saldo-grande { text-align: right; }
        .saldo-grande .label { font-size: 12px; color: rgba(255,255,255,0.6); }
        .saldo-grande .val { font-size: 32px; font-weight: 800; color: #ff6b6b; margin-top: 2px; }

        /* BARRA PROGRESO */
        .progress-section { }
        .progress-label { display: flex; justify-content: space-between; font-size: 12px; color: rgba(255,255,255,0.6); margin-bottom: 6px; }
        .progress-bar-outer { background: rgba(255,255,255,0.15); border-radius: 8px; height: 8px; }
        .progress-bar-inner { background: linear-gradient(90deg, #27a65a, #4cd98a); height: 8px; border-radius: 8px; transition: width .5s; }

        /* INFO GRID */
        .info-grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 20px; }
        .info-card { background: #fff; border-radius: 10px; border: 1px solid #e8e0f0; padding: 16px 18px; }
        .info-card-label { font-size: 11px; color: #888; text-transform: uppercase; letter-spacing: .04em; margin-bottom: 6px; }
        .info-card-val { font-size: 16px; font-weight: 700; color: #1a1a2e; }

        /* CRONOGRAMA */
        .section { background: #fff; border-radius: 12px; border: 1px solid #e8e0f0; padding: 20px 24px; margin-bottom: 20px; }
        .section-title { font-size: 15px; font-weight: 700; color: #1a1a2e; margin-bottom: 16px; }
        .crono-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .crono-table th { font-size: 11px; color: #888; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; padding: 8px 10px; text-align: right; border-bottom: 1px solid #e8e0f0; }
        .crono-table th:first-child, .crono-table th:nth-child(2) { text-align: left; }
        .crono-table td { padding: 10px; color: #444; border-bottom: 1px solid #f5f0ff; text-align: right; vertical-align: middle; }
        .crono-table td:first-child, .crono-table td:nth-child(2) { text-align: left; }
        .crono-table tr:last-child td { border-bottom: none; }
        .crono-table tr:hover td { background: #faf8ff; }
        .crono-table tr.pagada td { color: #aaa; }
        .badge-pa { background: #e8f5ee; color: #27a65a; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 20px; }
        .badge-pe { background: #fff3e0; color: #e67e22; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 20px; }
        .badge-ve { background: #fde8e8; color: #E30613; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 20px; }
        .badge-prox { background: #5B2D8E; color: #fff; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 20px; }
        .nro-cuota { width: 28px; height: 28px; background: #f0e8ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; color: #5B2D8E; }
    </style>
</head>
<body>
@include('partials.sidebar')

<div class="main">
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">Inicio</a>
        <i class="ti ti-chevron-right" style="font-size:12px;"></i>
        <span>Préstamos</span>
        <i class="ti ti-chevron-right" style="font-size:12px;"></i>
        <span>{{ $credito->codcuentacredito }}</span>
    </div>

    @php
        $pct = $credito->montoaprobadocredito > 0
            ? round(($credito->montocapitalpagado / $credito->montoaprobadocredito) * 100, 1)
            : 0;
    @endphp

    <!-- Header crédito -->
    <div class="credito-header">
        <div class="credito-header-top">
            <div>
                <div class="credito-cod">{{ $credito->codcuentacredito }}</div>
                <div class="credito-tipo">{{ $credito->destipocredito }} · {{ $credito->desproducto }}</div>
            </div>
            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:10px;">
                <span class="badge-estado-cr">{{ $credito->desestadocredito }}</span>
                <div class="saldo-grande">
                    <div class="label">Saldo pendiente</div>
                    <div class="val">{{ $credito->moneda }} {{ number_format($credito->montosaldocapital, 2) }}</div>
                </div>
            </div>
        </div>
        <div class="progress-section">
            <div class="progress-label">
                <span>Pagado: {{ $credito->moneda }} {{ number_format($credito->montocapitalpagado, 2) }} ({{ $pct }}%)</span>
                <span>Total: {{ $credito->moneda }} {{ number_format($credito->montoaprobadocredito, 2) }}</span>
            </div>
            <div class="progress-bar-outer">
                <div class="progress-bar-inner" style="width: {{ $pct }}%;"></div>
            </div>
        </div>
    </div>

    <!-- Info cards -->
    <div class="info-grid-4">
        <div class="info-card">
            <div class="info-card-label">Monto aprobado</div>
            <div class="info-card-val">S/ {{ number_format($credito->montoaprobadocredito, 2) }}</div>
        </div>
        <div class="info-card">
            <div class="info-card-label">N° cuotas</div>
            <div class="info-card-val">{{ $credito->nrocuotas }}</div>
        </div>
        <div class="info-card">
            <div class="info-card-label">TEA</div>
            <div class="info-card-val" style="color:#5B2D8E;">{{ number_format($credito->tea * 100, 2) }}%</div>
        </div>
        <div class="info-card">
            <div class="info-card-label">Desembolso</div>
            <div class="info-card-val" style="font-size:14px;">{{ \Carbon\Carbon::parse($credito->fechadesembolsocredito)->format('d/m/Y') }}</div>
        </div>
    </div>

    @if($proximaCuota)
    <div style="background:linear-gradient(135deg,#5B2D8E,#4a2275);border-radius:12px;padding:18px 24px;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;color:#fff;">
        <div>
            <div style="font-size:12px;color:rgba(255,255,255,0.7);margin-bottom:4px;">Próxima cuota a pagar — N° {{ $proximaCuota->nrocuota }}</div>
            <div style="font-size:26px;font-weight:800;">S/ {{ number_format($proximaCuota->montocuota, 2) }}</div>
            <div style="font-size:12px;color:rgba(255,255,255,0.7);margin-top:4px;">Vence el {{ \Carbon\Carbon::parse($proximaCuota->fechavencimientopagocuota)->format('d/m/Y') }}</div>
        </div>
        <a href="{{ route('pagos.credito') }}" style="background:#fff;color:#5B2D8E;padding:10px 22px;border-radius:8px;font-size:13px;font-weight:700;text-decoration:none;">Pagar ahora →</a>
    </div>
    @endif

    <!-- Cronograma -->
    <div class="section">
        <div class="section-title"><i class="ti ti-calendar" style="color:#5B2D8E;margin-right:6px;"></i>Cronograma de Pagos</div>
        <table class="crono-table">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Vencimiento</th>
                    <th>Capital</th>
                    <th>Interés</th>
                    <th>Mora</th>
                    <th>Cuota total</th>
                    <th>Saldo capital</th>
                    <th style="text-align:center;">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cronograma as $cuota)
                @php
                    $esPagada = $cuota->codestadocuota !== '01';
                    $esProxima = $proximaCuota && $cuota->nrocuota == $proximaCuota->nrocuota;
                @endphp
                <tr class="{{ $esPagada ? 'pagada' : '' }}">
                    <td>
                        <div class="nro-cuota" style="{{ $esPagada ? 'background:#e8f5ee;color:#27a65a;' : '' }}">{{ $cuota->nrocuota }}</div>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($cuota->fechavencimientopagocuota)->format('d/m/Y') }}</td>
                    <td>{{ number_format($cuota->montocapitalprogramado, 2) }}</td>
                    <td>{{ number_format($cuota->montointeresprogramado, 2) }}</td>
                    <td>{{ $cuota->montomora > 0 ? number_format($cuota->montomora, 2) : '—' }}</td>
                    <td style="font-weight:700;">{{ number_format($cuota->montocuota, 2) }}</td>
                    <td>{{ number_format($cuota->montosaldocapital, 2) }}</td>
                    <td style="text-align:center;">
                        @if($esProxima)
                            <span class="badge-prox">Próxima</span>
                        @elseif($esPagada)
                            <span class="badge-pa">✓ Pagada</span>
                        @elseif($cuota->diasatrasocuota > 0)
                            <span class="badge-ve">Vencida</span>
                        @else
                            <span class="badge-pe">Pendiente</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
