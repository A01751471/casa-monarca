<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            Mis casos
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 py-8 space-y-8">

        @if(session('status'))
            <div class="flex items-center gap-2 bg-green-50 border border-green-200 rounded-xl px-5 py-3 text-sm text-green-700">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('status') }}
            </div>
        @endif

        {{-- ── CASOS ACTIVOS ─────────────────────────────────────── --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-gray-800 text-sm">Casos activos</h3>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $activos->count() }} en proceso</p>
                </div>
                @if($activos->isNotEmpty())
                    <span class="text-xs px-3 py-1 bg-blue-100 text-blue-700 rounded-full font-semibold">
                        En atención
                    </span>
                @endif
            </div>

            @if($activos->isEmpty())
                <div class="px-6 py-14 text-center">
                    <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-500">No tienes casos activos asignados.</p>
                    <p class="text-xs text-gray-400 mt-1">Ofrécete en la bandeja de tu área para recibir un caso.</p>
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($activos as $exp)
                    @php
                        $sol    = $exp->solicitudes->first();
                        $p      = $sol?->migrantePerfil;
                        $nombre = $p ? trim($p->nombre . ' ' . $p->primer_apellido) : '—';
                    @endphp
                    <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center gap-4 hover:bg-gray-50 transition">
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <span class="font-mono text-xs font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded">
                                    {{ $exp->folio ?? 'Sin folio' }}
                                </span>
                                <span class="text-xs px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full font-semibold">
                                    En proceso
                                </span>
                                <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">
                                    {{ $exp->area?->nombre ?? '—' }}
                                </span>
                            </div>
                            <p class="text-sm font-medium text-gray-800">{{ $nombre }}</p>
                            @if($sol)
                            <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $sol->descripcion }}</p>
                            @endif
                            <p class="text-xs text-gray-400 mt-1">Abierto {{ $exp->created_at->diffForHumans() }}</p>
                        </div>
                        <a href="{{ route('casos.show', $exp->id) }}"
                           class="shrink-0 inline-flex items-center gap-1.5 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-full transition shadow-sm">
                            Abrir caso
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ── CASOS RESUELTOS ───────────────────────────────────── --}}
        @if($terminados->isNotEmpty())
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800 text-sm">Casos resueltos</h3>
                <p class="text-xs text-gray-400 mt-0.5">Últimos {{ $terminados->count() }}</p>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($terminados as $exp)
                @php
                    $sol    = $exp->solicitudes->first();
                    $p      = $sol?->migrantePerfil;
                    $nombre = $p ? trim($p->nombre . ' ' . $p->primer_apellido) : '—';
                @endphp
                <div class="px-6 py-3 flex items-center gap-4 hover:bg-gray-50 transition">
                    <span class="font-mono text-xs font-bold text-gray-500 bg-gray-50 px-2 py-0.5 rounded w-32 shrink-0">
                        {{ $exp->folio ?? '—' }}
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-700 truncate">{{ $nombre }}</p>
                        <p class="text-xs text-gray-400">{{ $exp->area?->nombre ?? '—' }}</p>
                    </div>
                    <span class="text-xs text-gray-400 shrink-0">
                        {{ $exp->resuelto_at?->format('d/m/Y') ?? $exp->updated_at->format('d/m/Y') }}
                    </span>
                    <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs font-semibold rounded-full shrink-0">
                        Resuelto
                    </span>
                    <a href="{{ route('casos.show', $exp->id) }}"
                       class="text-xs text-indigo-600 hover:underline shrink-0">Ver</a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</x-app-layout>
