<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago de Crédito — Banco Ripley</title>
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
        .page-title { font-size: 22px; font-weight: 700; color: #1a1a2e; margin-bottom: 4px; }
        .page-sub { font-size: 13px; color: #888; margin-bottom: 24px; }
        .alert { padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 16px; }
        .alert-success { background: #e8f5ee; color: #1e7e4c; border: 1px solid #b6dfc8; }
        .alert-error { background: #fde8e8; color: #c0392b; border: 1px solid #f5c6c6; }
        .empty-state { background: #fff; border-radius: 12px; border: 1px solid #e8e0f0; padding: 48px; text-align: center; color: #888; }
        .empty-state i { font-size: 48px; color: #ddd; display: block; margin-bottom: 12px; }
        .cuota-card { background: #fff; border-radius: 12px; border: 1px solid #e8e0f0; padding: 20px 24px; margin-bottom: 16px; display: grid; grid-template-columns: 1fr auto; gap: 16px; align-items: center; }
        .cuota-cod { font-size: 14px; font-weight: 700; color: #1a1a2e; }
        .cuota-prod { font-size: 12px; color: #888; margin-top: 2px; }
        .cuota-venc { font-size: 12px; color: #5B2D8E; margin-top: 6px; font-weight: 600; }
        .cuota-desglose { display: flex; gap: 16px; margin-top: 10px; }
        .desglose-item { }
        .desglose-label { font-size: 11px; color: #888; text-transform: uppercase; }
        .desglose-val { font-size: 14px; font-weight: 600; color: #1a1a2e; }
        .cuota-monto { font-size: 26px; font-weight: 800; color: #E30613; text-align: right; }
        .cuota-monto-label { font-size: 11px; color: #888; text-align: right; margin-bottom: 4px; }
        .btn-pagar { background: #5B2D8E; color: #fff; border: none; padding: 10px 22px; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; white-space: nowrap; margin-top: 8px; }
        .btn-pagar:hover { background: #4a2275; }
        .badge-mora { background: #fde8e8; color: #E30613; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 20px; margin-left: 8px; }

        /* Modal */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 100; align-items: center; justify-content: center; }
        .modal-overlay.show { display: flex; }
        .modal { background: #fff; border-radius: 16px; padding: 28px; width: 400px; }
        .modal-title { font-size: 16px; font-weight: 700; color: #1a1a2e; margin-bottom: 6px; }
        .modal-sub { font-size: 13px; color: #888; margin-bottom: 20px; }
        .modal-monto { font-size: 32px; font-weight: 800; color: #5B2D8E; text-align: center; margin: 16px 0; }
        .modal-select { width: 100%; padding: 11px 14px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; margin-bottom: 16px; outline: none; }
        .modal-select:focus { border-color: #5B2D8E; }
        .modal-btns { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .btn-cancel { background: #f5f0ff; color: #5B2D8E; border: none; padding: 10px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; }
        .btn-confirm { background: #5B2D8E; color: #fff; border: none; padding: 10px; border-radius: 8px; font-size: 14px; font-weight: 700; cursor: pointer; }
    </style>
</head>
<body>
@include('partials.sidebar')

<div class="main">
    <div class="page-title">Pago de Crédito</div>
    <div class="page-sub">Selecciona el crédito y la cuota a pagar</div>

    @if(session('success'))
        <div class="alert alert-success"><i class="ti ti-circle-check"></i> {{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-error">
            @foreach($errors->all() as $err) <div>{{ $err }}</div> @endforeach
        </div>
    @endif

    @if($cuotasPendientes->isEmpty())
        <div class="empty-state">
            <i class="ti ti-circle-check" style="color:#27a65a;"></i>
            <div style="font-size:16px;font-weight:700;color:#1a1a2e;margin-bottom:8px;">¡Todo al día!</div>
            <div>No tienes cuotas pendientes de pago en este momento.</div>
        </div>
    @else
        @foreach($cuotasPendientes as $c)
        @php $conMora = $c['montomora'] > 0; @endphp
        <div class="cuota-card">
            <div>
                <div style="display:flex;align-items:center;">
                    <div class="cuota-cod">{{ $c['codcuentacredito'] }}</div>
                    @if($conMora) <span class="badge-mora">Con mora</span> @endif
                </div>
                <div class="cuota-prod">{{ $c['desproducto'] ?? 'Crédito de Consumo' }} · Cuota N° {{ $c['nrocuota'] }}</div>
                <div class="cuota-venc"><i class="ti ti-calendar" style="font-size:12px;"></i> Vence: {{ \Carbon\Carbon::parse($c['fechavencimientopagocuota'])->format('d/m/Y') }}</div>
                <div class="cuota-desglose">
                    <div class="desglose-item">
                        <div class="desglose-label">Capital</div>
                        <div class="desglose-val">S/ {{ number_format($c['montocapitalprogramado'], 2) }}</div>
                    </div>
                    <div class="desglose-item">
                        <div class="desglose-label">Interés</div>
                        <div class="desglose-val">S/ {{ number_format($c['montointeresprogramado'], 2) }}</div>
                    </div>
                    @if($conMora)
                    <div class="desglose-item">
                        <div class="desglose-label" style="color:#E30613;">Mora</div>
                        <div class="desglose-val" style="color:#E30613;">S/ {{ number_format($c['montomora'], 2) }}</div>
                    </div>
                    @endif
                </div>
            </div>
            <div style="text-align:right;">
                <div class="cuota-monto-label">Total a pagar</div>
                <div class="cuota-monto">S/ {{ number_format($c['montocuota'] + $c['montomora'], 2) }}</div>
                <button class="btn-pagar" onclick="abrirPago('{{ $c['pkcuentacredito'] }}', '{{ $c['nrocuota'] }}', '{{ $c['periodomes'] }}', 'S/ {{ number_format($c['montocuota'] + $c['montomora'], 2) }}', '{{ $c['codcuentacredito'] }}')">
                    Pagar ahora →
                </button>
            </div>
        </div>
        @endforeach
    @endif
</div>

<!-- Modal de pago -->
<div class="modal-overlay" id="modalPago">
    <div class="modal">
        <div class="modal-title"><i class="ti ti-credit-card" style="color:#5B2D8E;margin-right:6px;"></i>Confirmar pago de cuota</div>
        <div class="modal-sub" id="modalSubtitle">—</div>
        <div class="modal-monto" id="modalMontoPago">S/ 0.00</div>
        <form method="POST" action="{{ route('pagos.credito.store') }}" id="frmPago">
            @csrf
            <input type="hidden" name="pkcuentacredito" id="inputCredito">
            <input type="hidden" name="nrocuota" id="inputCuota">
            <input type="hidden" name="periodomes" id="inputPeriodo">
            <label style="font-size:13px;color:#555;font-weight:500;display:block;margin-bottom:6px;">Débitar desde</label>
            <select name="pkcuentaahorro" class="modal-select" required>
                <option value="">— Selecciona cuenta —</option>
                @foreach($cuentasAhorro as $ca)
                <option value="{{ $ca->pkcuentaahorro }}">
                    {{ $ca->codcuentaahorro }} · S/ {{ number_format($ca->saldo, 2) }}
                </option>
                @endforeach
            </select>
            <div class="modal-btns">
                <button type="button" class="btn-cancel" onclick="cerrarPago()">Cancelar</button>
                <button type="submit" class="btn-confirm">Confirmar pago</button>
            </div>
        </form>
    </div>
</div>

<script>
function abrirPago(credito, cuota, periodo, monto, cod) {
    document.getElementById('inputCredito').value  = credito;
    document.getElementById('inputCuota').value    = cuota;
    document.getElementById('inputPeriodo').value  = periodo;
    document.getElementById('modalMontoPago').textContent = monto;
    document.getElementById('modalSubtitle').textContent  = cod + ' · Cuota N° ' + cuota;
    document.getElementById('modalPago').classList.add('show');
}
function cerrarPago() {
    document.getElementById('modalPago').classList.remove('show');
}
</script>
</body>
</html>
