<x-app-layout>
@php
    $user = auth()->user();
    $role = $user->role_id;
@endphp

<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-200 leading-tight">
        @if($role === 2)
            Panel — Coordinador
            @if($user->area) · {{ $user->area->nombre }} @endif
        @elseif($role === 3)
            Panel — Operativo
            @if($user->area) · {{ $user->area->nombre }} @endif
        @else
            Panel — Casa Monarca
        @endif
    </h2>
</x-slot>

<div class="max-w-5xl mx-auto px-4 py-8 space-y-6">

    @if(session('status'))
        <div class="flex items-center gap-2 bg-green-50 border border-green-200 rounded-xl px-5 py-3 text-sm text-green-700">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('status') }}
        </div>
    @endif

    {{-- ── Bienvenida + nivel de acceso ────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 flex flex-col sm:flex-row sm:items-center gap-4">
        <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0
            @if($role === 2) bg-indigo-100 @elseif($role === 3) bg-teal-100 @else bg-green-100 @endif">
            @if($role === 2)
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            @elseif($role === 3)
                <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            @else
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            @endif
        </div>

        <div class="flex-1">
            <h2 class="text-lg font-semibold text-gray-800">Bienvenido, {{ $user->name }}</h2>
            <p class="text-sm text-gray-500 mt-0.5">
                @if($role === 2)
                    Coordinador · Área {{ $user->area?->nombre ?? 'sin asignar' }}
                @elseif($role === 3)
                    Operativo · Área {{ $user->area?->nombre ?? 'sin asignar' }}
                @else
                    {{ $user->role?->name ?? 'Colaborador' }} · Casa Monarca
                @endif
            </p>
        </div>

        {{-- Badge de nivel de acceso --}}
        <div class="shrink-0">
            @if($role === 2)
                <div class="flex flex-col items-center px-5 py-3 bg-indigo-50 rounded-xl border border-indigo-100">
                    <p class="text-xs font-bold text-indigo-600 uppercase tracking-wider">Nivel 2</p>
                    <p class="text-lg font-bold text-indigo-700 mt-0.5">CRU</p>
                    <p class="text-xs text-indigo-400 mt-0.5">Crear · Leer · Actualizar</p>
                </div>
            @elseif($role === 3)
                <div class="flex flex-col items-center px-5 py-3 bg-teal-50 rounded-xl border border-teal-100">
                    <p class="text-xs font-bold text-teal-600 uppercase tracking-wider">Nivel 3</p>
                    <p class="text-lg font-bold text-teal-700 mt-0.5">CR</p>
                    <p class="text-xs text-teal-400 mt-0.5">Crear · Leer</p>
                </div>
            @else
                <div class="flex flex-col items-center px-5 py-3 bg-green-50 rounded-xl border border-green-100">
                    <p class="text-xs font-bold text-green-600 uppercase tracking-wider">Nivel 4</p>
                    <p class="text-lg font-bold text-green-700 mt-0.5">C</p>
                    <p class="text-xs text-green-400 mt-0.5">Crear registros</p>
                </div>
            @endif
        </div>
    </div>

    {{-- ── Aviso: usuario sin área asignada ───────────────────────── --}}
    @if(isset($sinArea) && $sinArea)
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 flex flex-col sm:flex-row sm:items-center gap-4">
        <div class="flex items-start gap-3 flex-1">
            <div class="w-9 h-9 rounded-full bg-amber-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                @if(isset($solicitudPendiente) && $solicitudPendiente)
                    <p class="font-semibold text-amber-800 text-sm">Solicitud de membresía en revisión</p>
                    <p class="text-xs text-amber-700 mt-0.5">
                        Tu solicitud para unirte a <strong>{{ $solicitudPendiente->area?->nombre }}</strong>
                        está pendiente de aprobación por el coordinador.
                    </p>
                @else
                    <p class="font-semibold text-amber-800 text-sm">No tienes área asignada</p>
                    <p class="text-xs text-amber-700 mt-0.5">
                        Solicita unirte a un área para poder ver solicitudes y ofrecerte para atender casos.
                    </p>
                @endif
            </div>
        </div>
        @if(!isset($solicitudPendiente) || !$solicitudPendiente)
        <a href="{{ route('mi-area.index') }}"
           class="shrink-0 px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold rounded-full transition">
            Solicitar área
        </a>
        @endif
    </div>
    @endif

    {{-- ── Estadísticas (coordinador) ──────────────────────────────── --}}
    @if($role === 2)
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Colaboradores activos</p>
            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalUsuarios }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-amber-100 shadow-sm p-5">
            <p class="text-xs font-medium text-amber-500 uppercase tracking-wide">Accesos pendientes</p>
            <p class="text-3xl font-bold text-amber-600 mt-1">{{ $pendientes->count() }}</p>
        </div>
        <a href="{{ route('admin.sin-area') }}"
           class="bg-white rounded-2xl border {{ ($solicitudesMembresia ?? 0) > 0 ? 'border-indigo-200' : 'border-gray-200' }} shadow-sm p-5 hover:border-indigo-300 transition">
            <p class="text-xs font-medium {{ ($solicitudesMembresia ?? 0) > 0 ? 'text-indigo-500' : 'text-gray-400' }} uppercase tracking-wide">
                Solicitudes de membresía
            </p>
            <p class="text-3xl font-bold {{ ($solicitudesMembresia ?? 0) > 0 ? 'text-indigo-600' : 'text-gray-800' }} mt-1">
                {{ $solicitudesMembresia ?? 0 }}
            </p>
            @if(($solicitudesMembresia ?? 0) > 0)
                <p class="text-xs text-indigo-400 mt-1">Pendientes de aprobar →</p>
            @endif
        </a>
    </div>
    @endif

    {{-- ── Acciones disponibles ─────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4">Acciones disponibles</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

            @if($role === 2 || $role === 3 || $role === 4)
            <a href="{{ route('areas.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50 transition group">
                <div class="w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-indigo-100 flex items-center justify-center shrink-0 transition">
                    <svg class="w-4 h-4 text-gray-500 group-hover:text-indigo-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16M3 21h18M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-700 group-hover:text-indigo-700 transition">Ver áreas</p>
                    <p class="text-xs text-gray-400">Consultar distribución de personal</p>
                </div>
            </a>
            @endif

            @can('puede-actualizar')
            <a href="{{ route('admin.areas.show', $user->area_id ?? 0) }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50 transition group">
                <div class="w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-indigo-100 flex items-center justify-center shrink-0 transition">
                    <svg class="w-4 h-4 text-gray-500 group-hover:text-indigo-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2h5M12 11a4 4 0 100-8 4 4 0 000 8z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-700 group-hover:text-indigo-700 transition">Mi área</p>
                    <p class="text-xs text-gray-400">Ver colaboradores de tu área</p>
                </div>
            </a>
            @endcan

            <a href="{{ route('profile.edit') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl border border-gray-200 hover:border-gray-300 hover:bg-gray-50 transition group">
                <div class="w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-gray-200 flex items-center justify-center shrink-0 transition">
                    <svg class="w-4 h-4 text-gray-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A9 9 0 1118.88 6.196M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-700">Mi perfil</p>
                    <p class="text-xs text-gray-400">Actualizar datos personales</p>
                </div>
            </a>

        </div>
    </div>

    {{-- ── Nota de permisos ─────────────────────────────────────────── --}}
    <div class="flex items-start gap-3 rounded-xl px-5 py-4
        @if($role === 2) bg-indigo-50 border border-indigo-200
        @elseif($role === 3) bg-teal-50 border border-teal-200
        @else bg-green-50 border border-green-200 @endif">
        <svg class="w-4 h-4 shrink-0 mt-0.5
            @if($role === 2) text-indigo-400
            @elseif($role === 3) text-teal-400
            @else text-green-400 @endif" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
        </svg>
        <p class="text-xs leading-relaxed
            @if($role === 2) text-indigo-700
            @elseif($role === 3) text-teal-700
            @else text-green-700 @endif">
            @if($role === 2)
                <strong>Coordinador (Nivel 2):</strong> Puedes crear, leer y actualizar registros de tu área.
                Para <strong>eliminar</strong> un registro debes solicitar al administrador.
                Tienes certificado digital PKI para firmar documentos.
            @elseif($role === 3)
                <strong>Operativo (Nivel 3):</strong> Puedes crear y leer registros.
                Para actualizar o eliminar, canaliza la solicitud a tu coordinador de área.
            @else
                <strong>Usuario (Nivel 4):</strong> Puedes registrar datos de beneficiarios.
                Para modificaciones o consultas adicionales, canaliza al nivel operativo.
            @endif
        </p>
    </div>

</div>
</x-app-layout>
