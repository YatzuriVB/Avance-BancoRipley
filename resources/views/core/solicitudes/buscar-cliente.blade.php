@extends('layouts.core')

@section('content')
<div class="max-w-lg">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Registro de Solicitud</h1>
        <p class="text-sm text-gray-500 mt-1">Busca al cliente para registrar una nueva solicitud de crédito</p>
    </div>

    @if($errors->any())
        <div class="bg-red-50 text-red-700 border border-red-200 rounded-lg px-4 py-3 text-sm mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <form method="POST" action="{{ route('core.solicitud.buscar') }}">
            @csrf
            <label class="block text-xs font-semibold text-gray-600 mb-2">DNI del cliente</label>
            <input type="text" name="dni" maxlength="8" placeholder="Ej: 11200007"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm mb-4 focus:outline-none focus:border-ripley-purple"
                   value="{{ old('dni') }}" required>
            <button type="submit" class="bg-ripley-purple text-white px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-opacity-90 w-full">
                Buscar cliente
            </button>
        </form>
    </div>
</div>
@endsection