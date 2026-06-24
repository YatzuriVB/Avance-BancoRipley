<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Core Financiero — Banco Andino</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white rounded-xl shadow-lg w-full max-w-md overflow-hidden">
        <div class="bg-[#5B2D8E] p-6 text-center text-white">
            <div class="inline-block bg-white rounded p-2 mb-3">
                <i class="ti ti-building-bank text-[#5B2D8E] text-3xl"></i>
            </div>
            <h1 class="text-2xl font-bold">Banco Ripley</h1>
            <p class="text-sm opacity-80 uppercase tracking-widest mt-1">Core Financiero</p>
        </div>
        
        <div class="p-8">
            <h2 class="text-gray-800 text-lg font-semibold mb-6 text-center">Acceso para Trabajadores</h2>
            
            <form method="POST" action="{{ route('core.login.post') }}">
                @csrf
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">DNI del Asesor</label>
                    <input type="text" name="dni" value="{{ old('dni') }}" class="w-full border border-gray-300 px-4 py-2.5 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5B2D8E] focus:border-transparent transition" placeholder="Ej: 12345678" required>
                    @error('dni')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>
                
                <button type="submit" class="w-full bg-[#5B2D8E] text-white font-bold py-3 rounded-lg hover:bg-opacity-90 transition shadow-sm mt-2">
                    Ingresar al Sistema
                </button>
            </form>

            <div class="mt-6 p-4 bg-blue-50 text-blue-700 text-sm rounded-lg flex items-start gap-3">
                <i class="ti ti-info-circle text-xl shrink-0 mt-0.5"></i>
                <p>Para esta versión académica, inicia sesión únicamente ingresando el DNI de un asesor registrado en la base de datos (Ej: un trabajador de la tabla <b>dpersonal</b>).</p>
            </div>
        </div>
    </div>

</body>
</html>
