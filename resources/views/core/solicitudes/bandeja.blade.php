@extends('layouts.core')

@section('content')
<div>
    @if(session('success'))
        <div class="bg-green-50 text-green-700 border border-green-200 rounded-lg px-4 py-3 text-sm mb-4">
            {{ session('success') }}
        </div>
    @endif
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Bandeja de Solicitudes</h1>
        <p class="text-sm text-gray-500 mt-1">Solicitudes de crédito asignadas a tu cartera</p>
    </div>

    <!-- Resumen -->
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="text-xs text-gray-500 mb-1">En Evaluación</div>
            <div class="text-2xl font-bold text-amber-600">{{ $totalEvaluacion }}</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="text-xs text-gray-500 mb-1">En Comité</div>
            <div class="text-2xl font-bold text-ripley-purple">{{ $totalComite }}</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="text-xs text-gray-500 mb-1">Aprobadas</div>
            <div class="text-2xl font-bold text-green-600">{{ $totalAprobado }}</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="text-xs text-gray-500 mb-1">Rechazadas</div>
            <div class="text-2xl font-bold text-red-600">{{ $totalRechazado }}</div>
        </div>
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Solicitud</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Cliente</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Tipo</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Monto</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Plazo</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Fecha</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Estado</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Acción</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($solicitudes as $sol)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $sol->codsolicitud }}</td>
                    <td class="px-5 py-3">
                        <div class="text-gray-800">{{ $sol->nomcliente }}</div>
                        <div class="text-xs text-gray-400">DNI: {{ $sol->numerodocumentoidentidad }}</div>
                    </td>
                    <td class="px-5 py-3 text-gray-600">{{ $sol->destipocredito }}</td>
                    <td class="px-5 py-3 text-right font-medium text-gray-800">S/ {{ number_format($sol->montosolicitudcredito, 2) }}</td>
                    <td class="px-5 py-3 text-center text-gray-600">{{ $sol->plazosolicitudcredito }} meses</td>
                    <td class="px-5 py-3 text-gray-600">{{ \Carbon\Carbon::parse($sol->fechasolicitudcredito)->format('d/m/Y') }}</td>
                    <td class="px-5 py-3 text-center">
                        @php
                            $badge = match(trim($sol->codsolicitudestado)) {
                                '01' => ['bg-amber-100', 'text-amber-700'],
                                '06' => ['bg-purple-100', 'text-purple-700'],
                                '02' => ['bg-green-100', 'text-green-700'],
                                '03' => ['bg-red-100', 'text-red-700'],
                                '04' => ['bg-blue-100', 'text-blue-700'],
                                default => ['bg-gray-100', 'text-gray-700'],
                            };
                        @endphp
                        <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $badge[0] }} {{ $badge[1] }}">
                            {{ $sol->dessolicitudestado }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-center">
                        <a href="{{ route('core.solicitud.show', $sol->pksolicitud) }}" class="text-ripley-purple text-sm font-medium hover:underline">
                            Evaluar →
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-5 py-10 text-center text-gray-400">No tienes solicitudes asignadas</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection