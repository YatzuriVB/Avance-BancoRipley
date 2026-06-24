@php
    $pkcliente = auth()->user()->pkcliente;
    $clienteSidebar = $cliente ?? DB::table('dcliente')->where('pkcliente', $pkcliente)->first();
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil — Banco Ripley</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f7f5fb; display: flex; min-height: 100vh; }
        .sidebar { width: 220px; background: #5B2D8E; display: flex; flex-direction: column; position: fixed; top: 0; left: 0; height: 100vh; }
        .sidebar-logo { padding: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-logo-text { color: #fff; font-size: 16px; font-weight: 700; }
        .sidebar-logo-text span { color: #E30613; }
        .sidebar-user { padding: 14px 20px; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 10px; }
        .avatar { width: 34px; height: 34px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 12px; font-weight: 700; flex-shrink: 0; }
        .sidebar-user-name { color: #fff; font-size: 13px; font-weight: 500; }
        .sidebar-user-sub { color: rgba(255,255,255,0.5); font-size: 11px; }
        .nav-section { padding: 12px 0 4px; }
        .nav-label { color: rgba(255,255,255,0.35); font-size: 10px; font-weight: 600; letter-spacing: .06em; padding: 0 20px 6px; text-transform: uppercase; }
        .nav-item { display: flex; align-items: center; gap: 10px; padding: 9px 20px; color: rgba(255,255,255,0.65); font-size: 13px; cursor: pointer; text-decoration: none; }
        .nav-item:hover { background: rgba(255,255,255,0.08); color: #fff; }
        .nav-item.active { background: rgba(255,255,255,0.12); color: #fff; }
        .nav-item i { font-size: 16px; }
        .sidebar-bottom { margin-top: auto; padding: 16px 20px; border-top: 1px solid rgba(255,255,255,0.1); }
        .nav-logout { display: flex; align-items: center; gap: 10px; color: rgba(255,255,255,0.5); font-size: 13px; cursor: pointer; text-decoration: none; background: none; border: none; width: 100%; }
        .nav-logout:hover { color: #fff; }
        .main { margin-left: 220px; flex: 1; padding: 28px; }
        .page-header { margin-bottom: 24px; }
        .page-title { font-size: 22px; font-weight: 700; color: #1a1a2e; }
        .page-sub { font-size: 13px; color: #888; margin-top: 4px; }
        .card { background: #fff; border-radius: 12px; border: 1px solid #e8e0f0; padding: 24px; margin-bottom: 20px; }
        .card-title { font-size: 15px; font-weight: 700; color: #1a1a2e; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; }
        .card-title i { color: #5B2D8E; font-size: 18px; }
        .avatar-lg { width: 72px; height: 72px; background: linear-gradient(135deg, #5B2D8E, #4a2275); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 26px; font-weight: 700; flex-shrink: 0; }
        .profile-header { display: flex; align-items: center; gap: 20px; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid #f5f0ff; }
        .profile-name { font-size: 20px; font-weight: 700; color: #1a1a2e; }
        .profile-cod { font-size: 13px; color: #5B2D8E; font-weight: 600; margin-top: 2px; }
        .profile-user { font-size: 12px; color: #888; margin-top: 2px; }
        .info-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
        .info-item label { font-size: 11px; color: #888; text-transform: uppercase; letter-spacing: .04em; display: block; margin-bottom: 4px; }
        .info-item .val { font-size: 14px; color: #1a1a2e; font-weight: 500; }
        .form-group { margin-bottom: 16px; }
        .form-group label { font-size: 13px; color: #555; font-weight: 500; display: block; margin-bottom: 6px; }
        .form-control { width: 100%; padding: 10px 14px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; color: #1a1a2e; outline: none; transition: border .15s; }
        .form-control:focus { border-color: #5B2D8E; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .btn-primary { background: #5B2D8E; color: #fff; border: none; padding: 10px 24px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: background .15s; }
        .btn-primary:hover { background: #4a2275; }
        .alert { padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 16px; }
        .alert-success { background: #e8f5ee; color: #1e7e4c; border: 1px solid #b6dfc8; }
        .alert-error { background: #fde8e8; color: #c0392b; border: 1px solid #f5c6c6; }
        .error-msg { font-size: 12px; color: #E30613; margin-top: 4px; }
    </style>
</head>
<body>
@php $cliente = $clienteSidebar; @endphp
@include('partials.sidebar')

<div class="main">
    <div class="page-header">
        <div class="page-title">Mi Perfil</div>
        <div class="page-sub">Información personal y configuración de seguridad</div>
    </div>

    @if(session('success'))
        <div class="alert alert-success"><i class="ti ti-circle-check"></i> {{ session('success') }}</div>
    @endif

    <!-- Datos personales -->
    <div class="card">
        <div class="profile-header">
            <div class="avatar-lg">{{ strtoupper(substr($cliente->nomcliente ?? 'CL', 0, 2)) }}</div>
            <div>
                <div class="profile-name">{{ $cliente->nomcliente ?? 'Sin nombre' }}</div>
                <div class="profile-cod">{{ $cliente->codcliente ?? '' }}</div>
                <div class="profile-user">Usuario: {{ $usuario->username }}</div>
            </div>
        </div>

        <div class="card-title"><i class="ti ti-id-badge"></i> Información Personal</div>
        <div class="info-grid">
            <div class="info-item">
                <label>Tipo de documento</label>
                <div class="val">{{ $cliente->destipodocumentoidentidad ?? 'DNI' }}</div>
            </div>
            <div class="info-item">
                <label>Número de documento</label>
                <div class="val">{{ $cliente->numerodocumentoidentidad ?? '—' }}</div>
            </div>
            <div class="info-item">
                <label>Fecha de nacimiento</label>
                <div class="val">{{ $cliente->fechanacimiento ? \Carbon\Carbon::parse($cliente->fechanacimiento)->format('d/m/Y') : '—' }}</div>
            </div>
            <div class="info-item">
                <label>Correo electrónico</label>
                <div class="val">{{ $cliente->email ?? '—' }}</div>
            </div>
            <div class="info-item">
                <label>Teléfono personal</label>
                <div class="val">{{ $cliente->numerotelefonopersonal ?? $cliente->telefono ?? '—' }}</div>
            </div>
            <div class="info-item">
                <label>Cliente desde</label>
                <div class="val">{{ $cliente->fechaingresocaja ? \Carbon\Carbon::parse($cliente->fechaingresocaja)->format('d/m/Y') : '—' }}</div>
            </div>
            @if($cliente->desdistrito)
            <div class="info-item" style="grid-column: span 3;">
                <label>Ubicación</label>
                <div class="val">{{ $cliente->desdistrito }}, {{ $cliente->desprovincia }}, {{ $cliente->desdepartamento }}</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Cambiar contraseña -->
    <div class="card">
        <div class="card-title"><i class="ti ti-lock"></i> Cambiar Contraseña</div>
        <form method="POST" action="{{ route('perfil.password') }}" style="max-width: 500px;">
            @csrf
            @method('PATCH')
            <div class="form-group">
                <label>Contraseña actual</label>
                <input type="password" name="password_actual" class="form-control" placeholder="••••••••">
                @error('password_actual') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Nueva contraseña</label>
                    <input type="password" name="password_nuevo" class="form-control" placeholder="••••••••">
                    @error('password_nuevo') <div class="error-msg">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label>Confirmar contraseña</label>
                    <input type="password" name="password_nuevo_confirmation" class="form-control" placeholder="••••••••">
                </div>
            </div>
            <button type="submit" class="btn-primary"><i class="ti ti-device-floppy"></i> Actualizar contraseña</button>
        </form>
    </div>
</div>
</body>
</html>
