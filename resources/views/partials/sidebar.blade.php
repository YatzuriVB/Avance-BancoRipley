@php
    $currentRoute = request()->route()->getName();
@endphp

<div class="sidebar">
    <div class="sidebar-logo">
        <div class="sidebar-logo-text">banco ripley<span>.</span></div>
    </div>
    <div class="sidebar-user">
        <div class="avatar">{{ strtoupper(substr($cliente->nomcliente ?? 'CL', 0, 2)) }}</div>
        <div>
            <div class="sidebar-user-name">{{ explode(',', $cliente->nomcliente ?? 'Cliente')[0] }}</div>
            <div class="sidebar-user-sub">{{ auth()->user()->username }}</div>
        </div>
    </div>
    <div class="nav-section">
        <div class="nav-label">Principal</div>
        <a href="{{ route('dashboard') }}" class="nav-item {{ in_array($currentRoute, ['dashboard']) ? 'active' : '' }}">
            <i class="ti ti-layout-dashboard"></i> Inicio
        </a>
        <a href="{{ route('cuenta.index') }}" class="nav-item {{ str_starts_with($currentRoute ?? '', 'cuenta') ? 'active' : '' }}">
            <i class="ti ti-building-bank"></i> Cuentas de Ahorro
        </a>
        <a href="{{ route('credito.index') }}" class="nav-item {{ str_starts_with($currentRoute ?? '', 'credito') ? 'active' : '' }}">
            <i class="ti ti-credit-card"></i> Mis Créditos
        </a>
        </a>
        <a href="{{ route('transferencias') }}" class="nav-item {{ $currentRoute === 'transferencias' ? 'active' : '' }}">
            <i class="ti ti-arrows-exchange"></i> Transferencias
        </a>
        <a href="{{ route('pagos.servicios') }}" class="nav-item {{ $currentRoute === 'pagos.servicios' ? 'active' : '' }}">
            <i class="ti ti-receipt"></i> Pago de servicios
        </a>
    </div>
    <div class="nav-section">
        <div class="nav-label">Préstamos</div>
        <a href="{{ route('credito.simulador') }}" class="nav-item {{ $currentRoute === 'credito.simulador' ? 'active' : '' }}">
            <i class="ti ti-calculator"></i> Simulador
        </a>
        <a href="{{ route('credito.solicitar') }}" class="nav-item {{ $currentRoute === 'credito.solicitar' ? 'active' : '' }}">
            <i class="ti ti-file-plus"></i> Solicitar crédito
        </a>
        <a href="{{ route('pagos.credito') }}" class="nav-item {{ $currentRoute === 'pagos.credito' ? 'active' : '' }}">
            <i class="ti ti-calendar-due"></i> Pago de crédito
        </a>
    </div>
    <div class="nav-section">
        <div class="nav-label">Mi cuenta</div>
        <a href="{{ route('perfil') }}" class="nav-item {{ $currentRoute === 'perfil' ? 'active' : '' }}">
            <i class="ti ti-user"></i> Perfil
        </a>
    </div>
    <div class="sidebar-bottom">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-logout">
                <i class="ti ti-logout"></i> Cerrar sesión
            </button>
        </form>
    </div>
</div>
