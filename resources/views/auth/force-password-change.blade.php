<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualiza tu contraseña — Banco Ripley</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #f7f5fb; }
        .card { background: #fff; border-radius: 16px; border: 1px solid #e8e0f0; padding: 36px; width: 100%; max-width: 420px; }
        .logo { color: #5B2D8E; font-size: 18px; font-weight: 700; margin-bottom: 20px; }
        .logo span { color: #E30613; }
        .alert-icon {
            width: 48px; height: 48px; background: #faeeda; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; margin-bottom: 16px;
        }
        .alert-icon svg { width: 24px; height: 24px; }
        .card-title { font-size: 20px; font-weight: 700; color: #1a1a2e; margin-bottom: 6px; }
        .card-sub { font-size: 13px; color: #888; margin-bottom: 24px; line-height: 1.5; }
        .field { margin-bottom: 16px; }
        .field label { display: block; font-size: 12px; font-weight: 600; color: #444; margin-bottom: 6px; }
        .field input {
            width: 100%; padding: 10px 14px; border: 1.5px solid #e0d8ee;
            border-radius: 8px; font-size: 14px; outline: none;
        }
        .field input:focus { border-color: #5B2D8E; }
        .requirements { background: #f7f5fb; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; }
        .requirements div { font-size: 12px; color: #666; margin-bottom: 4px; }
        .requirements div:last-child { margin-bottom: 0; }
        .error { color: #dc2626; font-size: 12px; margin-top: 4px; }
        .btn-main {
            width: 100%; padding: 12px; background: #5B2D8E; color: #fff;
            border: none; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer;
        }
        .btn-main:hover { background: #4a2275; }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">banco ripley<span>.</span></div>
        <div class="alert-icon">
            <svg fill="none" stroke="#ba7517" stroke-width="2" viewBox="0 0 24 24"><path d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </div>
        <div class="card-title">Actualiza tu contraseña</div>
        <div class="card-sub">Por tu seguridad, debes establecer una nueva contraseña antes de continuar. La contraseña temporal ya no podrá usarse.</div>

        <div class="requirements">
            <div>✓ Mínimo 8 caracteres</div>
            <div>✓ Al menos una mayúscula y una minúscula</div>
            <div>✓ Al menos un número</div>
        </div>

        <form method="POST" action="{{ route('password.force.update') }}">
            @csrf
            <div class="field">
                <label for="password">Nueva contraseña</label>
                <input id="password" type="password" name="password" required autofocus />
                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="field">
                <label for="password_confirmation">Confirmar contraseña</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required />
            </div>
            <button type="submit" class="btn-main">Actualizar contraseña</button>
        </form>
    </div>
</body>
</html>