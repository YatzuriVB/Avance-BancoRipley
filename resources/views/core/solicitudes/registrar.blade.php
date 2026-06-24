@extends('layouts.core')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Nueva Solicitud de Crédito</h1>
        <p class="text-sm text-gray-500 mt-1">Cliente: {{ $cliente->nomcliente }} · DNI: {{ $cliente->numerodocumentoidentidad }}</p>
    </div>

    @if($errors->any())
        <div class="bg-red-50 text-red-700 border border-red-200 rounded-lg px-4 py-3 text-sm mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <form method="POST" action="{{ route('core.solicitud.registrar.store', $cliente->pkcliente) }}">
            @csrf
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-2">Tipo de crédito</label>
                    <select name="tipo_credito" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm" required>
                        <option value="">Selecciona...</option>
                        <option value="CO">Crédito de Consumo</option>
                        <option value="ME">Crédito Microempresa</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-2">Monto solicitado (S/)</label>
                    <input type="number" name="monto" step="0.01" min="500" max="100000" placeholder="Ej: 5000"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm" required>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-2">Plazo (meses)</label>
                    <input type="number" name="plazo" min="6" max="60" placeholder="Ej: 24"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-2">Ingreso mensual neto (S/)</label>
                    <input type="number" name="ingreso" step="0.01" min="0" placeholder="Ej: 2500"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm" required>
                </div>
            </div>
            <div class="mb-6">
                <label class="block text-xs font-semibold text-gray-600 mb-2">Actividad económica</label>
                <select name="actividad" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm" required>
                    <option value="">Selecciona...</option>
                    @foreach($actividades as $act)
                        <option value="{{ $act->pkactividadeconomica }}">{{ $act->desactividadeconomica }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-ripley-purple text-white px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-opacity-90">
                Registrar solicitud
            </button>
        </form>
    </div>
</div>
@endsection