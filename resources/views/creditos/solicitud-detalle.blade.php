<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado de Solicitud — Banco Ripley</title>
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

        .main { margin-left: 220px; flex: 1; padding: 28px; max-width: 800px; }
        .topbar { margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; }
        .topbar-title { font-size: 22px; font-weight: 700; color: #1a1a2e; }
        .topbar-sub { font-size: 13px; color: #888; margin-top: 4px; }
        .back-link { font-size: 13px; color: #5B2D8E; text-decoration: none; }

        .section { background: #fff; border-radius: 12px; border: 1px solid #e8e0f0; padding: 24px; margin-bottom: 16px; }
        .section-title { font-size: 15px; font-weight: 700; color: #1a1a2e; margin-bottom: 16px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .info-label { font-size: 11px; color: #888; margin-bottom: 4px; }
        .info-val { font-size: 14px; font-weight: 600; color: #1a1a2e; }

        .timeline { position: relative; padding-left: 28px; }
        .timeline-item { position: relative; padding-bottom: 24px; }
        .timeline-item:last-child { padding-bottom: 0; }
        .timeline-dot {
            position: absolute; left: -28px; top: 2px; width: 16px; height: 16px;
            border-radius: 50%; background: #e0d8ee; border: 3px solid #fff;
        }
        .timeline-dot.active { background: #5B2D8E; }
        .timeline-dot.done { background: #27a65a; }
        .timeline-line {
            position: absolute; left: -21px; top: 18px; bottom: 0; width: 2px; background: #e0d8ee;
        }
        .timeline-title { font-size: 13px; font-weight: 600; color: #1a1a2e; }
        .timeline-sub { font-size: 12px; color: #888; margin-top: 2px; }

        .badge { font-size: 11px; font-weight: 600; padding: 4px 12px; border-radius: 20px; display: inline-block; }
        .badge-eval { background: #faeeda; color: #ba7517; }
        .badge-comite { background: #e8e0f0; color: #5B2D8E; }
        .badge-aprobado { background: #e8f5ee; color: #27a65a; }
        .badge-rechazado { background: #fde8e8; color: #E30613; }
        .badge-desembolsado { background: #e0f0fe; color: #1d6fa5; }

        .btn-link {
            background: #5B2D8E; color: #fff; border: none; border-radius: 8px;
            padding: 10px 24px; font-size: 13px; font-weight: 700; text-decoration: none; display: inline-block;
        }
    </style>
</head>
<body>

@include('partials.sidebar')

<div class="main">
    <div class="topbar">
        <div>
            <div class="topbar-title">{{ $solicitud->codsolicitud }}</div>
            <div class="topbar-sub">Estado de tu solicitud de crédito</div>
        </div>
        <a href="{{ route('credito.solicitar') }}" class="back-link">← Volver</a>
    </div>

    <div class="section">
        <div class="section-title">Estado actual</div>
        @php
            $badgeClass = match(trim($solicitud->dessolicitudestado)) {
                'En Evaluación' => 'badge-eval',
                'En Comité' => 'badge-comite',
                'Aprobado' => 'badge-aprobado',
                'Rechazado' => 'badge-rechazado',
                'Desembolsado' => 'badge-desembolsado',
                default => 'badge-eval',
            };
        @endphp
        <span class="badge {{ $badgeClass }}">{{ $solicitud->dessolicitudestado }}</span>

        @if($creditoGenerado)
            <div style="margin-top: 16px;">
                <a href="{{ route('credito.show', $creditoGenerado->pkcuentacredito) }}" class="btn-link">
                    Ver mi crédito {{ $creditoGenerado->codcuentacredito }} →
                </a>
            </div>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Seguimiento</div>
        <div class="timeline">
            @php
                $estados = ['01' => 'Enviado / En Evaluación', '06' => 'En Comité', '02' => 'Aprobado', '04' => 'Desembolsado'];
                $estadoActual = trim($solicitud->codsolicitudestado);
                $orden = ['01', '06', '02', '04'];
                $posicionActual = array_search($estadoActual, $orden);
                if ($posicionActual === false) $posicionActual = $estadoActual === '03' ? 0 : 0; // rechazado se queda en eval
            @endphp
            @foreach($orden as $i => $codigo)
                @php
                    $completado = $i < $posicionActual || ($estadoActual === $codigo);
                    $esActual = $estadoActual === $codigo;
                @endphp
                <div class="timeline-item">
                    @if($i < count($orden) - 1)
                        <div class="timeline-line"></div>
                    @endif
                    <div class="timeline-dot {{ $esActual ? 'active' : ($i < $posicionActual ? 'done' : '') }}"></div>
                    <div class="timeline-title">{{ $estados[$codigo] }}</div>
                    @if($esActual)
                        <div class="timeline-sub">Estado actual de tu solicitud</div>
                    @endif
                </div>
            @endforeach
            @if($estadoActual === '03')
                <div class="timeline-item">
                    <div class="timeline-dot active" style="background:#E30613;"></div>
                    <div class="timeline-title" style="color:#E30613;">Rechazado</div>
                    <div class="timeline-sub">Tu solicitud no fue aprobada</div>
                </div>
            @endif
        </div>
    </div>

    <div class="section">
        <div class="section-title">Datos de la solicitud</div>
        <div class="info-grid">
            <div>
                <div class="info-label">Tipo de crédito</div>
                <div class="info-val">{{ $solicitud->destipocredito }}</div>
            </div>
            <div>
                <div class="info-label">Producto</div>
                <div class="info-val">{{ $solicitud->desproducto }}</div>
            </div>
            <div>
                <div class="info-label">Monto solicitado</div>
                <div class="info-val">S/ {{ number_format($solicitud->montosolicitudcredito, 2) }}</div>
            </div>
            <div>
                <div class="info-label">Monto aprobado</div>
                <div class="info-val">{{ $solicitud->montoaprobadocredito ? 'S/ ' . number_format($solicitud->montoaprobadocredito, 2) : '—' }}</div>
            </div>
            <div>
                <div class="info-label">Plazo solicitado</div>
                <div class="info-val">{{ $solicitud->plazosolicitudcredito }} meses</div>
            </div>
            <div>
                <div class="info-label">Fecha de solicitud</div>
                <div class="info-val">{{ \Carbon\Carbon::parse($solicitud->fechasolicitudcredito)->format('d/m/Y') }}</div>
            </div>
            <div>
                <div class="info-label">Actividad económica</div>
                <div class="info-val">{{ $solicitud->desactividadeconomica ?? '—' }}</div>
            </div>
            <div>
                <div class="info-label">Fecha de aprobación</div>
                <div class="info-val">{{ $solicitud->fechaaprobacioncredito ? \Carbon\Carbon::parse($solicitud->fechaaprobacioncredito)->format('d/m/Y') : '—' }}</div>
            </div>
        </div>
    </div>
</div>

</body>
</html>