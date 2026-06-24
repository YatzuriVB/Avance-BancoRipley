<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva contraseña — Banco Ripley</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #f7f5fb; }
        .card { background: #fff; border-radius: 16px; border: 1px solid #e8e0f0; padding: 36px; width: 100%; max-width: 400px; }
        .logo { color: #5B2D8E; font-size: 18px; font-weight: 700; margin-bottom: 24px; }
        .logo span { color: #E30613; }
        .card-title { font-size: 20px; font-weight: 700; color: #1a1a2e; margin-bottom: 6px; }
        .card-sub { font-size: 13px; color: #888; margin-bottom: 24px; }
        .field { margin-bottom: 16px; }
        .field label { display: block; font-size: 12px; font-weight: 600; color: #444; margin-bottom: 6px; }
        .field input {
            width: 100%; padding: 10px 14px; border: 1.5px solid #e0d8ee;
            border-radius: 8px; font-size: 14px; outline: none;
        }
        .field input:focus { border-color: #5B2D8E; }
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
        <div class="card-title">Crea tu nueva contraseña</div>
        <div class="card-sub">Identidad verificada. Ingresa tu nueva contraseña.</div>

        <form method="POST" action="{{ route('password.reset.store') }}">
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