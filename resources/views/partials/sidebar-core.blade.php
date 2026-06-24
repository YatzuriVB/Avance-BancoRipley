@php
    $cargoActual = trim(Session::get('core_cargo') ?? '');
    $puedeAsesor = $cargoActual === 'Asesor de Negocios';
    $puedeComite = $cargoActual === 'Funcionario de Créditos';
    $puedeAdmin = $cargoActual === 'Administrador de Agencia';
    $puedeAnalista = $cargoActual === 'Analista de Créditos';
@endphp
<aside class="w-64 bg-white border-r border-gray-200 flex flex-col shadow-sm">
    <div class="py-4">
        <!-- PRINCIPAL -->
        <div class="px-6 text-xs font-semibold text-gray-400 mb-2 uppercase tracking-wider">Principal</div>
        <a href="{{ route('core.dashboard') }}" class="flex items-center px-6 py-2.5 bg-ripley-purple text-white font-medium">
            <i class="ti ti-layout-dashboard text-lg mr-3"></i> Dashboard
        </a>

        <!-- OTORGAMIENTO -->
        <div class="px-6 text-xs font-semibold text-gray-400 mt-6 mb-2 uppercase tracking-wider">Otorgamiento de créditos</div>

        @if($puedeAsesor)
        <a href="{{ route('core.solicitudes.bandeja') }}" class="flex items-center px-6 py-2.5 text-gray-600 hover:bg-gray-50 transition">
            <i class="ti ti-inbox text-lg text-gray-400 mr-3"></i> Bandeja de solicitudes
        </a>
        <a href="{{ route('core.presolicitud') }}" class="flex items-center px-6 py-2.5 text-gray-600 hover:bg-gray-50 transition">
            <i class="ti ti-search text-lg text-gray-400 mr-3"></i> 1. Pre-solicitud
        </a>
        <a href="{{ route('core.solicitud.buscar.form') }}" class="flex items-center px-6 py-2.5 text-gray-600 hover:bg-gray-50 transition">
            <i class="ti ti-file-text text-lg text-gray-400 mr-3"></i> 2. Registro de solicitud
        </a>
        @endif

        @if($puedeComite)
        <a href="{{ route('core.solicitudes.comite') }}" class="flex items-center px-6 py-2.5 text-gray-600 hover:bg-gray-50 transition">
            <i class="ti ti-users text-lg text-gray-400 mr-3"></i> 3. Propuesta y comité
        </a>
        @endif

        @if($puedeComite || $puedeAdmin)
        <a href="{{ route('core.solicitudes.desembolso') }}" class="flex items-center px-6 py-2.5 text-gray-600 hover:bg-gray-50 transition">
            <i class="ti ti-check text-lg text-gray-400 mr-3"></i> 4. Aprobación y desembolso
        </a>
        @endif

        @if($puedeAdmin)
        <div class="px-6 text-xs font-semibold text-gray-400 mt-6 mb-2 uppercase tracking-wider">Gestión</div>
        <a href="{{ route('core.cuentas.gestion') }}" class="flex items-center px-6 py-2.5 text-gray-600 hover:bg-gray-50 transition">
            <i class="ti ti-settings text-lg text-gray-400 mr-3"></i> Gestión de cuentas
        </a>
        @endif

        @if($puedeAdmin || $puedeAnalista || $puedeComite)
        <a href="{{ route('core.solicitudes.mora') }}" class="flex items-center px-6 py-2.5 text-gray-600 hover:bg-gray-50 transition">
            <i class="ti ti-alert-triangle text-lg text-gray-400 mr-3"></i> 5. Mora y recuperación
        </a>
        @endif

    </div>
</aside>