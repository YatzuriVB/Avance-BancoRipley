@extends('layouts.core')

@section('content')
<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Gestión de Cuentas</h1>
        <p class="text-sm text-gray-500 mt-1">Busca y administra el estado de las cuentas de ahorro de los clientes</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 text-green-700 border border-green-200 rounded-lg px-4 py-3 text-sm mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('core.cuentas.gestion') }}" class="flex gap-3">
            <input type="text" name="dni" maxlength="8" placeholder="Buscar por DNI del cliente"
                   value="{{ $dniQuery }}"
                   class="flex-1 border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
            <button type="submit" class="bg-ripley-purple text-white px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-opacity-90">
                Buscar
            </button>
        </form>
    </div>

    @if($dniQuery)
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Cuenta</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Cliente</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Saldo</th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Estado</th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($cuentas as $c)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 font-medium text-gray-800">{{ $c->codcuentaahorro }}</td>
                        <td class="px-5 py-3">
                            <div class="text-gray-800">{{ $c->nomcliente }}</div>
                            <div class="text-xs text-gray-400">DNI: {{ $c->numerodocumentoidentidad }}</div>
                        </td>
                        <td class="px-5 py-3 text-right font-medium text-gray-800">S/ {{ number_format($c->montosaldodisponible_ac, 2) }}</td>
                        <td class="px-5 py-3 text-center">
                            @php
                                $badge = match(trim($c->desestadocuenta)) {
                                    'Activa' => ['bg-green-100', 'text-green-700'],
                                    'Inactiva' => ['bg-gray-100', 'text-gray-700'],
                                    'Bloqueada' => ['bg-red-100', 'text-red-700'],
                                    'Cerrada' => ['bg-gray-200', 'text-gray-500'],
                                    default => ['bg-amber-100', 'text-amber-700'],
                                };
                            @endphp
                            <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $badge[0] }} {{ $badge[1] }}">
                                {{ $c->desestadocuenta }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <form method="POST" action="{{ route('core.cuenta.cambiarestado', $c->pkcuentaahorro) }}" class="flex gap-2 justify-center items-center">
                                @csrf
                                <input type="hidden" name="dni" value="{{ $dniQuery }}">
                                <select name="nuevo_estado" class="text-xs border border-gray-300 rounded-lg px-2 py-1.5">
                                    <option value="01">Activar</option>
                                    <option value="03">Bloquear</option>
                                    <option value="02">Inactivar</option>
                                    <option value="04">Cerrar</option>
                                </select>
                                <button type="submit" class="text-xs bg-ripley-purple text-white px-3 py-1.5 rounded-lg font-semibold hover:bg-opacity-90">
                                    Aplicar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-gray-400">No se encontraron cuentas para ese DNI</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection