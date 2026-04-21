<x-migrante-portal-layout>

<div class="space-y-6">

    {{-- Bienvenida --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm px-6 py-5">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center shrink-0 text-green-700 font-bold text-lg">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-800">
                    Bienvenido, {{ $perfil?->nombre ?? auth()->user()->name }}
                </h2>
                <p class="text-sm text-gray-500">
                    @if($perfil)
                        {{ $perfil->pais_origen }} · Ingreso {{ \Carbon\Carbon::parse($perfil->fecha_atencion)->format('d/m/Y') }}
                    @else
                        Portal de atención a migrantes — Casa Monarca
                    @endif
                </p>
            </div>
        </div>
    </div>

    {{-- Acción principal --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <a href="{{ route('migrante.solicitudes.create') }}"
           class="bg-green-700 hover:bg-green-800 text-white rounded-2xl p-5 flex items-center gap-4 transition shadow-sm group">
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <div>
                <p class="font-semibold text-sm">Nueva solicitud</p>
                <p class="text-xs text-green-200 mt-0.5">Pedir ayuda a un área de Casa Monarca</p>
            </div>
        </a>

        <a href="{{ route('migrante.solicitudes.index') }}"
           class="bg-white hover:bg-gray-50 border border-gray-200 rounded-2xl p-5 flex items-center gap-4 transition shadow-sm group">
            <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <p class="font-semibold text-sm text-gray-800">Mis solicitudes</p>
                <p class="text-xs text-gray-400 mt-0.5">Ver el estado de sus peticiones</p>
            </div>
        </a>
    </div>

    {{-- Solicitudes recientes --}}
    @if($solicitudesRecientes->isNotEmpty())
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800 text-sm">Solicitudes recientes</h3>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($solicitudesRecientes as $sol)
            @php
                $badge = match($sol->status) {
                    'pendiente'  => 'bg-amber-100 text-amber-700',
                    'en_proceso' => 'bg-blue-100 text-blue-700',
                    'completada' => 'bg-green-100 text-green-700',
                    default      => 'bg-red-100 text-red-600',
                };
                $label = match($sol->status) {
                    'pendiente'  => 'Pendiente',
                    'en_proceso' => 'En proceso',
                    'completada' => 'Completada',
                    default      => 'Rechazada',
                };
            @endphp
            <div class="px-6 py-3 flex items-center gap-4">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $sol->descripcion }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">
                        {{ $sol->area?->nombre ?? '—' }} · {{ ucfirst($sol->tipo) }} ·
                        {{ $sol->created_at->format('d/m/Y') }}
                    </p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $badge }} shrink-0">
                    {{ $label }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm px-6 py-10 text-center">
        <p class="text-sm text-gray-500">Aún no ha levantado ninguna solicitud.</p>
        <a href="{{ route('migrante.solicitudes.create') }}"
           class="inline-block mt-3 text-green-700 text-sm font-medium hover:underline">
            Crear su primera solicitud →
        </a>
    </div>
    @endif

</div>

</x-migrante-portal-layout>
