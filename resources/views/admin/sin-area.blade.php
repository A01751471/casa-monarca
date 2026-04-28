<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            Colaboradores sin área
        </h2>
    </x-slot>

    <div class="max-w-5xl mx-auto px-4 py-8 space-y-8">

        @if(session('status'))
            <div class="flex items-center gap-2 bg-green-50 border border-green-200 rounded-xl px-5 py-3 text-sm text-green-700">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('status') }}
            </div>
        @endif
        @if(session('error'))
            <div class="flex items-center gap-2 bg-red-50 border border-red-200 rounded-xl px-5 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        {{-- ── SOLICITUDES PENDIENTES ─────────────────────────────────── --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-gray-800 text-sm">Solicitudes de membresía pendientes</h3>
                    <p class="text-xs text-gray-400 mt-0.5">
                        {{ $solicitudes->count() }} solicitud(es) esperando respuesta
                        @if(!$esAdmin) · solo las de tu área @endif
                    </p>
                </div>
                @if($solicitudes->isNotEmpty())
                    <span class="text-xs px-3 py-1 bg-amber-100 text-amber-700 rounded-full font-semibold">
                        Requieren acción
                    </span>
                @endif
            </div>

            @if($solicitudes->isEmpty())
                <div class="px-6 py-10 text-center text-sm text-gray-400">
                    No hay solicitudes pendientes.
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($solicitudes as $sol)
                    <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-1">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold flex items-center justify-center shrink-0">
                                    {{ strtoupper(substr($sol->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $sol->user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $sol->user->role?->name }} · {{ $sol->user->email }}</p>
                                </div>
                            </div>
                            <div class="ml-11 space-y-1">
                                <p class="text-xs text-gray-600">
                                    Solicita unirse a
                                    <strong class="text-indigo-700">{{ $sol->area?->nombre }}</strong>
                                </p>
                                @if($sol->nota)
                                    <p class="text-xs text-gray-500 italic">"{{ $sol->nota }}"</p>
                                @endif
                                <p class="text-xs text-gray-400">
                                    Enviada {{ $sol->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0 ml-11 sm:ml-0">
                            <form action="{{ route('membresia.aprobar', $sol->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        onclick="return confirm('¿Aprobar a {{ addslashes($sol->user->name) }} en {{ addslashes($sol->area?->nombre) }}?')"
                                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-full transition">
                                    Aprobar
                                </button>
                            </form>
                            <form action="{{ route('membresia.rechazar', $sol->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        onclick="return confirm('¿Rechazar la solicitud de {{ addslashes($sol->user->name) }}?')"
                                        class="px-3 py-2 border border-red-300 text-red-600 hover:bg-red-50 text-xs font-semibold rounded-full transition">
                                    Rechazar
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ── USUARIOS SIN ÁREA ─────────────────────────────────────── --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800 text-sm">Colaboradores activos sin área</h3>
                <p class="text-xs text-gray-400 mt-0.5">
                    {{ $sinArea->count() }} usuario(s) activos sin área asignada.
                    Puedes asignarlos directamente sin esperar solicitud.
                </p>
            </div>

            @if($sinArea->isEmpty())
                <div class="px-6 py-10 text-center text-sm text-gray-400">
                    Todos los colaboradores activos tienen área asignada.
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($sinArea as $u)
                    <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center gap-4"
                         x-data="{ open: false }">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <div class="w-8 h-8 rounded-full bg-gray-100 text-gray-600 text-xs font-bold flex items-center justify-center shrink-0">
                                {{ strtoupper(substr($u->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ $u->name }}</p>
                                <p class="text-xs text-gray-400">{{ $u->role?->name }} · {{ $u->email }}</p>
                            </div>
                        </div>

                        <div class="shrink-0 sm:ml-0">
                            <button @click="open = !open"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-full transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Asignar a área
                            </button>
                        </div>

                        {{-- Formulario de asignación directa --}}
                        <div x-show="open" style="display:none"
                             class="w-full sm:col-span-full mt-2 bg-indigo-50 border border-indigo-200 rounded-xl p-4">
                            <form action="{{ route('membresia.asignar') }}" method="POST"
                                  class="flex flex-wrap items-end gap-3">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $u->id }}">
                                <div class="flex-1 min-w-[200px]">
                                    <label class="block text-xs font-semibold text-indigo-700 mb-1">Área de destino</label>
                                    <select name="area_id" required
                                            class="w-full border border-indigo-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                                        <option value="">— Selecciona —</option>
                                        @foreach($areas as $area)
                                            <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit"
                                        class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-full transition">
                                    Confirmar asignación
                                </button>
                                <button type="button" @click="open = false"
                                        class="px-3 py-2 text-xs text-gray-500 hover:text-gray-700 transition">
                                    Cancelar
                                </button>
                            </form>
                        </div>

                    </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
