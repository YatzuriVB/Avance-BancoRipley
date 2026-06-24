<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transferencias — Banco Ripley</title>
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
        .layout { display: grid; grid-template-columns: 420px 1fr; gap: 20px; }
        .card { background: #fff; border-radius: 12px; border: 1px solid #e8e0f0; padding: 24px; }
        .card-title { font-size: 15px; font-weight: 700; color: #1a1a2e; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; }
        .card-title i { color: #5B2D8E; font-size: 18px; }
        .form-group { margin-bottom: 16px; }
        .form-group label { font-size: 13px; color: #555; font-weight: 500; display: block; margin-bottom: 6px; }
        .form-control { width: 100%; padding: 11px 14px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; color: #1a1a2e; outline: none; transition: border .15s; }
        .form-control:focus { border-color: #5B2D8E; box-shadow: 0 0 0 3px rgba(91,45,142,0.1); }
        .saldo-hint { font-size: 12px; color: #27a65a; margin-top: 4px; font-weight: 600; }
        .btn-trf { width: 100%; background: #5B2D8E; color: #fff; border: none; padding: 12px; border-radius: 8px; font-size: 15px; font-weight: 700; cursor: pointer; transition: background .15s; display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 4px; }
        .btn-trf:hover { background: #4a2275; }
        .error-msg { font-size: 12px; color: #E30613; margin-top: 4px; }
        .alert { padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 16px; }
        .alert-success { background: #e8f5ee; color: #1e7e4c; border: 1px solid #b6dfc8; }
        .alert-error { background: #fde8e8; color: #c0392b; border: 1px solid #f5c6c6; }
        /* Historial */
        .hist-item { display: flex; align-items: center; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f5f0ff; }
        .hist-item:last-child { border-bottom: none; }
        .hist-icon { width: 36px; height: 36px; background: #fde8e8; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .hist-icon i { font-size: 18px; color: #E30613; }
        .hist-left { display: flex; align-items: center; gap: 10px; }
        .hist-cod { font-size: 13px; font-weight: 600; color: #1a1a2e; }
        .hist-date { font-size: 12px; color: #888; margin-top: 2px; }
        .hist-monto { font-size: 15px; font-weight: 700; color: #E30613; }
        .empty-state { text-align: center; padding: 32px; color: #888; font-size: 13px; }
        .empty-state i { font-size: 36px; color: #ddd; display: block; margin-bottom: 10px; }

        /* Modal de confirmación */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 100; align-items: center; justify-content: center; }
        .modal-overlay.show { display: flex; }
        .modal { background: #fff; border-radius: 16px; padding: 28px; width: 380px; }
        .modal-title { font-size: 16px; font-weight: 700; color: #1a1a2e; margin-bottom: 16px; }
        .modal-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f5f0ff; font-size: 14px; }
        .modal-row:last-of-type { border-bottom: none; }
        .modal-label { color: #888; }
        .modal-val { font-weight: 600; color: #1a1a2e; }
        .modal-monto { font-size: 28px; font-weight: 800; color: #5B2D8E; text-align: center; margin: 16px 0; }
        .modal-btns { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 20px; }
        .btn-cancel { background: #f5f0ff; color: #5B2D8E; border: none; padding: 10px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; }
        .btn-confirm { background: #5B2D8E; color: #fff; border: none; padding: 10px; border-radius: 8px; font-size: 14px; font-weight: 700; cursor: pointer; }
    </style>
</head>
<body>
@include('partials.sidebar')

<div class="main">
    <div class="page-title">Transferencias</div>
    <div class="page-sub">Transfiere saldo entre tus cuentas propias</div>

    @if(session('success'))
        <div class="alert alert-success"><i class="ti ti-circle-check"></i> {{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-error">
            @foreach($errors->all() as $err) <div>{{ $err }}</div> @endforeach
        </div>
    @endif

    <div class="layout">
        <!-- Formulario -->
        <div class="card">
            <div class="card-title"><i class="ti ti-send"></i>Transferencia entre cuentas propias</div>
            <form id="frmTransferencia" method="POST" action="{{ route('transferencias.store') }}" onsubmit="return confirmar(event)">
                @csrf
                <div class="form-group">
                    <label>Cuenta origen</label>
                    <select name="cuenta_origen" id="cuentaOrigen" class="form-control" onchange="actualizarSaldo()">
                        <option value="">— Selecciona una cuenta —</option>
                        @foreach($cuentas as $c)
                        <option value="{{ $c->pkcuentaahorro }}" data-saldo="{{ $c->saldo }}" data-cod="{{ $c->codcuentaahorro }}">
                            {{ $c->codcuentaahorro }} · S/ {{ number_format($c->saldo, 2) }}
                        </option>
                        @endforeach
                    </select>
                    @error('cuenta_origen') <div class="error-msg">{{ $message }}</div> @enderror
                    <div class="saldo-hint" id="saldoHint" style="display:none;"></div>
                </div>

                <div class="form-group">
                    <label>Cuenta destino</label>
                    <select name="cuenta_destino" id="cuentaDestino" class="form-control">
                        <option value="">— Selecciona una cuenta —</option>
                        @foreach($cuentas as $c)
                        <option value="{{ $c->pkcuentaahorro }}" data-cod="{{ $c->codcuentaahorro }}">
                            {{ $c->codcuentaahorro }} · {{ $c->tipo }}
                        </option>
                        @endforeach
                    </select>
                    @error('cuenta_destino') <div class="error-msg">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Monto (S/)</label>
                    <input type="number" name="monto" id="monto" class="form-control" placeholder="0.00" min="1" step="0.01" value="{{ old('monto') }}">
                    @error('monto') <div class="error-msg">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Descripción (opcional)</label>
                    <input type="text" name="descripcion" class="form-control" placeholder="Ej: Envío a ahorro programado" maxlength="100" value="{{ old('descripcion') }}">
                </div>

                <button type="submit" class="btn-trf">
                    <i class="ti ti-send"></i> Transferir
                </button>
            </form>
        </div>

        <!-- Historial -->
        <div class="card">
            <div class="card-title"><i class="ti ti-history"></i>Últimas transferencias</div>
            @if($historial->isEmpty())
                <div class="empty-state">
                    <i class="ti ti-inbox"></i>
                    Sin transferencias recientes
                </div>
            @else
                @foreach($historial as $h)
                <div class="hist-item">
                    <div class="hist-left">
                        <div class="hist-icon"><i class="ti ti-send"></i></div>
                        <div>
                            <div class="hist-cod">{{ $h->codcuentaahorro }}</div>
                            <div class="hist-date">{{ \Carbon\Carbon::parse($h->fechahoraoperacion)->format('d/m/Y H:i') }} · {{ $h->desconceptooperacion }}</div>
                        </div>
                    </div>
                    <div class="hist-monto">-S/ {{ number_format($h->montooperacion, 2) }}</div>
                </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<!-- Modal de confirmación -->
<div class="modal-overlay" id="modalOverlay">
    <div class="modal">
        <div class="modal-title"><i class="ti ti-send" style="color:#5B2D8E;margin-right:6px;"></i>Confirmar transferencia</div>
        <div class="modal-monto" id="modalMonto">S/ 0.00</div>
        <div class="modal-row"><span class="modal-label">Desde</span><span class="modal-val" id="modalOrigen">—</span></div>
        <div class="modal-row"><span class="modal-label">Hacia</span><span class="modal-val" id="modalDestino">—</span></div>
        <div class="modal-btns">
            <button class="btn-cancel" onclick="cancelar()">Cancelar</button>
            <button class="btn-confirm" onclick="document.getElementById('frmTransferencia').submit()">Confirmar</button>
        </div>
    </div>
</div>

<script>
function actualizarSaldo() {
    const sel = document.getElementById('cuentaOrigen');
    const opt = sel.options[sel.selectedIndex];
    const hint = document.getElementById('saldoHint');
    if (opt.value) {
        const saldo = parseFloat(opt.dataset.saldo);
        hint.textContent = 'Saldo disponible: S/ ' + saldo.toLocaleString('es-PE', {minimumFractionDigits: 2});
        hint.style.display = 'block';
    } else {
        hint.style.display = 'none';
    }
}

function confirmar(e) {
    e.preventDefault();
    const origen  = document.getElementById('cuentaOrigen');
    const destino = document.getElementById('cuentaDestino');
    const monto   = parseFloat(document.getElementById('monto').value);

    if (!origen.value || !destino.value || !monto) {
        alert('Completa todos los campos obligatorios.');
        return false;
    }
    if (origen.value === destino.value) {
        alert('La cuenta destino debe ser diferente a la cuenta origen.');
        return false;
    }

    document.getElementById('modalMonto').textContent  = 'S/ ' + monto.toLocaleString('es-PE', {minimumFractionDigits: 2});
    document.getElementById('modalOrigen').textContent  = origen.options[origen.selectedIndex].dataset.cod;
    document.getElementById('modalDestino').textContent = destino.options[destino.selectedIndex].dataset.cod;
    document.getElementById('modalOverlay').classList.add('show');
    return false;
}

function cancelar() {
    document.getElementById('modalOverlay').classList.remove('show');
}
</script>
</body>
</html>
