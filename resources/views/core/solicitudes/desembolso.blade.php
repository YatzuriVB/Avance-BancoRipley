@extends('layouts.core')

@section('content')
<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Aprobación y Desembolso</h1>
        <p class="text-sm text-gray-500 mt-1">Créditos aprobados pendientes de desembolso</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 text-green-700 border border-green-200 rounded-lg px-4 py-3 text-sm mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="bg-red-50 text-red-700 border border-red-200 rounded-lg px-4 py-3 text-sm mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Solicitud</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Cliente</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Tipo</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Monto aprobado</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Plazo</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Fecha aprobación</th>
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
                    <td class="px-5 py-3 text-right font-medium text-gray-800">S/ {{ number_format($sol->montoaprobadocredito, 2) }}</td>
                    <td class="px-5 py-3 text-center text-gray-600">{{ $sol->plazoaprobadocredito }} meses</td>
                    <td class="px-5 py-3 text-gray-600">{{ \Carbon\Carbon::parse($sol->fechaaprobacioncredito)->format('d/m/Y') }}</td>
                    <td class="px-5 py-3 text-center">
                        <form method="POST" action="{{ route('core.solicitud.desembolsar', $sol->pksolicitud) }}" onsubmit="return confirm('¿Confirmas el desembolso de S/ {{ number_format($sol->montoaprobadocredito, 2) }}?')">
                            @csrf
                            <button type="submit" class="bg-ripley-purple text-white px-4 py-1.5 rounded-lg text-xs font-semibold hover:bg-opacity-90">
                                Desembolsar
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-10 text-center text-gray-400">No hay créditos aprobados pendientes de desembolso</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection