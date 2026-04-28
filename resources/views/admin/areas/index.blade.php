<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">Áreas operativas</h2>
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

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">Estado de los equipos</h3>
                <p class="text-xs text-gray-400 mt-0.5">Vista general de la estructura interna de Casa Monarca</p>
            </div>

            <div class="divide-y divide-gray-100">
                @foreach($areas as $area)
                @php
                    $coordinadores = $area->users->where('role_id', 2)->where('status', 'alta');
                    $totalActivos  = $area->users->where('status', 'alta')->count();
                    $pendientes    = \App\Models\Solicitud::where('area_id', $area->id)
                                        ->where('status', 'pendiente')->count();
                @endphp
                <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center gap-4 hover:bg-gray-50 transition">

                    {{-- Nombre y coordinadores --}}
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800">{{ $area->nombre }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            @if($coordinadores->isNotEmpty())
                                Coord: {{ $coordinadores->pluck('name')->join(', ') }}
                            @else
                                <span class="italic">Sin coordinador asignado</span>
                            @endif
                        </p>
                    </div>

                    {{-- Stats --}}
                    <div class="flex gap-4 shrink-0 text-center">
                        <div>
                            <p class="text-xl font-bold text-indigo-600">{{ $totalActivos }}</p>
                            <p class="text-xs text-gray-400">Personal</p>
                        </div>
                        @if($pendientes > 0)
                        <div>
                            <p class="text-xl font-bold text-amber-500">{{ $pendientes }}</p>
                            <p class="text-xs text-amber-400">Solicitudes</p>
                        </div>
                        @endif
                    </div>

                    {{-- Acciones según rol --}}
                    <div class="flex gap-2 shrink-0">
                        @can('puede-eliminar')
                            <a href="{{ route('admin.areas.show', $area->id) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-gray-300 bg-white text-gray-700 text-xs font-medium hover:bg-gray-50 transition">
                                Ver equipo
                            </a>
                        @endcan
                        @if(auth()->user()->role_id <= 3)
                            <a href="{{ route('casos.bandeja', $area->id) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-indigo-300 bg-indigo-50 text-indigo-700 text-xs font-medium hover:bg-indigo-100 transition">
                                @if($pendientes > 0)
                                    <span class="w-4 h-4 bg-amber-500 text-white text-xs font-bold rounded-full flex items-center justify-center shrink-0">
                                        {{ $pendientes > 9 ? '9+' : $pendientes }}
                                    </span>
                                @endif
                                Bandeja
                            </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</x-app-layout>
