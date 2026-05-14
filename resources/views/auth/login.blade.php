<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banco Ripley — Ingresa a tu banca</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; min-height: 100vh; display: flex; }
        .left {
            width: 42%; background: #5B2D8E; padding: 48px 40px;
            display: flex; flex-direction: column; justify-content: space-between;
        }
        .logo { color: #fff; font-size: 22px; font-weight: 700; letter-spacing: -0.3px; margin-bottom: 48px; }
        .logo span { color: #E30613; }
        .hero-title { color: #fff; font-size: 32px; font-weight: 700; line-height: 1.25; margin-bottom: 12px; }
        .hero-sub { color: rgba(255,255,255,0.65); font-size: 15px; line-height: 1.6; }
        .features { display: flex; flex-direction: column; gap: 16px; margin-top: 40px; }
        .feature { display: flex; align-items: center; gap: 12px; color: rgba(255,255,255,0.8); font-size: 14px; }
        .feature-icon {
            width: 32px; height: 32px; background: rgba(255,255,255,0.12);
            border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .footer-text { color: rgba(255,255,255,0.35); font-size: 12px; }
        .right {
            flex: 1; background: #f7f5fb;
            display: flex; align-items: center; justify-content: center; padding: 32px;
        }
        .card {
            background: #fff; border-radius: 16px; border: 1px solid #e8e0f0;
            padding: 40px 36px; width: 100%; max-width: 380px;
        }
        .card-title { font-size: 22px; font-weight: 700; color: #1a1a2e; margin-bottom: 4px; }
        .card-sub { font-size: 14px; color: #888; margin-bottom: 28px; }
        .tab-group {
            display: flex; border: 1.5px solid #5B2D8E;
            border-radius: 8px; overflow: hidden; margin-bottom: 28px;
        }
        .tab {
            flex: 1; padding: 9px; font-size: 13px; font-weight: 600;
            text-align: center; cursor: pointer; color: #5B2D8E;
            background: #fff; border: none; transition: all .15s;
        }
        .tab.active { background: #5B2D8E; color: #fff; }
        .field { margin-bottom: 20px; }
        .field label { display: block; font-size: 12px; font-weight: 600; color: #444; margin-bottom: 6px; }
        .field input {
            width: 100%; padding: 10px 14px; border: 1.5px solid #e0d8ee;
            border-radius: 8px; font-size: 14px; color: #1a1a2e;
            background: #faf9fc; outline: none; transition: border .15s;
        }
        .field input:focus { border-color: #5B2D8E; background: #fff; }
        .error { color: #dc2626; font-size: 12px; margin-top: 4px; }
        .forgot { text-align: right; margin-top: -12px; margin-bottom: 22px; }
        .forgot a { font-size: 12px; color: #5B2D8E; text-decoration: none; }
        .btn-main {
            width: 100%; padding: 12px; background: #5B2D8E; color: #fff;
            border: none; border-radius: 8px; font-size: 15px; font-weight: 600;
            cursor: pointer; transition: background .15s;
        }
        .btn-main:hover { background: #4a2275; }
        .divider { display: flex; align-items: center; gap: 10px; margin: 22px 0; }
        .divider-line { flex: 1; height: 1px; background: #e0d8ee; }
        .divider span { font-size: 12px; color: #aaa; }
        .btn-register {
            width: 100%; padding: 11px; background: transparent; color: #5B2D8E;
            border: 1.5px solid #5B2D8E; border-radius: 8px; font-size: 14px;
            font-weight: 600; cursor: pointer; transition: all .15s;
        }
        .btn-register:hover { background: #f0e8ff; }
        .security-note {
            display: flex; align-items: center; gap: 8px; margin-top: 20px;
            padding: 8px 12px; background: #f0faf4; border-radius: 8px;
        }
        .security-note svg { flex-shrink: 0; }
        .security-note span { font-size: 11px; color: #27a65a; }
        .back-link { margin-top: 16px; text-align: center; }
        .back-link a { font-size: 12px; color: #888; text-decoration: none; }
        .back-link a:hover { color: #5B2D8E; }
    </style>
</head>
<body>
    <div class="left">
        <div>
            <div class="logo">banco ripley<span>.</span></div>
            <div class="hero-title">Más de 20 años simplificando tu vida</div>
            <div class="hero-sub">Gestiona tus finanzas de forma segura, rápida y desde donde estés.</div>
            <div class="features">
                <div class="feature">
                    <div class="feature-icon">
                        <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    Seguridad bancaria certificada
                </div>
                <div class="feature">
                    <div class="feature-icon">
                        <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    Disponible las 24 horas
                </div>
                <div class="feature">
                    <div class="feature-icon">
                        <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg>
                    </div>
                    Transferencias en segundos
                </div>
                <div class="feature">
                    <div class="feature-icon">
                        <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                    </div>
                    Gestiona tus créditos online
                </div>
            </div>
        </div>
        <div class="footer-text">© 2026 Banco Ripley Perú S.A.</div>
    </div>

    <div class="right">
        <div class="card">
            <div class="card-title">Ingresa a tu banca</div>
            <div class="card-sub">Bienvenida de vuelta</div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <div class="tab-group">
                <button type="button" class="tab active" onclick="switchTab(this, 'dni')">DNI</button>
                <button type="button" class="tab" onclick="switchTab(this, 'ce')">Carné extranjería</button>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field">
                    <label for="dni">Número de documento</label>
                    <input id="dni" type="text" name="dni" value="{{ old('dni') }}"
                        placeholder="Ingresa tu DNI" maxlength="12" required autofocus />
                    @error('dni')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="password">Contraseña</label>
                    <input id="password" type="password" name="password"
                           placeholder="••••••••" required autocomplete="current-password" />
                    @error('password')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                @if (Route::has('password.request'))
                    <div class="forgot">
                        <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                    </div>
                @endif

                <button type="submit" class="btn-main">Ingresar</button>
            </form>

            <div class="divider">
                <div class="divider-line"></div>
                <span>¿Eres nueva?</span>
                <div class="divider-line"></div>
            </div>

            <a href="{{ route('register') }}">
                <button type="button" class="btn-register">Crear cuenta</button>
            </a>

            <div class="security-note">
                <svg width="14" height="14" fill="none" stroke="#27a65a" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <span>Conexión segura SSL · Banco Ripley Perú</span>
            </div>

            <div class="back-link">
                <a href="https://www.bancoripley.com.pe">← Volver a bancoripley.com.pe</a>
            </div>
        </div>
    </div>

    <script>
        function switchTab(btn, type) {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('email').placeholder = type === 'dni' ? 'Ingresa tu DNI' : 'Ingresa tu CE';
        }
    </script>
</body>
</html>