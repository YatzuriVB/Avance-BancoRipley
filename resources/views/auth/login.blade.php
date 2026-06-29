<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banco Ripley — Ingresa a tu banca</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
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
            overflow-y: auto;
        }
        .card {
            background: #fff; border-radius: 16px; border: 1px solid #e8e0f0;
            padding: 32px 36px; width: 100%; max-width: 400px;
        }
        .card-title { font-size: 22px; font-weight: 700; color: #1a1a2e; margin-bottom: 4px; }
        .card-sub { font-size: 14px; color: #888; margin-bottom: 20px; }

        /* Tarjeta bancaria */
        .bank-card {
            width: 100%; height: 180px;
            background: linear-gradient(135deg, #4a148c 0%, #310d61 100%);
            border-radius: 16px; padding: 20px 24px;
            position: relative; overflow: hidden;
            box-shadow: 0 10px 25px rgba(74,20,140,0.35);
            margin-bottom: 24px; color: #fff;
            border: 1px solid rgba(255,255,255,0.15);
        }
        .bank-card-watermark {
            position: absolute; bottom: -30px; left: -10px;
            font-size: 160px; font-weight: 900; font-family: serif;
            color: rgba(255,255,255,0.05); user-select: none; line-height: 1;
        }
        .bank-card-top {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 20px;
        }
        .bank-card-name { font-size: 13px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; }
        .bank-card-chip {
            width: 38px; height: 28px;
            background: linear-gradient(135deg, #ffca28, #ffb300);
            border-radius: 5px; box-shadow: inset 0 1px 3px rgba(0,0,0,0.2);
        }
        .bank-card-number-label {
            font-size: 9px; text-transform: uppercase; letter-spacing: 1px;
            color: #b39ddb; font-weight: 700; margin-bottom: 6px;
        }
        .bank-card-number {
            font-size: 18px; font-family: monospace; letter-spacing: 4px;
            text-align: center; background: rgba(0,0,0,0.25);
            padding: 6px 0; border-radius: 6px;
            border: 1px solid rgba(255,255,255,0.1); font-weight: 700;
        }
        .bank-card-bottom {
            display: flex; justify-content: space-between; align-items: flex-end;
            margin-top: 14px;
        }
        .bank-card-holder-label { font-size: 8px; text-transform: uppercase; letter-spacing: 1px; color: #b39ddb; }
        .bank-card-holder { font-size: 12px; font-weight: 500; text-transform: uppercase; color: #e0e0e0; margin-top: 2px; }
        .card-circles { display: flex; position: relative; width: 40px; height: 24px; }
        .circle-red { width: 24px; height: 24px; border-radius: 50%; background: #ff3d00; position: absolute; left: 0; opacity: 0.9; }
        .circle-yellow { width: 24px; height: 24px; border-radius: 50%; background: #ffc107; position: absolute; left: 14px; opacity: 0.8; }

        .field { margin-bottom: 16px; }
        .field label { display: block; font-size: 12px; font-weight: 600; color: #444; margin-bottom: 6px; }
        .field input {
            width: 100%; padding: 10px 14px; border: 1.5px solid #e0d8ee;
            border-radius: 8px; font-size: 14px; color: #1a1a2e;
            background: #faf9fc; outline: none; transition: border .15s;
        }
        .field input:focus { border-color: #5B2D8E; background: #fff; }
        .error { color: #dc2626; font-size: 12px; margin-top: 4px; }
        .forgot { text-align: right; margin-top: -8px; margin-bottom: 20px; }
        .forgot a { font-size: 12px; color: #5B2D8E; text-decoration: none; }
        .btn-main {
            width: 100%; padding: 12px; background: #5B2D8E; color: #fff;
            border: none; border-radius: 8px; font-size: 15px; font-weight: 600;
            cursor: pointer; transition: background .15s;
        }
        .btn-main:hover { background: #4a2275; }
        .divider { display: flex; align-items: center; gap: 10px; margin: 20px 0; }
        .divider-line { flex: 1; height: 1px; background: #e0d8ee; }
        .divider span { font-size: 12px; color: #aaa; }
        .btn-register {
            width: 100%; padding: 11px; background: transparent; color: #5B2D8E;
            border: 1.5px solid #5B2D8E; border-radius: 8px; font-size: 14px;
            font-weight: 600; cursor: pointer; transition: all .15s; display: block;
            text-align: center; text-decoration: none;
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

            <!-- Tarjeta bancaria decorativa -->
            <div class="bank-card">
                <div class="bank-card-watermark">R</div>
                <div class="bank-card-top">
                    <div class="bank-card-name">Banco Ripley</div>
                    <div class="bank-card-chip"></div>
                </div>
                <div class="bank-card-number-label">Código de Usuario / Tarjeta</div>
                <div class="bank-card-number">CLI — 0000 — 0001</div>
                <div class="bank-card-bottom">
                    <div>
                        <div class="bank-card-holder-label">Titular de Cuenta</div>
                        <div class="bank-card-holder">Cliente Ripley</div>
                    </div>
                    <div class="card-circles">
                        <div class="circle-red"></div>
                        <div class="circle-yellow"></div>
                    </div>
                </div>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field">
                    <label for="username">Código de usuario</label>
                    <input id="username" type="text" name="username" value="{{ old('username') }}"
                        placeholder="Ej: cli000001" required autofocus />
                    @error('username')
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

                <button type="submit" class="btn-main">Ingresar de forma segura</button>
            </form>

            <div class="divider">
                <div class="divider-line"></div>
                <span>¿Eres nueva?</span>
                <div class="divider-line"></div>
            </div>

            <a href="{{ route('register') }}" class="btn-register">Crear cuenta</a>

            <div class="security-note">
                <svg width="14" height="14" fill="none" stroke="#27a65a" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <span>Conexión segura SSL · Banco Ripley Perú</span>
            </div>

            <div class="back-link">
                <a href="/">← Volver a bancoripley.com.pe</a>
            </div>
        </div>
    </div>
</body>
</html>