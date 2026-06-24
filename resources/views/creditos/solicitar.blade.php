<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Crédito — Banco Ripley</title>
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

        .main { margin-left: 220px; flex: 1; padding: 28px; max-width: 900px; }
        .topbar { margin-bottom: 24px; }
        .topbar-title { font-size: 22px; font-weight: 700; color: #1a1a2e; }
        .topbar-sub { font-size: 13px; color: #888; margin-top: 4px; }

        .alert { padding: 14px 18px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; }
        .alert-success { background: #e8f5ee; color: #1d7a4a; border: 1px solid #b8e6cc; }
        .alert-error { background: #fde8e8; color: #b91c1c; border: 1px solid #f5c2c2; }

        .section { background: #fff; border-radius: 12px; border: 1px solid #e8e0f0; padding: 24px; margin-bottom: 20px; }
        .section-title { font-size: 15px; font-weight: 700; color: #1a1a2e; margin-bottom: 18px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
        .field label { display: block; font-size: 12px; font-weight: 600; color: #444; margin-bottom: 6px; }
        .field input, .field select {
            width: 100%; padding: 10px 14px; border: 1.5px solid #e0d8ee;
            border-radius: 8px; font-size: 14px; color: #1a1a2e;
            background: #faf9fc; outline: none;
        }
        .field input:focus, .field select:focus { border-color: #5B2D8E; background: #fff; }
        .btn-submit {
            background: #5B2D8E; color: #fff; border: none; border-radius: 8px;
            padding: 12px 28px; font-size: 14px; font-weight: 700; cursor: pointer;
        }
        .btn-submit:hover { background: #4a2275; }

        .history-item { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f5f0ff; }
        .history-item:last-child { border-bottom: none; }
        .history-code { font-size: 13px; font-weight: 600; color: #1a1a2e; }
        .history-detail { font-size: 12px; color: #888; margin-top: 2px; }
        .badge { font-size: 10px; font-weight: 600; padding: 3px 10px; border-radius: 20px; }
        .badge-eval { background: #faeeda; color: #ba7517; }
        .badge-comite { background: #e8e0f0; color: #5B2D8E; }
        .badge-aprobado { background: #e8f5ee; color: #27a65a; }
        .badge-rechazado { background: #fde8e8; color: #E30613; }
        .badge-desembolsado { background: #e0f0fe; color: #1d6fa5; }
        .empty-state { text-align: center; padding: 30px 0; color: #888; font-size: 13px; }
    </style>
</head>
<body>

@include('partials.sidebar')

<div class="main">
    <div class="topbar">
        <div class="topbar-title">Solicitar Crédito</div>
        <div class="topbar-sub">Completa el formulario para enviar tu solicitud al banco</div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-error">{{ $errors->first() }}</div>
    @endif

    <div class="section">
        <div class="section-title">Datos de la solicitud</div>
        <form method="POST" action="{{ route('credito.solicitar.store') }}">
            @csrf
            <div class="form-row">
                <div class="field">
                    <label>Tipo de crédito</label>
                    <select name="tipo_credito" required>
                        <option value="">Selecciona...</option>
                        <option value="CO">Crédito de Consumo</option>
                        <option value="ME">Crédito Microempresa</option>
                    </select>
                </div>
                <div class="field">
                    <label>Monto solicitado (S/)</label>
                    <input type="number" name="monto" step="0.01" min="500" max="100000" placeholder="Ej: 5000" required>
                </div>
            </div>
            <div class="form-row">
                <div class="field">
                    <label>Plazo (meses)</label>
                    <input type="number" name="plazo" min="6" max="60" placeholder="Ej: 24" required>
                </div>
                <div class="field">
                    <label>Ingreso mensual neto (S/)</label>
                    <input type="number" name="ingreso" step="0.01" min="0" placeholder="Ej: 2500" required>
                </div>
            </div>
            <div class="field" style="margin-bottom: 20px;">
                <label>Actividad económica</label>
                <select name="actividad" required>
                    <option value="">Selecciona...</option>
                    @foreach($actividades as $act)
                        <option value="{{ $act->pkactividadeconomica }}">{{ $act->desactividadeconomica }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-submit">Enviar solicitud</button>
        </form>
    </div>

    <div class="section">
        <div class="section-title">Mis solicitudes anteriores</div>
        @forelse($solicitudes as $sol)
        <a href="{{ route('credito.solicitud.show', $sol->pksolicitud) }}" class="history-item" style="text-decoration:none;cursor:pointer;">
            <div>
                <div class="history-code">{{ $sol->codsolicitud }}</div>
                <div class="history-detail">
                    S/ {{ number_format($sol->montosolicitudcredito, 2) }} · {{ $sol->plazosolicitudcredito }} meses · {{ \Carbon\Carbon::parse($sol->fechasolicitudcredito)->format('d/m/Y') }}
                </div>
            </div>
            @php
                $badgeClass = match(trim($sol->dessolicitudestado)) {
                    'En Evaluación' => 'badge-eval',
                    'En Comité' => 'badge-comite',
                    'Aprobado' => 'badge-aprobado',
                    'Rechazado' => 'badge-rechazado',
                    'Desembolsado' => 'badge-desembolsado',
                    default => 'badge-eval',
                };
            @endphp
            <span class="badge {{ $badgeClass }}">{{ $sol->dessolicitudestado }}</span>
            </a>
            @empty
        <div class="empty-state">No tienes solicitudes registradas</div>
        @endforelse
    </div>
</div>

</body>
</html>