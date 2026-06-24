@extends('layouts.core')

@section('content')
<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Evaluación de Solicitud</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $solicitud->codsolicitud }}</p>
        </div>
        <a href="{{ route('core.solicitudes.bandeja') }}" class="text-sm text-ripley-purple hover:underline">
            ← Volver a la bandeja
        </a>
    </div>

    <div class="grid grid-cols-3 gap-6">
        <!-- Columna izquierda: datos -->
        <div class="col-span-2 space-y-4">

            <!-- Datos del cliente -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-sm font-bold text-gray-700 mb-4 uppercase tracking-wide">Datos del Cliente</h2>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <div class="text-gray-400 text-xs mb-1">Nombre completo</div>
                        <div class="text-gray-800 font-medium">{{ $solicitud->nomcliente }}</div>
                    </div>
                    <div>
                        <div class="text-gray-400 text-xs mb-1">DNI</div>
                        <div class="text-gray-800 font-medium">{{ $solicitud->numerodocumentoidentidad }}</div>
                    </div>
                    <div>
                        <div class="text-gray-400 text-xs mb-1">Correo</div>
                        <div class="text-gray-800 font-medium">{{ $solicitud->email ?? '—' }}</div>
                    </div>
                    <div>
                        <div class="text-gray-400 text-xs mb-1">Teléfono</div>
                        <div class="text-gray-800 font-medium">{{ $solicitud->telefono ?? '—' }}</div>
                    </div>
                </div>
            </div>

            <!-- Datos de la solicitud -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-sm font-bold text-gray-700 mb-4 uppercase tracking-wide">Datos de la Solicitud</h2>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <div class="text-gray-400 text-xs mb-1">Tipo de crédito</div>
                        <div class="text-gray-800 font-medium">{{ $solicitud->destipocredito }}</div>
                    </div>
                    <div>
                        <div class="text-gray-400 text-xs mb-1">Producto</div>
                        <div class="text-gray-800 font-medium">{{ $solicitud->desproducto }}</div>
                    </div>
                    <div>
                        <div class="text-gray-400 text-xs mb-1">Monto solicitado</div>
                        <div class="text-gray-800 font-medium">S/ {{ number_format($solicitud->montosolicitudcredito, 2) }}</div>
                    </div>
                    <div>
                        <div class="text-gray-400 text-xs mb-1">Plazo</div>
                        <div class="text-gray-800 font-medium">{{ $solicitud->plazosolicitudcredito }} meses</div>
                    </div>
                    <div>
                        <div class="text-gray-400 text-xs mb-1">Fecha de solicitud</div>
                        <div class="text-gray-800 font-medium">{{ \Carbon\Carbon::parse($solicitud->fechasolicitudcredito)->format('d/m/Y') }}</div>
                    </div>
                    <div>
                        <div class="text-gray-400 text-xs mb-1">Actividad económica</div>
                        <div class="text-gray-800 font-medium">{{ $solicitud->desactividadeconomica ?? '—' }}</div>
                    </div>
                </div>
            </div>

            <!-- Evaluación crediticia -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-sm font-bold text-gray-700 mb-4 uppercase tracking-wide">Evaluación Crediticia</h2>
                <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                    <div>
                        <div class="text-gray-400 text-xs mb-1">Ingreso mensual declarado</div>
                        <div class="text-gray-800 font-medium">
                            S/ {{ number_format($ingreso->montofuenteingreso ?? $solicitud->montoingresoneto ?? 0, 2) }}
                        </div>
                    </div>
                    <div>
                        <div class="text-gray-400 text-xs mb-1">Cuota estimada</div>
                        <div class="text-gray-800 font-medium">
                            S/ {{ number_format($solicitud->montosolicitudcredito / max($solicitud->plazosolicitudcredito, 1), 2) }}
                        </div>
                    </div>
                </div>
                <form method="POST" action="{{ route('core.solicitud.evaluar', $solicitud->pksolicitud) }}">
                    @csrf
                    <label class="block text-xs text-gray-500 mb-1">Comentario de evaluación</label>
                    <textarea name="comentario" rows="3" class="w-full border border-gray-200 rounded-lg p-3 text-sm mb-4" placeholder="Observaciones del asesor..."></textarea>

                    <div class="flex gap-3">
                        <button type="submit" name="accion" value="comite" class="bg-ripley-purple text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-opacity-90">
                            Enviar a Comité
                        </button>
                        <button type="submit" name="accion" value="rechazar" class="bg-red-50 text-red-600 border border-red-200 px-5 py-2 rounded-lg text-sm font-semibold hover:bg-red-100">
                            Rechazar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Columna derecha: estado -->
        <div class="space-y-4">
            <div class="bg-white rounded-xl border border-gray-200 p-6 text-center">
                <div class="text-xs text-gray-400 mb-2">Estado actual</div>
                @php
                    $badge = match(trim($solicitud->codsolicitudestado)) {
                        '01' => ['bg-amber-100', 'text-amber-700'],
                        '06' => ['bg-purple-100', 'text-purple-700'],
                        '02' => ['bg-green-100', 'text-green-700'],
                        '03' => ['bg-red-100', 'text-red-700'],
                        '04' => ['bg-blue-100', 'text-blue-700'],
                        default => ['bg-gray-100', 'text-gray-700'],
                    };
                @endphp
                <span class="text-sm font-semibold px-4 py-2 rounded-full {{ $badge[0] }} {{ $badge[1] }} inline-block">
                    {{ $solicitud->dessolicitudestado }}
                </span>
            </div>
        </div>
    </div>
</div>
@endsection