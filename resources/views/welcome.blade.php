<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banco Ripley Perú</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; }
        .nav {
            background: #5B2D8E; padding: 0 48px;
            display: flex; align-items: center; justify-content: space-between; height: 64px;
            position: sticky; top: 0; z-index: 100;
        }
        .nav-logo { color: #fff; font-size: 18px; font-weight: 700; }
        .nav-logo span { color: #E30613; }
        .nav-links { display: flex; align-items: center; gap: 28px; }
        .nav-link { color: rgba(255,255,255,0.8); font-size: 14px; text-decoration: none; cursor: pointer; }
        .nav-link:hover { color: #fff; }
        .btn-banca {
            background: #fff; color: #5B2D8E; padding: 8px 20px;
            border-radius: 8px; font-size: 14px; font-weight: 700; border: none; cursor: pointer;
            text-decoration: none;
        }
        .btn-banca:hover { background: #f0e8ff; }
        .hero {
            background: #5B2D8E; padding: 64px 48px;
            display: flex; align-items: center; gap: 48px;
        }
        .hero-left { flex: 1; }
        .hero-tag {
            background: rgba(255,255,255,0.15); color: #fff; font-size: 12px;
            font-weight: 600; padding: 4px 14px; border-radius: 20px;
            display: inline-block; margin-bottom: 20px;
        }
        .hero-title { color: #fff; font-size: 40px; font-weight: 800; line-height: 1.2; margin-bottom: 16px; }
        .hero-title span { color: #E8C4FF; }
        .hero-sub { color: rgba(255,255,255,0.7); font-size: 15px; line-height: 1.6; margin-bottom: 32px; }
        .hero-btns { display: flex; gap: 12px; }
        .btn-primary {
            background: #fff; color: #5B2D8E; padding: 12px 28px;
            border-radius: 8px; font-size: 15px; font-weight: 700; border: none; cursor: pointer;
            text-decoration: none; display: inline-block;
        }
        .btn-primary:hover { background: #f0e8ff; }
        .btn-secondary {
            background: transparent; color: #fff; padding: 12px 28px;
            border-radius: 8px; font-size: 15px; font-weight: 600;
            border: 2px solid rgba(255,255,255,0.4); cursor: pointer;
            text-decoration: none; display: inline-block;
        }
        .btn-secondary:hover { border-color: #fff; }
        .hero-right { flex: 0 0 360px; position: relative; padding-bottom: 20px; }
        .hero-img {
            border-radius: 16px; overflow: hidden; height: 340px;
            width: 100%;
        }
        .hero-img img { width: 100%; height: 100%; object-fit: cover; }
        .floating-card {
            position: absolute; bottom: 0; left: -24px;
            background: #fff; border-radius: 12px; padding: 14px 18px;
            min-width: 190px; border: 1px solid #e8e0f0;
        }
        .floating-card-label { font-size: 11px; color: #888; margin-bottom: 4px; }
        .floating-card-val { font-size: 18px; font-weight: 700; color: #1a1a2e; }
        .floating-card-sub { font-size: 11px; color: #27a65a; margin-top: 2px; }
        .promo { padding: 56px 48px; background: #fff; }
        .section-label {
            font-size: 12px; font-weight: 700; color: #5B2D8E;
            letter-spacing: .06em; text-transform: uppercase; margin-bottom: 8px;
        }
        .section-title { font-size: 26px; font-weight: 800; color: #1a1a2e; margin-bottom: 36px; }
        .promo-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .promo-card { border-radius: 12px; overflow: hidden; border: 1px solid #e8e0f0; }
        .promo-img { height: 180px; position: relative; overflow: hidden; }
        .promo-img img { width: 100%; height: 100%; object-fit: cover; }
        .promo-badge {
            position: absolute; top: 12px; left: 12px;
            background: #E30613; color: #fff; font-size: 11px;
            font-weight: 700; padding: 4px 10px; border-radius: 20px;
        }
        .promo-body { padding: 18px; }
        .promo-title { font-size: 15px; font-weight: 700; color: #1a1a2e; margin-bottom: 6px; }
        .promo-desc { font-size: 13px; color: #888; line-height: 1.5; }
        .promo-link { font-size: 13px; color: #5B2D8E; font-weight: 600; margin-top: 12px; display: block; }
        .cta { background: #5B2D8E; padding: 64px 48px; display: flex; align-items: center; gap: 48px; }
        .cta-left { flex: 1; }
        .cta-title { color: #fff; font-size: 28px; font-weight: 800; margin-bottom: 12px; }
        .cta-sub { color: rgba(255,255,255,0.7); font-size: 15px; line-height: 1.6; margin-bottom: 28px; }
        .cta-right { flex: 0 0 300px; height: 220px; border-radius: 16px; overflow: hidden; }
        .cta-right img { width: 100%; height: 100%; object-fit: cover; }
        .footer {
            background: #1a1a2e; padding: 28px 48px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .footer-logo { color: #fff; font-size: 15px; font-weight: 700; }
        .footer-logo span { color: #E30613; }
        .footer-links { display: flex; gap: 24px; }
        .footer-link { color: rgba(255,255,255,0.4); font-size: 12px; text-decoration: none; }
        .footer-link:hover { color: rgba(255,255,255,0.7); }
        .footer-text { color: rgba(255,255,255,0.4); font-size: 12px; }
    </style>
</head>
<body>

<nav class="nav">
    <div class="nav-logo">banco ripley<span>.</span></div>
    <div class="nav-links">
        <a href="#" class="nav-link">Personas</a>
        <a href="#" class="nav-link">Empresas</a>
        <a href="#" class="nav-link">Promociones</a>
        <a href="#" class="nav-link">Ayuda</a>
        <a href="{{ route('login') }}" class="btn-banca">Banca por Internet</a>
    </div>
</nav>

<section class="hero">
    <div class="hero-left">
        <div class="hero-tag">Más de 20 años contigo</div>
        <div class="hero-title">Tu banco, <span>siempre contigo</span></div>
        <div class="hero-sub">Gestiona tus cuentas, créditos y transferencias de forma simple, segura y desde donde estés.</div>
        <div class="hero-btns">
            <a href="{{ route('login') }}" class="btn-primary">Banca por Internet</a>
            <a href="{{ route('register') }}" class="btn-secondary">Abrir cuenta gratis</a>
        </div>
    </div>
    <div class="hero-right">
        <div class="hero-img">
            <img src="{{ asset('images/MujerCelu.jpg') }}" alt="Persona usando Banco Ripley" />
        </div>
        <div class="floating-card">
            <div class="floating-card-label">Saldo disponible</div>
            <div class="floating-card-val">S/ 4,250.00</div>
            <div class="floating-card-sub">↑ +S/ 200 hoy</div>
        </div>
    </div>
</section>

<section class="promo">
    <div class="section-label">Promociones</div>
    <div class="section-title">Ofertas exclusivas para ti</div>
    <div class="promo-grid">
        <div class="promo-card">
            <div class="promo-img">
                <img src="{{ asset('images/Promo.png') }}" alt="Promoción Ripley" />
                <div class="promo-badge">20% dscto</div>
            </div>
            <div class="promo-body">
                <div class="promo-title">20% en compras Ripley</div>
                <div class="promo-desc">Paga con tu Tarjeta Ripley y obtén descuentos en todas las tiendas.</div>
                <span class="promo-link">Ver promoción →</span>
            </div>
        </div>
        <div class="promo-card">
            <div class="promo-img">
                <img src="{{ asset('images/cuotas.png') }}" alt="Cuotas sin intereses" />
                <div class="promo-badge">Sin intereses</div>
            </div>
            <div class="promo-body">
                <div class="promo-title">Cuotas sin intereses</div>
                <div class="promo-desc">Hasta 12 cuotas sin intereses en tiendas seleccionadas del Perú.</div>
                <span class="promo-link">Ver promoción →</span>
            </div>
        </div>
        <div class="promo-card">
            <div class="promo-img">
                <img src="{{ asset('images/Prestamo.png') }}" alt="Préstamo rápido" />
                <div class="promo-badge">Nuevo</div>
            </div>
            <div class="promo-body">
                <div class="promo-title">Préstamo en 24 horas</div>
                <div class="promo-desc">Solicita tu crédito online y recibe el dinero en tu cuenta al instante.</div>
                <span class="promo-link">Solicitar →</span>
            </div>
        </div>
    </div>
</section>

<section class="cta">
    <div class="cta-left">
        <div class="cta-title">¿Lista para empezar?</div>
        <div class="cta-sub">Únete a miles de clientes que ya gestionan sus finanzas con Banco Ripley. Abre tu cuenta en minutos, sin papeleos.</div>
        <a href="{{ route('register') }}" class="btn-primary">Abrir mi cuenta gratis</a>
    </div>
    <div class="cta-right">
        <img src="{{ asset('images/PerUsarRipley.png') }}" alt="Usar Banco Ripley" />
    </div>
</section>

<footer class="footer">
    <div class="footer-logo">banco ripley<span>.</span></div>
    <div class="footer-links">
        <a href="#" class="footer-link">Términos y condiciones</a>
        <a href="#" class="footer-link">Política de privacidad</a>
        <a href="#" class="footer-link">Contacto</a>
    </div>
    <div class="footer-text">© 2026 Banco Ripley Perú S.A.</div>
</footer>

</body>
</html>