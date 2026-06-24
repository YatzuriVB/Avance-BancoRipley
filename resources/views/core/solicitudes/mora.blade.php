@extends('layouts.core')

@section('content')
<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Bandeja de Mora</h1>
        <p class="text-sm text-gray-500 mt-1">Créditos en mora de tu cartera asignada</p>
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

    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="text-xs text-gray-500 mb-1">Cartera en mora</div>
            <div class="text-2xl font-bold text-red-600">S/ {{ number_format($totalMora, 2) }}</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="text-xs text-gray-500 mb-1">Elegibles a judicial (≥121 días)</div>
            <div class="text-2xl font-bold text-amber-600">{{ $puedeJudicial }}</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="text-xs text-gray-500 mb-1">Elegibles a castigo (&gt;180 días)</div>
            <div class="text-2xl font-bold text-gray-700">{{ $puedeCastigar }}</div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Crédito</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Cliente</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Tipo</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Monto vencido</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Días atraso</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Acción</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($creditosMora as $c)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $c->codcuentacredito }}</td>
                    <td class="px-5 py-3">
                        <div class="text-gray-800">{{ $c->nomcliente }}</div>
                        <div class="text-xs text-gray-400">DNI: {{ $c->numerodocumentoidentidad }}</div>
                    </td>
                    <td class="px-5 py-3 text-gray-600">{{ $c->destipocredito }}</td>
                    <td class="px-5 py-3 text-right font-medium text-red-600">S/ {{ number_format($c->montosaldovencido, 2) }}</td>
                    <td class="px-5 py-3 text-center">
                        @php
                            $diasClass = $c->diasatrasocredito > 180 ? 'bg-gray-700 text-white' : ($c->diasatrasocredito >= 121 ? 'bg-amber-100 text-amber-700' : 'bg-yellow-50 text-yellow-700');
                        @endphp
                        <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $diasClass }}">
                            {{ $c->diasatrasocredito }} días
                        </span>
                    </td>
                    <td class="px-5 py-3 text-center">
                        <div class="flex gap-2 justify-center">
                            @if($c->diasatrasocredito >= 121 && $c->flagjudicial !== 'S')
                            <form method="POST" action="{{ route('core.mora.judicial', $c->pkcuentacredito) }}" onsubmit="return confirm('¿Derivar a cobranza judicial?')">
                                @csrf
                                <button type="submit" class="text-xs bg-amber-100 text-amber-700 px-3 py-1.5 rounded-lg font-semibold hover:bg-amber-200">
                                    Derivar judicial
                                </button>
                            </form>
                            @endif
                            @if($c->diasatrasocredito > 180 && $c->flagcastigado !== 'S')
                            <form method="POST" action="{{ route('core.mora.castigar', $c->pkcuentacredito) }}" onsubmit="return confirm('¿Castigar este crédito? Esta acción es irreversible.')">
                                @csrf
                                <button type="submit" class="text-xs bg-gray-700 text-white px-3 py-1.5 rounded-lg font-semibold hover:bg-gray-800">
                                    Castigar
                                </button>
                            </form>
                            @endif
                            @if($c->flagjudicial === 'S')
                                <span class="text-xs text-amber-600 font-semibold">En judicial</span>
                            @endif
                            @if($c->flagcastigado === 'S')
                                <span class="text-xs text-gray-500 font-semibold">Castigado</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-10 text-center text-gray-400">No tienes créditos en mora en tu cartera</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection