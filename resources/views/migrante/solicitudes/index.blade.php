<x-migrante-portal-layout>

<div class="space-y-5">

    {{-- Encabezado --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Mis solicitudes</h1>
            <p class="text-sm text-gray-500 mt-0.5">Historial de peticiones a Casa Monarca</p>
        </div>
        <a href="{{ route('migrante.solicitudes.create') }}"
           class="inline-flex items-center gap-1.5 px-4 py-2 bg-green-700 hover:bg-green-800 text-white text-sm font-semibold rounded-full transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nueva solicitud
        </a>
    </div>

    {{-- Flash --}}
    @if(session('status'))
        <div class="flex items-center gap-2 bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl px-4 py-3">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('status') }}
        </div>
    @endif

    {{-- Lista --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        @if($solicitudes->isEmpty())
            <div class="py-16 text-center text-gray-400 text-sm">
                No ha realizado ninguna solicitud todavía.
            </div>
        @else
        <div class="divide-y divide-gray-100">
            @foreach($solicitudes as $sol)
            @php
                $badge = match($sol->status) {
                    'pendiente'  => ['bg-amber-100 text-amber-700', 'Pendiente'],
                    'en_proceso' => ['bg-blue-100 text-blue-700', 'En proceso'],
                    'completada' => ['bg-green-100 text-green-700', 'Completada'],
                    default      => ['bg-red-100 text-red-600', 'Rechazada'],
                };
                $tipoLabel = match($sol->tipo) {
                    'documento'   => 'Documento',
                    'proceso'     => 'Proceso/trámite',
                    'apoyo'       => 'Apoyo',
                    'informacion' => 'Información',
                    default       => 'Otro',
                };
            @endphp
            <div class="px-6 py-4">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 leading-snug">{{ $sol->descripcion }}</p>
                        <div class="flex flex-wrap gap-2 mt-1.5">
                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">
                                {{ $sol->area?->nombre ?? '—' }}
                            </span>
                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">
                                {{ $tipoLabel }}
                            </span>
                            <span class="text-xs text-gray-400">
                                {{ $sol->created_at->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $badge[0] }} shrink-0">
                        {{ $badge[1] }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>

        @if($solicitudes->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $solicitudes->links() }}
        </div>
        @endif
        @endif
    </div>

</div>

</x-migrante-portal-layout>
