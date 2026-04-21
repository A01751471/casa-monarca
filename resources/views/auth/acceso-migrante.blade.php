<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Acceso — Casa Monarca</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-800 min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-md">

    {{-- Logo / encabezado --}}
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-14 h-14 bg-green-700 rounded-full text-white font-bold text-xl mb-3">
            CM
        </div>
        <h1 class="text-xl font-bold text-gray-800">Casa Monarca</h1>
        <p class="text-sm text-gray-500 mt-1">Portal de atención a migrantes</p>
    </div>

    {{-- Tarjeta --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 space-y-5">

        <div>
            <h2 class="text-base font-semibold text-gray-800">Identificación segura</h2>
            <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                Seleccione su nombre y cargue el archivo de llave (.pem) que le entregó el personal de Casa Monarca.
            </p>
        </div>

        {{-- Errores --}}
        @if(session('error'))
            <div class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        @if($migrantes->isEmpty())
            <div class="bg-amber-50 border border-amber-200 text-amber-700 text-sm rounded-xl px-4 py-3">
                No hay migrantes con acceso habilitado en este momento. Contacte al personal.
            </div>
        @else
        <form method="POST" action="{{ route('migrante.login.post') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            {{-- Selección de identidad --}}
            <div>
                <label for="user_id" class="block text-xs font-semibold text-gray-600 mb-1.5">
                    ¿Quién es usted?
                </label>
                <select name="user_id" id="user_id" required
                        class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white">
                    <option value="">— Seleccione su nombre —</option>
                    @foreach($migrantes as $m)
                        @php
                            $p = $m->migrantePerfil;
                            $nombre = $p
                                ? trim($p->nombre . ' ' . $p->primer_apellido . ' ' . $p->segundo_apellido)
                                : $m->name;
                        @endphp
                        <option value="{{ $m->id }}" {{ old('user_id') == $m->id ? 'selected' : '' }}>
                            {{ $nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Archivo de llave --}}
            <div>
                <label for="llave" class="block text-xs font-semibold text-gray-600 mb-1.5">
                    Archivo de llave privada (.pem)
                </label>
                <div class="relative">
                    <input type="file" name="llave" id="llave" accept=".pem,.key" required
                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm text-gray-700
                                  file:mr-3 file:py-1 file:px-3 file:rounded-full file:border-0
                                  file:text-xs file:font-semibold file:bg-green-50 file:text-green-700
                                  hover:file:bg-green-100 focus:ring-2 focus:ring-green-500">
                </div>
                <p class="text-xs text-gray-400 mt-1">
                    Inserte su USB, seleccione el archivo <strong>llave_privada.pem</strong> que le proporcionaron.
                </p>
            </div>

            <button type="submit"
                    class="w-full py-2.5 bg-green-700 hover:bg-green-800 text-white font-semibold text-sm rounded-xl transition shadow-sm">
                Ingresar
            </button>
        </form>
        @endif

    </div>

    {{-- Enlace a login staff --}}
    <p class="text-center text-xs text-gray-400 mt-4">
        ¿Es personal de Casa Monarca?
        <a href="{{ route('login') }}" class="text-green-700 hover:underline font-medium">Iniciar sesión aquí</a>
    </p>

</div>

</body>
</html>
