<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banco Ripley Perú</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-white text-gray-800 antialiased">
    <!-- Top Bar -->
    <div class="bg-gray-100 border-b border-gray-200 text-xs text-gray-500 py-2 px-4 md:px-8 flex justify-between items-center">
        <div class="flex gap-4">
            <a href="#" class="hover:text-[#5B2D8E] transition-colors">Ripley.com</a>
            <a href="#" class="hover:text-[#5B2D8E] transition-colors">RipleyPuntos Go</a>
            <a href="#" class="hover:text-[#5B2D8E] transition-colors">Seguros Ripley</a>
        </div>
        <div class="flex items-center gap-1">País: <span class="text-lg leading-none">🇵🇪</span> Peru</div>
    </div>

    <!-- Main Navigation -->
    <nav class="bg-white flex flex-col md:flex-row items-center justify-between border-b border-gray-200">
        <!-- Logo -->
        <div class="flex items-center py-4 px-4 md:px-8">
            <a href="/" class="text-[#5B2D8E] text-2xl font-extrabold tracking-tight">banco ripley<span class="text-[#E30613]">_</span></a>
        </div>
        
        <!-- Links -->
        <div class="hidden lg:flex items-center gap-6 text-[11px] font-bold text-gray-600 tracking-wider">
            <a href="#" class="hover:text-[#5B2D8E] transition-colors">TARJETAS RIPLEY</a>
            <a href="#" class="hover:text-[#5B2D8E] transition-colors">PRÉSTAMOS</a>
            <a href="#" class="hover:text-[#5B2D8E] transition-colors">AHORROS</a>
            <a href="#" class="hover:text-[#5B2D8E] transition-colors">SEGUROS</a>
            <a href="#" class="hover:text-[#5B2D8E] transition-colors">PROMOCIONES</a>
        </div>

        <!-- Buttons -->
        <div class="flex w-full md:w-auto mt-2 md:mt-0">
            <a href="{{ route('register') }}" class="flex-1 md:flex-none bg-[#f0a830] text-white text-[11px] font-bold px-6 py-5 flex items-center justify-center hover:bg-[#e09820] transition-colors">
                ¡OBTÉN TU TARJETA!
            </a>
            <a href="{{ route('login') }}" class="flex-1 md:flex-none bg-[#5B2D8E] text-white text-[11px] font-bold px-6 py-5 flex items-center justify-center gap-2 hover:bg-[#4a2275] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                BANCA POR INTERNET
            </a>
        </div>
    </nav>

    <!-- Sub Banner -->
    <div class="bg-[#f8f8fa] text-center py-3 border-b border-gray-200 flex flex-col sm:flex-row items-center justify-center gap-3">
        <span class="text-gray-600 text-sm">¿Imprevistos? Recibe <span class="text-[#5B2D8E] font-bold">desde S/50 hasta S/20,000</span> en efectivo</span>
        <a href="{{ route('register') }}" class="bg-[#E30613] text-white text-xs font-bold px-4 py-1.5 rounded hover:bg-[#c20510] transition-colors">Pídelo aquí</a>
    </div>

    <!-- Hero Banner -->
    <div class="w-full bg-gray-900 h-[300px] md:h-[400px]">
        <img src="{{ asset('images/MujerCelu.jpg') }}" alt="Banner Banco Ripley" class="w-full h-full object-cover opacity-90 object-top">
    </div>

    <!-- ¿Qué necesitas hoy? -->
    <div class="max-w-5xl mx-auto px-6 py-16 text-center">
        <h2 class="text-[#5B2D8E] text-3xl font-bold mb-10">¿Qué necesitas hoy?</h2>
        <div class="border-2 border-[#5B2D8E] rounded-3xl p-8 md:p-12 grid grid-cols-1 md:grid-cols-3 gap-10">
            <!-- Item 1 -->
            <div class="flex flex-col items-center">
                <svg class="w-12 h-12 text-[#5B2D8E] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2" stroke-width="1.5"/><line x1="3" y1="10" x2="21" y2="10" stroke-width="1.5"/></svg>
                <h3 class="text-gray-800 font-bold text-lg mb-2">Tarjeta Ripley</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Acumula Puntos GO y canjéalos por lo que quieras</p>
            </div>
            <!-- Item 2 -->
            <div class="flex flex-col items-center relative">
                <!-- Divider for Desktop -->
                <div class="hidden md:block absolute left-0 top-1/2 -translate-y-1/2 w-px h-24 bg-gray-200"></div>
                <div class="hidden md:block absolute right-0 top-1/2 -translate-y-1/2 w-px h-24 bg-gray-200"></div>
                
                <svg class="w-12 h-12 text-[#5B2D8E] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <h3 class="text-gray-800 font-bold text-lg mb-2">Préstamos</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Efectivo para tus proyectos, directo en tu cuenta</p>
            </div>
            <!-- Item 3 -->
            <div class="flex flex-col items-center">
                <svg class="w-12 h-12 text-[#5B2D8E] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h3 class="text-gray-800 font-bold text-lg mb-2">Cuenta Ahorro Plus</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Tu dinero crece con nuestra Súper Tasa</p>
            </div>
        </div>
    </div>

    <!-- Tu Efectivo Express -->
    <div class="bg-gray-50 py-16">
        <div class="max-w-5xl mx-auto px-6 text-center">
            <h2 class="text-[#5B2D8E] text-2xl md:text-3xl font-bold mb-10">Tu Efectivo Express, listo para usarlo en minutos</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Card 1 -->
                <div class="bg-white shadow-sm hover:shadow-md transition-shadow border border-gray-200 overflow-hidden text-left flex flex-col">
                    <div class="bg-[#8b4f9c] text-white text-[10px] font-bold px-4 py-2 w-max rounded-br-lg tracking-wider">POR TIEMPO LIMITADO</div>
                    <div class="p-8 flex-1 flex flex-col">
                        <h3 class="text-[#5B2D8E] text-3xl font-extrabold mb-4">Retira S/ 1,000</h3>
                        <p class="text-gray-500 text-sm mb-1">En 12 cuotas mensuales de <strong class="text-gray-800">S/ 112*</strong></p>
                        <p class="text-gray-500 text-sm mb-6">T.C.E.A.: 140,85%</p>
                        <div class="mt-auto">
                            <p class="text-gray-400 text-xs mb-4 leading-tight">*Sujeto a evaluación crediticia. Cuota referencial.</p>
                            <button class="w-full bg-[#E30613] text-white font-bold py-3 rounded hover:bg-[#c20510] transition-colors">Lo quiero</button>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-white shadow-sm hover:shadow-md transition-shadow border border-gray-200 overflow-hidden text-left flex flex-col">
                    <div class="text-[#5B2D8E] text-[10px] font-bold px-4 py-2 tracking-wider">DESEMBOLSA</div>
                    <div class="px-8 pb-8 pt-4 flex-1 flex flex-col">
                        <h3 class="text-[#5B2D8E] text-3xl font-extrabold mb-4">S/ 3,000</h3>
                        <p class="text-gray-500 text-sm mb-1">En 24 cuotas mensuales de <strong class="text-gray-800">S/ 210*</strong></p>
                        <p class="text-gray-500 text-sm mb-6">T.C.E.A.: 91,89%</p>
                        <div class="mt-auto">
                            <p class="text-gray-400 text-xs mb-4 leading-tight">*Sujeto a evaluación crediticia. Cuota referencial.</p>
                            <button class="w-full bg-[#E30613] text-white font-bold py-3 rounded hover:bg-[#c20510] transition-colors">¡Lo quiero!</button>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-white shadow-sm hover:shadow-md transition-shadow border border-gray-200 overflow-hidden text-left flex flex-col">
                    <div class="text-[#5B2D8E] text-[10px] font-bold px-4 py-2 tracking-wider">DESEMBOLSA</div>
                    <div class="px-8 pb-8 pt-4 flex-1 flex flex-col">
                        <h3 class="text-[#5B2D8E] text-3xl font-extrabold mb-4">S/ 5,000</h3>
                        <p class="text-gray-500 text-sm mb-1">En 24 cuotas mensuales de <strong class="text-gray-800">S/ 337*</strong></p>
                        <p class="text-gray-500 text-sm mb-6">T.C.E.A.: 76,85%</p>
                        <div class="mt-auto">
                            <p class="text-gray-400 text-xs mb-4 leading-tight">*Sujeto a evaluación crediticia. Cuota referencial.</p>
                            <button class="w-full bg-[#E30613] text-white font-bold py-3 rounded hover:bg-[#c20510] transition-colors">¡Lo quiero!</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Action Section -->
    <div class="py-16 bg-white text-center">
        <div class="max-w-4xl mx-auto px-6">
            <h2 class="text-[#5B2D8E] text-2xl font-bold mb-8">Haz tus pagos, sin complicaciones</h2>
            <p class="text-gray-500 mb-8">Solo con tu DNI</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('login') }}" class="bg-[#5B2D8E] text-white px-8 py-4 rounded-xl font-bold hover:bg-[#4a2275] transition-colors flex items-center justify-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2" stroke-width="2"/><line x1="3" y1="10" x2="21" y2="10" stroke-width="2"/></svg>
                    Paga tu Tarjeta Ripley<br>y Préstamos
                </a>
                <a href="{{ route('login') }}" class="bg-[#5B2D8E] text-white px-8 py-4 rounded-xl font-bold hover:bg-[#4a2275] transition-colors flex items-center justify-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Ordena la deuda de tu<br>Tarjeta de Crédito
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-100 py-6 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <p class="text-gray-400 text-xs">© 2026 Banco Ripley Perú S.A. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>