@extends('layouts.core')

@section('content')
<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Propuesta y Comité</h1>
        <p class="text-sm text-gray-500 mt-1">Solicitudes pendientes de resolución por el comité de créditos</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 text-green-700 border border-green-200 rounded-lg px-4 py-3 text-sm mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-4">
        @forelse($solicitudes as $sol)
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <div class="font-bold text-gray-800">{{ $sol->codsolicitud }}</div>
                    <div class="text-sm text-gray-500 mt-1">{{ $sol->nomcliente }} · DNI: {{ $sol->numerodocumentoidentidad }}</div>
                </div>
                <span class="text-xs font-semibold px-3 py-1 rounded-full bg-purple-100 text-purple-700">
                    {{ $sol->dessolicitudestado }}
                </span>
            </div>

            <div class="grid grid-cols-4 gap-4 mb-4 text-sm">
                <div>
                    <div class="text-gray-400 text-xs mb-1">Tipo</div>
                    <div class="text-gray-800 font-medium">{{ $sol->destipocredito }}</div>
                </div>
                <div>
                    <div class="text-gray-400 text-xs mb-1">Monto</div>
                    <div class="text-gray-800 font-medium">S/ {{ number_format($sol->montosolicitudcredito, 2) }}</div>
                </div>
                <div>
                    <div class="text-gray-400 text-xs mb-1">Plazo</div>
                    <div class="text-gray-800 font-medium">{{ $sol->plazosolicitudcredito }} meses</div>
                </div>
                <div>
                    <div class="text-gray-400 text-xs mb-1">Fecha solicitud</div>
                    <div class="text-gray-800 font-medium">{{ \Carbon\Carbon::parse($sol->fechasolicitudcredito)->format('d/m/Y') }}</div>
                </div>
            </div>

            <form method="POST" action="{{ route('core.solicitud.resolver', $sol->pksolicitud) }}">
                @csrf
                <textarea name="comentario" rows="2" class="w-full border border-gray-200 rounded-lg p-3 text-sm mb-3" placeholder="Comentario del comité (opcional)..."></textarea>
                <div class="flex gap-3">
                    <button type="submit" name="accion" value="aprobar" class="bg-green-600 text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-green-700">
                        Aprobar
                    </button>
                    <button type="submit" name="accion" value="rechazar" class="bg-red-50 text-red-600 border border-red-200 px-5 py-2 rounded-lg text-sm font-semibold hover:bg-red-100">
                        Rechazar
                    </button>
                    <a href="{{ route('core.solicitud.show', $sol->pksolicitud) }}" class="text-sm text-ripley-purple self-center hover:underline ml-2">
                        Ver detalle completo →
                    </a>
                </div>
            </form>
        </div>
        @empty
        <div class="bg-white rounded-xl border border-gray-200 p-10 text-center text-gray-400">
            No hay solicitudes pendientes en comité
        </div>
        @endforelse
    </div>
</div>
@endsection