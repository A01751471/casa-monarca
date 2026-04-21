<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acceso — Casa Monarca</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased min-h-screen flex flex-col">

    {{-- Nav superior --}}
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-4xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3">
                <img src="{{ asset('images/logo-casa-monarca.png') }}"
                     alt="Casa Monarca"
                     class="h-10 w-auto"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <span style="display:none" class="font-bold text-green-800 text-lg">Casa Monarca</span>
            </a>
            <a href="{{ route('tipo-registro') }}"
               class="text-sm text-green-700 hover:text-green-900 font-medium">
                ¿Primera vez? Registrarse →
            </a>
        </div>
    </div>

    {{-- Contenido --}}
    <div class="flex-1 flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-2xl">

            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-gray-800">¿Cómo desea ingresar?</h1>
                <p class="text-gray-500 mt-2 text-sm">
                    Seleccione el tipo de acceso que corresponde a su relación con Casa Monarca.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- Colaborador / staff --}}
                <a href="{{ route('login') }}"
                   class="group bg-white rounded-2xl border-2 border-gray-200 hover:border-green-600 p-8 flex flex-col items-center text-center transition-all duration-200 hover:shadow-lg">
                    <div class="w-16 h-16 rounded-full bg-green-100 group-hover:bg-green-600 flex items-center justify-center mb-5 transition-colors duration-200">
                        <svg class="w-8 h-8 text-green-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h2 class="font-bold text-gray-800 text-lg mb-2">Soy colaborador</h2>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Personal interno, coordinadores, agentes externos o voluntarios de Casa Monarca.
                    </p>
                    <span class="mt-5 text-sm font-semibold text-green-700 group-hover:text-green-900">
                        Ingresar con email y contraseña →
                    </span>
                </a>

                {{-- Migrante --}}
                <a href="{{ route('migrante.login') }}"
                   class="group bg-white rounded-2xl border-2 border-gray-200 hover:border-orange-500 p-8 flex flex-col items-center text-center transition-all duration-200 hover:shadow-lg">
                    <div class="w-16 h-16 rounded-full bg-orange-100 group-hover:bg-orange-500 flex items-center justify-center mb-5 transition-colors duration-200">
                        <svg class="w-8 h-8 text-orange-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <h2 class="font-bold text-gray-800 text-lg mb-2">Soy migrante</h2>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Persona hospedada en el albergue con credenciales de acceso entregadas por el personal.
                    </p>
                    <span class="mt-5 text-sm font-semibold text-orange-600 group-hover:text-orange-800">
                        Ingresar con mi llave de acceso →
                    </span>
                </a>

            </div>

            <p class="text-center text-xs text-gray-400 mt-8">
                Su información está protegida conforme a la
                <span class="font-medium">Ley Federal de Protección de Datos Personales en Posesión de Particulares</span>.
            </p>
        </div>
    </div>

</body>
</html>
