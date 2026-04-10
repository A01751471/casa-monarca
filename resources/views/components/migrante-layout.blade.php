@props([])

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
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body x-data class="bg-gray-100 font-sans antialiased text-gray-800">

    {{-- Barra superior --}}
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-5xl mx-auto px-4 py-2 flex items-center justify-between">

            {{-- Selector de idioma tipo pill/cápsula --}}
            <div class="flex items-center bg-gray-100 rounded-full p-0.5 gap-0.5 text-xs font-semibold">
                <button @click="$store.lang = 'es'"
                        :class="$store.lang === 'es'
                            ? 'bg-white text-green-700 shadow-sm ring-1 ring-gray-200'
                            : 'text-gray-400 hover:text-gray-600'"
                        class="px-3 py-1 rounded-full transition-all duration-200 cursor-pointer">
                    ES
                </button>
                <button @click="$store.lang = 'en'"
                        :class="$store.lang === 'en'
                            ? 'bg-white text-green-700 shadow-sm ring-1 ring-gray-200'
                            : 'text-gray-400 hover:text-gray-600'"
                        class="px-3 py-1 rounded-full transition-all duration-200 cursor-pointer">
                    EN
                </button>
                <button @click="$store.lang = 'fr'"
                        :class="$store.lang === 'fr'
                            ? 'bg-white text-green-700 shadow-sm ring-1 ring-gray-200'
                            : 'text-gray-400 hover:text-gray-600'"
                        class="px-3 py-1 rounded-full transition-all duration-200 cursor-pointer">
                    FR
                </button>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('tipo-registro') }}"
                   class="px-4 py-1.5 border border-gray-300 rounded-full text-gray-600 hover:bg-gray-50 transition text-xs font-medium">
                    Registro
                </a>
                <a href="{{ route('login') }}"
                   class="px-4 py-1.5 bg-green-700 text-white rounded-full hover:bg-green-800 transition text-xs font-medium">
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
                 onerror="this.style.display='none'; document.getElementById('logo-fallback').style.display='flex';">
            <div id="logo-fallback"
                 style="display:none"
                 class="h-16 w-16 bg-green-700 rounded-full items-center justify-center text-white font-bold text-xl shrink-0">
                CM
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400"
                   x-text="$store.lang === 'es' ? 'Entrevista de Ingreso al Albergue' :
                            $store.lang === 'en' ? 'Shelter Intake Interview' :
                                                   'Entretien d\'admission au refuge'">
                    Entrevista de Ingreso al Albergue
                </p>
                <h1 class="text-xl font-bold text-green-800 mt-0.5">
                    Casa Monarca — Ayuda Humanitaria al Migrante
                </h1>
                <p class="text-xs text-gray-500 mt-1 leading-relaxed"
                   x-text="$store.lang === 'es' ? 'Complete el formulario con sus datos personales. Su información está protegida y solo será utilizada por el personal autorizado de Casa Monarca.' :
                            $store.lang === 'en' ? 'Please fill out the form with your personal details. Your information is protected and will only be used by authorized Casa Monarca staff.' :
                                                   'Veuillez remplir le formulaire avec vos données personnelles. Vos informations sont protégées et ne seront utilisées que par le personnel autorisé de Casa Monarca.'">
                    Complete el formulario con sus datos personales. Su información está protegida y solo será utilizada por el personal autorizado de Casa Monarca.
                </p>
            </div>
        </div>
    </div>

    {{-- Contenido --}}
    <div class="max-w-5xl mx-auto px-4 py-8">
        {{ $slot }}
    </div>

</body>
</html>
