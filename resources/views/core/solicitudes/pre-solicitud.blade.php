@extends('layouts.core')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Pre-solicitud</h1>
        <p class="text-sm text-gray-500 mt-1">Evalúa rápidamente la elegibilidad del cliente antes del registro formal</p>
    </div>

    @if($errors->any())
        <div class="bg-red-50 text-red-700 border border-red-200 rounded-lg px-4 py-3 text-sm mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <form method="POST" action="{{ route('core.presolicitud.evaluar') }}">
            @csrf
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-2">DNI del cliente</label>
                    <input type="text" name="dni" maxlength="8" placeholder="11200007"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm" value="{{ old('dni') }}" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-2">Monto a solicitar (S/)</label>
                    <input type="number" name="monto" step="0.01" min="500" max="100000" placeholder="5000"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm" value="{{ old('monto') }}" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-2">Ingreso mensual (S/)</label>
                    <input type="number" name="ingreso" step="0.01" min="0" placeholder="2500"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm" value="{{ old('ingreso') }}" required>
                </div>
            </div>
            <button type="submit" class="bg-ripley-purple text-white px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-opacity-90">
                Evaluar elegibilidad
            </button>
        </form>
    </div>

    @if(isset($evaluado) && $evaluado)
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="text-sm font-bold text-gray-700 mb-4 uppercase tracking-wide">Resultado de la evaluación</h2>

        <div class="mb-4">
            <div class="text-xs text-gray-400 mb-1">Cliente</div>
            <div class="text-gray-800 font-medium">{{ $cliente->nomcliente }}</div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
            <div>
                <div class="text-gray-400 text-xs mb-1">Ratio de endeudamiento estimado</div>
                <div class="text-gray-800 font-medium">{{ number_format($ratioEndeudamiento, 1) }}%</div>
            </div>
            <div>
                <div class="text-gray-400 text-xs mb-1">Historial de mora</div>
                <div class="text-gray-800 font-medium">{{ $tieneMora ? 'Tiene mora activa' : 'Sin mora registrada' }}</div>
            </div>
        </div>

        @if($elegible)
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                <div class="text-green-700 font-semibold text-sm">✓ Cliente elegible</div>
                <div class="text-green-600 text-xs mt-1">Puedes continuar con el registro formal de la solicitud.</div>
            </div>
            <a href="{{ route('core.solicitud.registrar', $cliente->pkcliente) }}" class="bg-ripley-purple text-white px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-opacity-90 inline-block">
                Continuar con el registro →
            </a>
        @else
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="text-red-700 font-semibold text-sm">✗ Cliente no elegible en este momento</div>
                <div class="text-red-600 text-xs mt-1">
                    @if($tieneMora)
                        El cliente tiene créditos en mora activa.
                    @else
                        El ratio de endeudamiento supera el límite permitido (30%).
                    @endif
                </div>
            </div>
        @endif
    </div>
    @endif
</div>
@endsection