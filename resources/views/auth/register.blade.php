<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banco Ripley — Crear cuenta</title>
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
        .steps { display: flex; flex-direction: column; gap: 20px; margin-top: 40px; }
        .step { display: flex; align-items: flex-start; gap: 14px; }
        .step-num {
            width: 28px; height: 28px; background: rgba(255,255,255,0.15);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 13px; font-weight: 700; flex-shrink: 0; margin-top: 1px;
        }
        .step-text { color: rgba(255,255,255,0.8); font-size: 14px; line-height: 1.5; }
        .step-text strong { color: #fff; display: block; font-size: 14px; margin-bottom: 2px; }
        .footer-text { color: rgba(255,255,255,0.35); font-size: 12px; }
        .right {
            flex: 1; background: #f7f5fb;
            display: flex; align-items: center; justify-content: center; padding: 32px;
        }
        .card {
            background: #fff; border-radius: 16px; border: 1px solid #e8e0f0;
            padding: 36px; width: 100%; max-width: 420px;
        }
        .card-title { font-size: 22px; font-weight: 700; color: #1a1a2e; margin-bottom: 4px; }
        .card-sub { font-size: 14px; color: #888; margin-bottom: 28px; }
        .field { margin-bottom: 18px; }
        .field label { display: block; font-size: 12px; font-weight: 600; color: #444; margin-bottom: 6px; }
        .field input {
            width: 100%; padding: 10px 14px; border: 1.5px solid #e0d8ee;
            border-radius: 8px; font-size: 14px; color: #1a1a2e;
            background: #faf9fc; outline: none; transition: border .15s;
        }
        .field input:focus { border-color: #5B2D8E; background: #fff; }
        .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .error { color: #dc2626; font-size: 12px; margin-top: 4px; }
        .btn-main {
            width: 100%; padding: 12px; background: #5B2D8E; color: #fff;
            border: none; border-radius: 8px; font-size: 15px; font-weight: 600;
            cursor: pointer; transition: background .15s; margin-top: 4px;
        }
        .btn-main:hover { background: #4a2275; }
        .terms {
            font-size: 12px; color: #888; text-align: center;
            margin-top: 14px; line-height: 1.5;
        }
        .terms a { color: #5B2D8E; text-decoration: none; }
        .divider { display: flex; align-items: center; gap: 10px; margin: 20px 0; }
        .divider-line { flex: 1; height: 1px; background: #e0d8ee; }
        .divider span { font-size: 12px; color: #aaa; }
        .btn-login {
            width: 100%; padding: 11px; background: transparent; color: #5B2D8E;
            border: 1.5px solid #5B2D8E; border-radius: 8px; font-size: 14px;
            font-weight: 600; cursor: pointer; transition: all .15s;
        }
        .btn-login:hover { background: #f0e8ff; }
        .back-link { margin-top: 16px; text-align: center; }
        .back-link a { font-size: 12px; color: #888; text-decoration: none; }
        .back-link a:hover { color: #5B2D8E; }
    </style>
</head>
<body>
    <div class="left">
        <div>
            <div class="logo">banco ripley<span>.</span></div>
            <div class="hero-title">Abre tu cuenta en minutos</div>
            <div class="hero-sub">Sin papeleos ni filas. Todo desde tu dispositivo.</div>
            <div class="steps">
                <div class="step">
                    <div class="step-num">1</div>
                    <div class="step-text">
                        <strong>Crea tu cuenta</strong>
                        Ingresa tus datos personales de forma segura.
                    </div>
                </div>
                <div class="step">
                    <div class="step-num">2</div>
                    <div class="step-text">
                        <strong>Verifica tu identidad</strong>
                        Solo necesitas tu DNI o carné de extranjería.
                    </div>
                </div>
                <div class="step">
                    <div class="step-num">3</div>
                    <div class="step-text">
                        <strong>Empieza a operar</strong>
                        Accede a todos tus productos bancarios al instante.
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-text">© 2026 Banco Ripley Perú S.A.</div>
    </div>

    <div class="right">
        <div class="card">
            <div class="card-title">Crear cuenta</div>
            <div class="card-sub">Completa tus datos para registrarte</div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="field">
                    <label for="dni">DNI</label>
                    <input id="dni" type="text" name="dni" value="{{ old('dni') }}"
                        placeholder="12345678" maxlength="8" required autofocus />
                    @error('dni')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="username">Nombre de usuario</label>
                    <input id="username" type="text" name="username" value="{{ old('username') }}"
                        placeholder="Ej: jvaldivia26" required autocomplete="username" />
                    @error('username')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="email">Correo electrónico</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                            placeholder="correo@ejemplo.com" required autocomplete="email" />
                    @error('email')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field-row">
                    <div class="field">
                        <label for="password">Contraseña</label>
                        <input id="password" type="password" name="password"
                            placeholder="••••••••" required autocomplete="new-password" />
                        @error('password')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label for="password_confirmation">Confirmar contraseña</label>
                        <input id="password_confirmation" type="password"
                            name="password_confirmation" placeholder="••••••••"
                            required autocomplete="new-password" />
                    </div>
                </div>

                <button type="submit" class="btn-main">Crear cuenta</button>

                <div class="terms">
                    Al registrarte aceptas nuestros
                    <a href="#">Términos y condiciones</a> y
                    <a href="#">Política de privacidad</a>
                </div>
            </form>

            <div class="divider">
                <div class="divider-line"></div>
                <span>¿Ya tienes cuenta?</span>
                <div class="divider-line"></div>
            </div>

            <a href="{{ route('login') }}">
                <button type="button" class="btn-login">Iniciar sesión</button>
            </a>

            <div class="back-link">
                <a href="/">← Volver a bancoripley.com.pe</a>
            </div>
        </div>
    </div>
</body>
</html>