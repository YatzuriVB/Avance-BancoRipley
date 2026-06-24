@extends('layouts.core')

@section('content')
<div class="mb-6 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Mi cartera</h1>
        <p class="text-sm text-gray-500">Indicadores de la cartera que gestionas · Asesor {{ Session::get('core_codasesor') }}</p>
    </div>
    <div class="w-48 text-right">
        <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wider">Periodo (AAAAMM)</label>
        <input type="text" readonly value="{{ $ultimoPeriodo }}" class="w-full bg-white border border-gray-200 px-3 py-2 rounded text-right font-medium text-gray-700 shadow-sm outline-none">
    </div>
</div>

<!-- KPIs -->
<div class="grid grid-cols-7 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm border-t-4 border-t-[#5B2D8E] border border-gray-100 p-4">
        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Mi Cartera Total</div>
        <div class="text-2xl font-bold text-gray-800">S/ {{ number_format($carteraTotal, 2) }}</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border-t-4 border-t-green-500 border border-gray-100 p-4">
        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Vigente</div>
        <div class="text-2xl font-bold text-green-600">S/ {{ number_format($carteraVigente, 2) }}</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border-t-4 border-t-[#5B2D8E] border border-gray-100 p-4">
        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Vencida</div>
        <div class="text-2xl font-bold text-[#5B2D8E]">S/ {{ number_format($carteraVencida, 2) }}</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border-t-4 border-t-[#fbb034] border border-gray-100 p-4">
        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Ratio de Mora</div>
        <div class="text-2xl font-bold text-[#fbb034]">{{ number_format($ratioMora, 1) }}%</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border-t-4 border-t-red-400 border border-gray-100 p-4">
        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Créditos con Atraso</div>
        <div class="text-2xl font-bold text-red-500">{{ $creditosConAtraso }}</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border-t-4 border-t-[#5B2D8E] border border-gray-100 p-4">
        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">N° Créditos</div>
        <div class="text-2xl font-bold text-gray-800">{{ $numCreditos }}</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border-t-4 border-t-[#5B2D8E] border border-gray-100 p-4">
        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Clientes</div>
        <div class="text-2xl font-bold text-gray-800">{{ $numClientes }}</div>
    </div>
</div>

<div class="grid grid-cols-2 gap-6">
    <!-- LEFT COLUMN -->
    <div class="flex flex-col gap-6">
        <!-- Pie Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5 h-[340px]">
            <h2 class="text-sm font-bold text-gray-800 mb-4">Composición de mi cartera</h2>
            <div class="relative h-[250px] w-full flex justify-center">
                <canvas id="pieChart"></canvas>
            </div>
        </div>

        <!-- Bar Chart: Calificación -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5">
            <h2 class="text-sm font-bold text-gray-800 mb-4">Cartera por calificación</h2>
            
            <div class="space-y-4">
                @forelse($porCalificacion as $cal => $monto)
                @php
                    $pct = $carteraTotal > 0 ? ($monto / $carteraTotal) * 100 : 0;
                @endphp
                <div class="flex items-center gap-4">
                    <div class="w-4 text-xs font-bold text-gray-600">{{ $cal ?? 'S/C' }}</div>
                    <div class="flex-1 bg-gray-100 rounded-full h-3.5 overflow-hidden">
                        <div class="bg-slate-500 h-full rounded-full" style="width: {{ $pct }}%"></div>
                    </div>
                    <div class="w-24 text-right text-xs font-bold text-gray-800">S/ {{ number_format($monto, 2) }}</div>
                </div>
                @empty
                <div class="text-sm text-gray-400 text-center py-4">Sin datos de calificación</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- RIGHT COLUMN -->
    <div class="flex flex-col gap-6">
        <!-- Cumplimiento de Meta -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5 h-[340px]">
            <h2 class="text-sm font-bold text-gray-800 mb-6">Cumplimiento de meta</h2>
            
            <div class="mb-4">
                <label class="block text-xs text-gray-500 mb-1">Meta de colocaciones (S/)</label>
                <input type="number" id="inputMeta" placeholder="Ej. 6000000" class="w-64 border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:border-blue-400">
            </div>

            <div id="metaAviso" class="text-xs text-gray-400 mt-4">
                Asigna tu meta de colocaciones para ver el avance.
            </div>

            <div id="metaProgress" class="hidden mt-8">
                <div class="flex justify-between text-xs font-bold text-gray-600 mb-2">
                    <span>Avance actual</span>
                    <span id="metaPct">0%</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-5 overflow-hidden border border-gray-200">
                    <div id="metaBar" class="bg-[#5B2D8E] h-full transition-all duration-500" style="width: 0%"></div>
                </div>
                <div class="flex justify-between text-[10px] text-gray-400 mt-2">
                    <span>S/ {{ number_format($carteraTotal, 2) }} logrados</span>
                    <span id="metaFalta">S/ 0 restantes</span>
                </div>
            </div>
        </div>

        <!-- Cartera vencida por producto -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5">
            <h2 class="text-sm font-bold text-gray-800 mb-4">Cartera vencida por tipo de producto</h2>
            
            @if($moraPorProducto->isEmpty())
                <div class="text-sm text-gray-400 py-4">No hay cartera vencida registrada.</div>
            @else
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-400 uppercase border-b border-gray-100">
                        <tr>
                            <th class="pb-2 font-medium">Producto</th>
                            <th class="pb-2 font-medium text-right">Monto Vencido</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($moraPorProducto as $prod => $monto)
                        <tr>
                            <td class="py-3 font-medium text-gray-700">{{ $prod ?? 'Otros' }}</td>
                            <td class="py-3 text-right font-bold text-[#5B2D8E]">S/ {{ number_format($monto, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

<!-- Desembolsos del día y Solicitudes pendientes -->
<div class="grid grid-cols-2 gap-6 mt-6">
    <!-- Desembolsos del día -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-sm font-bold text-gray-800">Desembolsos del día</h2>
            <span class="text-xs font-bold text-[#5B2D8E] bg-purple-50 px-3 py-1 rounded-full">
                {{ $cantidadDesembolsosHoy }} crédito(s)
            </span>
        </div>
        <div class="text-2xl font-bold text-gray-800 mb-4">S/ {{ number_format($totalDesembolsadoHoy, 2) }}</div>

        @if($desembolsosHoy->isEmpty())
            <div class="text-sm text-gray-400 py-2">No hay desembolsos registrados hoy.</div>
        @else
            <div class="space-y-2 max-h-48 overflow-y-auto">
                @foreach($desembolsosHoy as $d)
                <div class="flex justify-between items-center py-2 border-b border-gray-50 last:border-0">
                    <div>
                        <div class="text-sm font-medium text-gray-700">{{ $d->codcuentacredito }}</div>
                        <div class="text-xs text-gray-400">{{ $d->nomcliente }}</div>
                    </div>
                    <div class="text-sm font-bold text-green-600">S/ {{ number_format($d->montocapitaldesembolsado, 2) }}</div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Solicitudes por estado -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5">
        <h2 class="text-sm font-bold text-gray-800 mb-4">Solicitudes en el sistema</h2>
        <div class="space-y-3">
            @foreach($solicitudesPorEstado as $estado)
            @php
                $colorMap = [
                    'En Evaluación' => 'bg-amber-400',
                    'En Comité' => 'bg-purple-400',
                    'Aprobado' => 'bg-green-400',
                    'Rechazado' => 'bg-red-400',
                    'Desembolsado' => 'bg-blue-400',
                    'Anulado' => 'bg-gray-400',
                ];
                $color = $colorMap[$estado->dessolicitudestado] ?? 'bg-gray-300';
            @endphp
            <div class="flex items-center gap-3">
                <div class="w-2.5 h-2.5 rounded-full {{ $color }}"></div>
                <div class="flex-1 text-sm text-gray-700">{{ $estado->dessolicitudestado }}</div>
                <div class="text-sm font-bold text-gray-800">{{ $estado->total }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    // Variables PHP a JS
    const valVigente = {{ $carteraVigente }};
    const valVencida = {{ $carteraVencida }};
    const totalCartera = {{ $carteraTotal }};

    // Gráfico de Pie
    const ctx = document.getElementById('pieChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Vigente', 'Vencida'],
            datasets: [{
                data: [valVigente, valVencida],
                backgroundColor: ['#5B2D8E', '#9E85B4'], // Colores morados Ripley
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 12, font: { size: 11 } }
                }
            }
        }
    });

    // Lógica Meta
    const inputMeta = document.getElementById('inputMeta');
    const metaAviso = document.getElementById('metaAviso');
    const metaProgress = document.getElementById('metaProgress');
    const metaPct = document.getElementById('metaPct');
    const metaBar = document.getElementById('metaBar');
    const metaFalta = document.getElementById('metaFalta');

    inputMeta.addEventListener('input', function() {
        const meta = parseFloat(this.value);
        if (meta > 0) {
            metaAviso.classList.add('hidden');
            metaProgress.classList.remove('hidden');

            let pct = (totalCartera / meta) * 100;
            if(pct > 100) pct = 100;

            metaPct.textContent = pct.toFixed(1) + '%';
            metaBar.style.width = pct + '%';
            
            const falta = Math.max(0, meta - totalCartera);
            metaFalta.textContent = 'S/ ' + falta.toLocaleString('es-PE', {minimumFractionDigits: 2}) + ' restantes';
        } else {
            metaAviso.classList.remove('hidden');
            metaProgress.classList.add('hidden');
        }
    });
</script>
@endsection
