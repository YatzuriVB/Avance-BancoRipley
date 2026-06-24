<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña — Banco Ripley</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #f7f5fb; }
        .card { background: #fff; border-radius: 16px; border: 1px solid #e8e0f0; padding: 36px; width: 100%; max-width: 400px; }
        .logo { color: #5B2D8E; font-size: 18px; font-weight: 700; margin-bottom: 24px; }
        .logo span { color: #E30613; }
        .card-title { font-size: 20px; font-weight: 700; color: #1a1a2e; margin-bottom: 6px; }
        .card-sub { font-size: 13px; color: #888; margin-bottom: 24px; line-height: 1.5; }
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
        .back-link { margin-top: 16px; text-align: center; }
        .back-link a { font-size: 12px; color: #888; text-decoration: none; }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">banco ripley<span>.</span></div>
        <div class="card-title">Recuperar contraseña</div>
        <div class="card-sub">Ingresa tu DNI y nombre de usuario para verificar tu identidad.</div>

        <form method="POST" action="{{ route('password.verify') }}">
            @csrf
            <div class="field">
                <label for="dni">DNI</label>
                <input id="dni" type="text" name="dni" maxlength="8" placeholder="12345678" required autofocus />
                @error('dni')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="field">
                <label for="username">Nombre de usuario</label>
                <input id="username" type="text" name="username" placeholder="cli000007" required />
                @error('username')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn-main">Verificar identidad</button>
        </form>

        <div class="back-link">
            <a href="{{ route('login') }}">← Volver al inicio de sesión</a>
        </div>
    </div>
</body>
</html>