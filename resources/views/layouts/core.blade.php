<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Core Financiero — Banco Ripley</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6; }
        .bg-ripley-purple { background-color: #5B2D8E; }
        .text-ripley-purple { color: #5B2D8E; }
        .bg-ripley-light { background-color: #7b4baf; }
    </style>
</head>
<body class="flex flex-col h-screen overflow-hidden">

    <!-- Topbar -->
    <header class="bg-ripley-purple text-white h-14 flex items-center justify-between px-6 shadow-md z-20 shrink-0">
        <div class="flex items-center gap-2">
            <div class="bg-white rounded p-1">
                <i class="ti ti-building-bank text-ripley-purple text-xl"></i>
            </div>
            <div class="font-bold text-lg leading-tight">
                Banco Ripley <br>
                <span class="text-xs font-normal tracking-wider opacity-80 uppercase">Core Financiero</span>
            </div>
        </div>
        
        <div class="flex items-center gap-4 text-sm">
            @if(Session::get('core_logged_in'))
                <div class="opacity-90">
                    {{ Session::get('core_nombre') }} - asesor - Ag. 0001
                </div>
                <form method="POST" action="{{ route('core.logout') }}">
                    @csrf
                    <button class="flex items-center gap-1 bg-ripley-light text-white px-3 py-1.5 rounded font-medium hover:bg-opacity-80 transition">
                        <i class="ti ti-logout text-lg"></i> Cerrar sesión
                    </button>
                </form>
            @endif
        </div>
    </header>

    <div class="flex flex-1 overflow-hidden">
        @if(Session::get('core_logged_in'))
            @include('partials.sidebar-core')
        @endif

        <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
            @yield('content')
        </main>
    </div>

</body>
</html>
