<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">Diagnóstico del Sistema</h2>
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 py-8 space-y-6">

        {{-- ── Estado de tablas ─────────────────────────────────── --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800 text-sm">Estado de tablas en BD</h3>
                <p class="text-xs text-gray-400 mt-0.5">Verifica que todas las migraciones corrieron correctamente</p>
            </div>
            <div class="px-6 py-4">
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                    @foreach($tablasOk as $tabla => $ok)
                    <div class="flex items-center gap-2 px-3 py-2 rounded-lg {{ $ok ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                        @if($ok)
                            <svg class="w-4 h-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-xs font-mono text-green-700">{{ $tabla }}</span>
                        @else
                            <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span class="text-xs font-mono text-red-700">{{ $tabla }}</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ── Conteos generales ────────────────────────────────── --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            @foreach([
                ['label' => 'Usuarios total',   'val' => $totalUsuarios,    'color' => 'indigo'],
                ['label' => 'Activos',           'val' => $totalActivos,     'color' => 'green'],
                ['label' => 'Pendientes',        'val' => $totalPendientes,  'color' => 'amber'],
                ['label' => 'Suspendidos',       'val' => $totalSuspendidos, 'color' => 'red'],
                ['label' => 'Certs. activos',    'val' => $certsActivos,     'color' => 'green'],
                ['label' => 'Certs. revocados',  'val' => $certsRevocados,   'color' => 'red'],
                ['label' => 'Migrantes',         'val' => $totalMigrantes,   'color' => 'purple'],
                ['label' => 'Solicitudes',       'val' => $totalSolicitudes, 'color' => 'blue'],
            ] as $stat)
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm px-5 py-4 text-center">
                <p class="text-2xl font-bold text-{{ $stat['color'] }}-600">{{ $stat['val'] }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $stat['label'] }}</p>
            </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- ── Usuarios por rol ─────────────────────────────── --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800 text-sm">Usuarios por rol</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach($porRol as $rol)
                    <div class="px-5 py-3 flex justify-between items-center">
                        <span class="text-sm text-gray-700">{{ $rol->name }}</span>
                        <span class="text-sm font-bold text-indigo-600">{{ $rol->users_count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ── Usuarios por área ────────────────────────────── --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800 text-sm">Usuarios por área</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach($porArea as $area)
                    <div class="px-5 py-3 flex justify-between items-center">
                        <span class="text-sm text-gray-700">{{ $area->nombre }}</span>
                        <span class="text-sm font-bold text-indigo-600">{{ $area->users_count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ── Expedientes ──────────────────────────────────── --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800 text-sm">Estado de expedientes</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    <div class="px-5 py-3 flex justify-between items-center">
                        <span class="text-sm text-gray-500">Sin asignar</span>
                        <span class="px-2.5 py-0.5 bg-gray-100 text-gray-600 text-xs font-bold rounded-full">{{ $expedientesSinAsignar }}</span>
                    </div>
                    <div class="px-5 py-3 flex justify-between items-center">
                        <span class="text-sm text-gray-500">En proceso</span>
                        <span class="px-2.5 py-0.5 bg-blue-100 text-blue-600 text-xs font-bold rounded-full">{{ $expedientesEnProceso }}</span>
                    </div>
                    <div class="px-5 py-3 flex justify-between items-center">
                        <span class="text-sm text-gray-500">Terminados</span>
                        <span class="px-2.5 py-0.5 bg-green-100 text-green-600 text-xs font-bold rounded-full">{{ $expedientesTerminados }}</span>
                    </div>
                    <div class="px-5 py-3 flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700">Documentos totales</span>
                        <span class="text-sm font-bold text-indigo-600">{{ $totalDocumentos }}</span>
                    </div>
                </div>
            </div>

            {{-- ── Últimos certificados ─────────────────────────── --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800 text-sm">Últimos certificados emitidos</h3>
                </div>
                @if($ultimosCerts->isEmpty())
                    <p class="px-5 py-4 text-xs text-gray-400">No hay certificados aún. Se generan al aprobar colaboradores.</p>
                @else
                    <div class="divide-y divide-gray-100">
                        @foreach($ultimosCerts as $cert)
                        <div class="px-5 py-3 flex justify-between items-center gap-2">
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-700 truncate">{{ $cert->user?->name ?? '(eliminado)' }}</p>
                                <p class="text-xs font-mono text-gray-400 truncate">{{ substr($cert->fingerprint, 0, 20) }}…</p>
                            </div>
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full shrink-0
                                {{ $cert->status === 'activo' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $cert->status }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        {{-- ── Últimos migrantes registrados ────────────────────── --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800 text-sm">Últimos migrantes registrados</h3>
            </div>
            @if($ultimosMigrantes->isEmpty())
                <p class="px-6 py-4 text-sm text-gray-400">No hay perfiles de migrantes registrados aún.</p>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($ultimosMigrantes as $m)
                    <div class="px-6 py-3 flex flex-wrap items-center gap-3">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800">{{ $m->nombre }} {{ $m->primer_apellido }}</p>
                            <p class="text-xs text-gray-400">{{ $m->pais_origen }} · {{ $m->genero }} · {{ $m->grupo_poblacion }}</p>
                        </div>
                        <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full
                            {{ $m->status === 'activo' ? 'bg-green-100 text-green-700' : ($m->status === 'pendiente' ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-600') }}">
                            {{ $m->status }}
                        </span>
                        <span class="text-xs text-gray-400">{{ $m->created_at->diffForHumans() }}</span>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ── Log de actividad reciente ─────────────────────────── --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800 text-sm">Log de actividad reciente</h3>
                <p class="text-xs text-gray-400 mt-0.5">Rastro inmutable — últimas 10 acciones</p>
            </div>
            @if($ultimasActividades->isEmpty())
                <p class="px-6 py-4 text-sm text-gray-400">No hay actividad registrada aún.</p>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($ultimasActividades as $log)
                    <div class="px-6 py-3 flex flex-wrap items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center shrink-0 text-xs font-bold text-indigo-700 mt-0.5">
                            {{ strtoupper(substr($log->actor_nombre, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-700">
                                <strong>{{ $log->actor_nombre }}</strong>
                                <span class="text-indigo-600 font-mono text-xs ml-1">{{ $log->accion }}</span>
                            </p>
                            @if($log->modelo_tipo)
                                <p class="text-xs text-gray-400 font-mono">
                                    {{ class_basename($log->modelo_tipo) }} #{{ $log->modelo_id }}
                                    @if($log->payload && isset($log->payload['usuario']))
                                        · {{ $log->payload['usuario'] }}
                                    @endif
                                </p>
                            @endif
                        </div>
                        <span class="text-xs text-gray-400 shrink-0">{{ $log->created_at->diffForHumans() }}</span>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Link al dashboard --}}
        <div class="text-center">
            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center gap-2 text-sm text-indigo-600 hover:text-indigo-800 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver al panel
            </a>
        </div>

    </div>
</x-app-layout>
