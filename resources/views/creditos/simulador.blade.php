<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulador de Crédito — Banco Ripley</title>
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

        .sim-layout { display: grid; grid-template-columns: 380px 1fr; gap: 20px; }

        /* FORMULARIO */
        .sim-form { background: #fff; border-radius: 12px; border: 1px solid #e8e0f0; padding: 24px; }
        .form-title { font-size: 15px; font-weight: 700; color: #1a1a2e; margin-bottom: 20px; }
        .form-group { margin-bottom: 18px; }
        .form-group label { font-size: 13px; color: #555; font-weight: 500; display: block; margin-bottom: 6px; }
        .form-control { width: 100%; padding: 11px 14px; border: 1px solid #ddd; border-radius: 8px; font-size: 15px; color: #1a1a2e; outline: none; transition: border .15s; }
        .form-control:focus { border-color: #5B2D8E; box-shadow: 0 0 0 3px rgba(91,45,142,0.1); }
        .form-hint { font-size: 11px; color: #aaa; margin-top: 4px; }
        .btn-calcular { width: 100%; background: #5B2D8E; color: #fff; border: none; padding: 12px; border-radius: 8px; font-size: 15px; font-weight: 700; cursor: pointer; transition: background .15s; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .btn-calcular:hover { background: #4a2275; }

        /* RESULTADO */
        .sim-result { display: flex; flex-direction: column; gap: 16px; }
        .result-card { background: linear-gradient(135deg, #5B2D8E, #4a2275); border-radius: 12px; padding: 24px; color: #fff; display: none; }
        .result-card.visible { display: block; }
        .result-main { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 20px; }
        .result-item .label { font-size: 11px; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: .04em; margin-bottom: 6px; }
        .result-item .val { font-size: 24px; font-weight: 800; }
        .result-item .val.cuota { font-size: 32px; }
        .result-sub { font-size: 12px; color: rgba(255,255,255,0.6); border-top: 1px solid rgba(255,255,255,0.15); padding-top: 14px; display: flex; justify-content: space-between; }

        /* TABLA AMORTIZACIÓN */
        .amort-card { background: #fff; border-radius: 12px; border: 1px solid #e8e0f0; padding: 20px 24px; display: none; max-height: 480px; overflow-y: auto; }
        .amort-card.visible { display: block; }
        .amort-title { font-size: 15px; font-weight: 700; color: #1a1a2e; margin-bottom: 14px; }
        .amort-table { width: 100%; border-collapse: collapse; font-size: 12px; }
        .amort-table th { font-size: 11px; color: #888; font-weight: 600; text-transform: uppercase; padding: 6px 10px; text-align: right; border-bottom: 1px solid #e8e0f0; position: sticky; top: 0; background: #fff; }
        .amort-table th:first-child { text-align: center; }
        .amort-table td { padding: 8px 10px; color: #444; border-bottom: 1px solid #f5f0ff; text-align: right; }
        .amort-table td:first-child { text-align: center; font-weight: 700; color: #5B2D8E; }
        .amort-table tr:hover td { background: #faf8ff; }
    </style>
</head>
<body>
@include('partials.sidebar')

<div class="main">
    <div class="page-title">Simulador de Crédito</div>
    <div class="page-sub">Calcula tu cuota mensual bajo el sistema francés (cuota fija)</div>

    <div class="sim-layout">
        <!-- Formulario -->
        <div class="sim-form">
            <div class="form-title"><i class="ti ti-calculator" style="color:#5B2D8E;margin-right:6px;"></i>Ingresa los datos</div>
            <div class="form-group">
                <label>Monto del préstamo (S/)</label>
                <input type="number" id="monto" class="form-control" placeholder="Ej: 5000" min="100" max="999999" step="100">
                <div class="form-hint">Mínimo S/ 100 — Máximo S/ 999,999</div>
            </div>
            <div class="form-group">
                <label>Plazo (meses)</label>
                <input type="number" id="plazo" class="form-control" placeholder="Ej: 24" min="1" max="120">
                <div class="form-hint">Entre 1 y 120 meses</div>
            </div>
            <div class="form-group">
                <label>TEA — Tasa Efectiva Anual (%)</label>
                <input type="number" id="tea" class="form-control" placeholder="Ej: 35.50" min="0.1" max="200" step="0.01">
                <div class="form-hint">Tasa efectiva anual en porcentaje</div>
            </div>
            <button class="btn-calcular" onclick="calcular()">
                <i class="ti ti-calculator"></i> Calcular cuota
            </button>
        </div>

        <!-- Resultado -->
        <div class="sim-result">
            <div class="result-card" id="resultCard">
                <div class="result-main">
                    <div class="result-item">
                        <div class="label">Cuota mensual</div>
                        <div class="val cuota">S/ <span id="rCuota">—</span></div>
                    </div>
                    <div class="result-item">
                        <div class="label">Total a pagar</div>
                        <div class="val">S/ <span id="rTotal">—</span></div>
                    </div>
                    <div class="result-item">
                        <div class="label">Total intereses</div>
                        <div class="val">S/ <span id="rIntereses">—</span></div>
                    </div>
                </div>
                <div class="result-sub">
                    <span>Sistema: Francés (cuota fija)</span>
                    <span id="rResumen">—</span>
                </div>
            </div>

            <div class="amort-card" id="amortCard">
                <div class="amort-title"><i class="ti ti-table" style="color:#5B2D8E;margin-right:6px;"></i>Tabla de Amortización</div>
                <table class="amort-table">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Capital</th>
                            <th>Interés</th>
                            <th>Cuota</th>
                            <th>Saldo</th>
                        </tr>
                    </thead>
                    <tbody id="amortBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function calcular() {
    const monto = parseFloat(document.getElementById('monto').value);
    const plazo = parseInt(document.getElementById('plazo').value);
    const tea   = parseFloat(document.getElementById('tea').value);

    if (!monto || !plazo || !tea || monto <= 0 || plazo <= 0 || tea <= 0) {
        alert('Por favor completa todos los campos con valores válidos.');
        return;
    }

    // TEA → TEM (tasa efectiva mensual)
    const tem = Math.pow(1 + tea / 100, 1 / 12) - 1;

    // Cuota fija sistema francés
    const cuota = monto * (tem * Math.pow(1 + tem, plazo)) / (Math.pow(1 + tem, plazo) - 1);
    const totalPago    = cuota * plazo;
    const totalInteres = totalPago - monto;

    // Mostrar resultados
    document.getElementById('rCuota').textContent     = fmt(cuota);
    document.getElementById('rTotal').textContent     = fmt(totalPago);
    document.getElementById('rIntereses').textContent = fmt(totalInteres);
    document.getElementById('rResumen').textContent   = `S/ ${fmt(monto)} a ${plazo} meses · TEA ${tea}%`;
    document.getElementById('resultCard').classList.add('visible');

    // Tabla de amortización
    const tbody = document.getElementById('amortBody');
    tbody.innerHTML = '';
    let saldo = monto;
    for (let i = 1; i <= plazo; i++) {
        const interes  = saldo * tem;
        const capital  = cuota - interes;
        saldo -= capital;
        const tr = `<tr>
            <td>${i}</td>
            <td>${fmt(capital)}</td>
            <td>${fmt(interes)}</td>
            <td style="font-weight:700;">${fmt(cuota)}</td>
            <td style="color:${saldo < 0.01 ? '#27a65a' : '#1a1a2e'};">${fmt(Math.max(0, saldo))}</td>
        </tr>`;
        tbody.insertAdjacentHTML('beforeend', tr);
    }
    document.getElementById('amortCard').classList.add('visible');
}

function fmt(n) {
    return n.toLocaleString('es-PE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

// Calcular al presionar Enter
document.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') calcular();
});
</script>
</body>
</html>
