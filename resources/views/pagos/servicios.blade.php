<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago de Servicios — Banco Ripley</title>
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
        .page-title { font-size: 22px; font-weight: 700; color: #1a1a2e; margin-bottom: 4px; }
        .page-sub { font-size: 13px; color: #888; margin-bottom: 24px; }
        .empresa-group { margin-bottom: 24px; }
        .empresa-nombre { font-size: 13px; font-weight: 700; color: #5B2D8E; text-transform: uppercase; letter-spacing: .06em; padding: 6px 0; border-bottom: 2px solid #5B2D8E; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
        .servicios-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 12px; }
        .servicio-card { background: #fff; border-radius: 10px; border: 1px solid #e8e0f0; padding: 16px 18px; cursor: pointer; transition: all .15s; display: flex; align-items: center; justify-content: space-between; }
        .servicio-card:hover { border-color: #5B2D8E; background: #faf5ff; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(91,45,142,0.1); }
        .servicio-left { display: flex; align-items: center; gap: 12px; }
        .servicio-icon { width: 38px; height: 38px; background: #f0e8ff; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .servicio-icon i { font-size: 20px; color: #5B2D8E; }
        .servicio-nombre { font-size: 13px; font-weight: 600; color: #1a1a2e; }
        .servicio-cat { font-size: 11px; color: #888; margin-top: 2px; }
        .servicio-arrow { color: #ccc; font-size: 16px; }
        .empty-state { background: #fff; border-radius: 12px; border: 1px solid #e8e0f0; padding: 48px; text-align: center; color: #888; }
        .empty-state i { font-size: 48px; color: #ddd; display: block; margin-bottom: 12px; }

        /* Modal pago servicio */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 100; align-items: center; justify-content: center; }
        .modal-overlay.show { display: flex; }
        .modal { background: #fff; border-radius: 16px; padding: 28px; width: 400px; }
        .modal-title { font-size: 16px; font-weight: 700; color: #1a1a2e; margin-bottom: 4px; }
        .modal-empresa { font-size: 13px; color: #5B2D8E; font-weight: 600; margin-bottom: 20px; }
        .form-group { margin-bottom: 14px; }
        .form-group label { font-size: 13px; color: #555; font-weight: 500; display: block; margin-bottom: 6px; }
        .form-control { width: 100%; padding: 10px 14px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; outline: none; }
        .form-control:focus { border-color: #5B2D8E; }
        .info-box { background: #f0e8ff; border-radius: 8px; padding: 12px; font-size: 12px; color: #5B2D8E; margin-bottom: 16px; display: flex; align-items: flex-start; gap: 8px; }
        .modal-btns { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 16px; }
        .btn-cancel { background: #f5f0ff; color: #5B2D8E; border: none; padding: 10px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; }
        .btn-confirm { background: #5B2D8E; color: #fff; border: none; padding: 10px; border-radius: 8px; font-size: 14px; font-weight: 700; cursor: pointer; }
    </style>
</head>
<body>
@include('partials.sidebar')

<div class="main">
    <div class="page-title">Pago de Servicios</div>
    <div class="page-sub">Paga tus servicios y recibos directamente desde tu cuenta</div>

    @php
        $iconMap = [
            'LUZ'     => 'ti-bolt',
            'AGUA'    => 'ti-droplet',
            'GAS'     => 'ti-flame',
            'TELF'    => 'ti-phone',
            'INTER'   => 'ti-wifi',
            'CABLE'   => 'ti-device-tv',
            'SEGURO'  => 'ti-shield',
            'MUNI'    => 'ti-building-community',
        ];
    @endphp

    @if($porEmpresa->isEmpty())
        <div class="empty-state">
            <i class="ti ti-plug-x"></i>
            <div style="font-size:16px;font-weight:700;color:#1a1a2e;margin-bottom:8px;">Sin servicios disponibles</div>
            <div>No hay convenios de pago de servicios configurados.</div>
        </div>
    @else
        @foreach($porEmpresa as $empresa => $servicios)
        <div class="empresa-group">
            <div class="empresa-nombre">
                <i class="ti ti-building"></i>
                {{ $empresa }}
            </div>
            <div class="servicios-grid">
                @foreach($servicios as $s)
                <div class="servicio-card" onclick="abrirServicio('{{ $s->pkconvenio }}', '{{ addslashes($s->desempresa) }}', '{{ addslashes($s->desservicio) }}')">
                    <div class="servicio-left">
                        <div class="servicio-icon">
                            <i class="ti {{ $iconMap[strtoupper(substr($s->codcategoria ?? '', 0, 5))] ?? 'ti-receipt' }}"></i>
                        </div>
                        <div>
                            <div class="servicio-nombre">{{ $s->desservicio }}</div>
                            <div class="servicio-cat">{{ $s->descategoria ?? $s->desconcepto ?? 'Servicio' }}</div>
                        </div>
                    </div>
                    <i class="ti ti-chevron-right servicio-arrow"></i>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    @endif
</div>

<!-- Modal pago de servicio -->
<div class="modal-overlay" id="modalServicio">
    <div class="modal">
        <form method="POST" action="{{ route('pagos.servicios.store') }}">
            @csrf
            <input type="hidden" name="pkconvenio" id="inputPkconvenio">

            <div class="modal-title"><i class="ti ti-receipt" style="color:#5B2D8E;margin-right:6px;"></i>Pago de servicio</div>
            <div class="modal-empresa" id="modalEmpresaNombre">—</div>

            <div class="info-box">
                <i class="ti ti-info-circle" style="flex-shrink:0;"></i>
                <span>Esta es una simulación de pago. En un entorno real se integraría con el proveedor del servicio.</span>
            </div>

            @if($errors->any())
                <div style="background:#fde8e8;color:#b91c1c;padding:10px 14px;border-radius:8px;font-size:12px;margin-bottom:14px;">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="form-group">
                <label>Número de suministro / código</label>
                <input type="text" name="codigo_suministro" class="form-control" placeholder="Ej: 123456789" required>
            </div>
            <div class="form-group">
                <label>Monto a pagar (S/)</label>
                <input type="number" name="monto" class="form-control" placeholder="0.00" min="1" max="5000" step="0.01" required>
            </div>
            <div class="form-group">
                <label>Débitar de cuenta</label>
                <select name="pkcuentaahorro" class="form-control" required>
                    <option value="">— Selecciona cuenta —</option>
                    @foreach($cuentasAhorro ?? [] as $cuenta)
                        <option value="{{ $cuenta->pkcuentaahorro }}">{{ $cuenta->codcuentaahorro }} — S/ {{ number_format($cuenta->saldo, 2) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="modal-btns">
                <button type="button" class="btn-cancel" onclick="cerrarServicio()">Cancelar</button>
                <button type="submit" class="btn-confirm">Pagar</button>
            </div>
        </form>
    </div>
</div>

<script>
function abrirServicio(pkconvenio, empresa, servicio) {
    document.getElementById('inputPkconvenio').value = pkconvenio;
    document.getElementById('modalEmpresaNombre').textContent = empresa + ' · ' + servicio;
    document.getElementById('modalServicio').classList.add('show');
}
function cerrarServicio() {
    document.getElementById('modalServicio').classList.remove('show');
}
</script>
</body>
</html>
