@props([])

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mi espacio — Casa Monarca</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-800 min-h-screen">

    {{-- Header --}}
    <header class="bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-4xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-green-700 rounded-full flex items-center justify-center text-white font-bold text-sm shrink-0">
                    CM
                </div>
                <div>
                    <p class="text-xs font-bold text-green-800 leading-none">Casa Monarca</p>
                    <p class="text-xs text-gray-400 mt-0.5">Portal de atención</p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-semibold text-gray-700">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-400">Migrante</p>
                </div>
                <form method="POST" action="{{ route('migrante.logout') }}">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-500 border border-gray-300 rounded-full hover:bg-gray-50 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Salir
                    </button>
                </form>
            </div>
        </div>

        {{-- Nav --}}
        <div class="max-w-4xl mx-auto px-4 pb-2 flex gap-1">
            <a href="{{ route('migrante.dashboard') }}"
               class="px-3 py-1.5 rounded-full text-xs font-medium transition
                      {{ request()->routeIs('migrante.dashboard') ? 'bg-green-700 text-white' : 'text-gray-500 hover:bg-gray-100' }}">
                Inicio
            </a>
            <a href="{{ route('migrante.solicitudes.index') }}"
               class="px-3 py-1.5 rounded-full text-xs font-medium transition
                      {{ request()->routeIs('migrante.solicitudes.*') ? 'bg-green-700 text-white' : 'text-gray-500 hover:bg-gray-100' }}">
                Mis solicitudes
            </a>
        </div>
    </header>

    {{-- Contenido --}}
    <main class="max-w-4xl mx-auto px-4 py-8">
        {{ $slot }}
    </main>

</body>
</html>
