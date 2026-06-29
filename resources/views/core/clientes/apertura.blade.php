@extends('layouts.core')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Apertura de Cliente Nuevo</h1>
        <p class="text-sm text-gray-500 mt-1">Registra un cliente nuevo y crea su acceso a Banca por Internet</p>
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

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <form method="POST" action="{{ route('core.clientes.apertura.store') }}">
            @csrf

            <div class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-3">Datos personales</div>
            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-600 mb-2">Nombre completo</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" placeholder="Apellidos, Nombres"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm" required>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-2">DNI</label>
                    <input type="text" name="dni" value="{{ old('dni') }}" maxlength="8" placeholder="12345678"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-2">Teléfono</label>
                    <input type="text" name="telefono" value="{{ old('telefono') }}" maxlength="9" placeholder="987654321"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                </div>
            </div>
            <div class="mb-6">
                <label class="block text-xs font-semibold text-gray-600 mb-2">Correo electrónico</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="cliente@correo.com"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
            </div>

            <div class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-3">Acceso a Banca por Internet</div>
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-2">Nombre de usuario</label>
                    <input type="text" name="username" value="{{ old('username') }}" placeholder="Ej: jperez01"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-2">Contraseña temporal</label>
                    <input type="text" name="password" placeholder="Mínimo 8 caracteres"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm" required>
                </div>
            </div>
            <p class="text-xs text-gray-400 mb-6">El cliente deberá cambiar esta contraseña en su primer ingreso a Banca por Internet.</p>

            <button type="submit" class="bg-ripley-purple text-white px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-opacity-90">
                Registrar cliente
            </button>
        </form>
    </div>
</div>
@endsection