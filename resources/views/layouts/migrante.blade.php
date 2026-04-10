<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Entrevista de Ingreso — Casa Monarca</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-800">

    {{-- Barra superior --}}
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-5xl mx-auto px-4 py-2 flex items-center justify-between">
            <div class="flex items-center gap-1 text-xs font-semibold">
                <button class="px-2 py-1 bg-green-700 text-white rounded">ES</button>
                <button class="px-2 py-1 text-gray-400 hover:text-gray-700 rounded border border-transparent hover:border-gray-200">EN</button>
                <button class="px-2 py-1 text-gray-400 hover:text-gray-700 rounded border border-transparent hover:border-gray-200">FR</button>
            </div>
            <div class="flex items-center gap-2 text-sm">
                <a href="{{ route('tipo-registro') }}"
                   class="px-3 py-1 border border-gray-300 rounded text-gray-600 hover:bg-gray-50">
                    Registro
                </a>
                <a href="{{ route('login') }}"
                   class="px-3 py-1 bg-green-700 text-white rounded hover:bg-green-800">
                    Iniciar sesión
                </a>
            </div>
        </div>
    </div>

    {{-- Cabecera con logo y título --}}
    <div class="bg-white border-b-4 border-green-700">
        <div class="max-w-5xl mx-auto px-4 py-5 flex items-center gap-5">
            <img src="{{ asset('images/logo-casa-monarca.png') }}"
                 alt="Casa Monarca"
                 class="h-16 w-auto"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            {{-- Fallback si no existe el archivo del logo --}}
            <div style="display:none"
                 class="h-16 w-16 bg-green-700 rounded-full items-center justify-center text-white font-bold text-xl shrink-0">
                CM
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">
                    Entrevista de Ingreso al Albergue
                </p>
                <h1 class="text-xl font-bold text-green-800 mt-0.5">
                    Casa Monarca — Ayuda Humanitaria al Migrante
                </h1>
                <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                    Complete el formulario con sus datos personales. Su información está protegida y solo será utilizada<br>
                    por el personal autorizado de Casa Monarca.
                </p>
            </div>
        </div>
    </div>

    {{-- Contenido principal --}}
    <div class="max-w-5xl mx-auto px-4 py-8">
        {{ $slot }}
    </div>

</body>
</html>
