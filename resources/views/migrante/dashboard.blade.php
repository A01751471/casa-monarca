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

    {{-- Acciones principales --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        <a href="{{ route('migrante.solicitudes.index') }}"
           style="background:var(--ink-900);color:var(--cream-50);border-radius:var(--r-lg);
                  padding:20px;display:flex;align-items:center;gap:14px;text-decoration:none;
                  transition:opacity .15s;"
           onmouseover="this.style.opacity='.88'" onmouseout="this.style.opacity='1'">
            <div style="width:40px;height:40px;background:rgba(255,255,255,.12);border-radius:var(--r-md);
                        display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <p style="font-family:var(--font-display);font-weight:700;font-size:14px;">Mis solicitudes</p>
                <p style="font-size:12px;color:var(--cream-300);margin-top:2px;">Ver y crear peticiones a Casa Monarca</p>
            </div>
        </a>

        <a href="{{ route('migrante.documentos.index') }}"
           style="background:var(--brand-orange-soft);border:1px solid var(--brand-orange-line);
                  border-radius:var(--r-lg);padding:20px;display:flex;align-items:center;gap:14px;
                  text-decoration:none;color:var(--ink-900);transition:background .15s;"
           onmouseover="this.style.background='oklch(92% 0.055 60)'" onmouseout="this.style.background='var(--brand-orange-soft)'">
            <div style="width:40px;height:40px;background:var(--paper);border:1px solid var(--brand-orange-line);
                        border-radius:var(--r-md);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg style="width:20px;height:20px;color:var(--brand-orange-deep);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <p style="font-family:var(--font-display);font-weight:700;font-size:14px;">Mis documentos</p>
                <p style="font-size:12px;color:var(--ink-500);margin-top:2px;">Identidad y documentación personal</p>
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
                    @if($sol->expediente?->folio)
                        <span class="font-mono text-xs font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded mr-1">
                            {{ $sol->expediente->folio }}
                        </span>
                    @endif
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
        <a href="{{ route('migrante.solicitudes.index') }}"
           class="inline-block mt-3 text-green-700 text-sm font-medium hover:underline">
            Ir a solicitudes →
        </a>
    </div>
    @endif

</div>

</x-migrante-portal-layout>
