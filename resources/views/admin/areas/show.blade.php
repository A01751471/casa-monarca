<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            Área: {{ $area->nombre }}
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

        {{-- Breadcrumb + acciones de cabecera --}}
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 text-xs text-gray-400">
                <a href="{{ route('areas.index') }}" class="hover:text-indigo-500 transition">Áreas</a>
                <span>/</span>
                <span class="text-gray-600">{{ $area->nombre }}</span>
            </div>
            @if(auth()->user()->role_id <= 3)
                <a href="{{ route('casos.bandeja', $area->id) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full border border-indigo-300 bg-indigo-50 text-indigo-700 text-xs font-medium hover:bg-indigo-100 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    Ir a bandeja de solicitudes
                </a>
            @endif
        </div>

        {{-- Tabla de equipo --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-gray-800 text-sm">Equipo del área</h3>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $area->users->count() }} miembro(s) registrado(s)</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Rol</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Estatus</th>
                            @can('puede-eliminar')
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($area->users as $u)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-gray-800">{{ $u->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-3 text-gray-600">{{ $u->role?->name ?? '—' }}</td>
                            <td class="px-6 py-3 text-center">
                                @if($u->status === 'alta')
                                    <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Activo</span>
                                @elseif($u->status === 'pendiente')
                                    <span class="px-2 py-0.5 bg-amber-100 text-amber-700 text-xs font-semibold rounded-full">Pendiente</span>
                                @elseif($u->status === 'revocacion')
                                    <span class="px-2 py-0.5 bg-orange-100 text-orange-700 text-xs font-semibold rounded-full">Suspendido</span>
                                @else
                                    <span class="px-2 py-0.5 bg-gray-100 text-gray-500 text-xs font-semibold rounded-full">Baja</span>
                                @endif
                            </td>

                            {{-- Acciones: solo admin ve esta columna completa --}}
                            @can('puede-eliminar')
                            <td class="px-6 py-3 text-right">
                                @if($u->status === 'pendiente')
                                    <div class="flex justify-end gap-2">
                                        <form action="{{ route('users.approve', $u->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                    onclick="return confirm('¿Aprobar a {{ addslashes($u->name) }}?')"
                                                    class="px-3 py-1 bg-green-600 hover:bg-green-500 text-white text-xs font-bold rounded-full transition">
                                                Aprobar
                                            </button>
                                        </form>
                                        <form action="{{ route('users.reject', $u->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                    onclick="return confirm('¿Rechazar solicitud de {{ addslashes($u->name) }}?')"
                                                    class="px-3 py-1 bg-red-600 hover:bg-red-500 text-white text-xs font-bold rounded-full transition">
                                                Rechazar
                                            </button>
                                        </form>
                                    </div>
                                @elseif($u->status === 'revocacion')
                                    <form action="{{ route('users.restore', $u->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="px-3 py-1 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold rounded-full transition">
                                            Restaurar
                                        </button>
                                    </form>
                                @elseif($u->status === 'alta')
                                    <div x-data="{ open: false }" class="relative inline-block text-left">
                                        <button @click="open = !open" @click.away="open = false" type="button"
                                                class="inline-flex items-center gap-1 px-3 py-1 rounded-full border border-gray-300 bg-white text-xs font-medium text-gray-700 hover:bg-gray-50 focus:outline-none transition">
                                            Acciones
                                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                        <div x-show="open" style="display:none"
                                             class="absolute right-0 mt-1 w-44 rounded-xl shadow-lg bg-white border border-gray-100 z-50 overflow-hidden">
                                            <form action="{{ route('users.toggleRole', $u->id) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                        class="w-full text-left px-4 py-2.5 text-xs text-gray-700 hover:bg-gray-50 hover:text-indigo-600 transition">
                                                    {{ $u->role_id == 3 ? 'Ascender a Coordinador' : 'Cambiar a Operativo' }}
                                                </button>
                                            </form>
                                            <form action="{{ route('users.revoke', $u->id) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                        onclick="return confirm('¿Suspender acceso de {{ addslashes($u->name) }}?')"
                                                        class="w-full text-left px-4 py-2.5 text-xs text-gray-700 hover:bg-gray-50 hover:text-orange-600 transition">
                                                    Suspender acceso
                                                </button>
                                            </form>
                                            <form action="{{ route('users.destroy', $u->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        onclick="return confirm('¿BORRAR permanentemente a {{ addslashes($u->name) }}?')"
                                                        class="w-full text-left px-4 py-2.5 text-xs font-bold text-gray-700 hover:bg-gray-50 hover:text-red-600 transition border-t border-gray-100">
                                                    Eliminar usuario
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            @endcan
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-sm text-gray-400">
                                No hay colaboradores registrados en esta área.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ── Solicitudes de membresía pendientes (coordinador/admin) ── --}}
        @if(auth()->user()->role_id <= 2)
        @php $solicitudesArea = $area->solicitudesMembresia()->with('user.role')->oldest()->get(); @endphp
        @if($solicitudesArea->isNotEmpty())
        <div class="bg-white rounded-2xl border border-amber-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-amber-100 bg-amber-50 flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-amber-800 text-sm">Solicitudes de membresía</h3>
                    <p class="text-xs text-amber-600 mt-0.5">{{ $solicitudesArea->count() }} persona(s) piden unirse a esta área</p>
                </div>
                <span class="text-xs px-2.5 py-1 bg-amber-100 text-amber-700 rounded-full font-semibold">Pendientes</span>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($solicitudesArea as $sol)
                <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold flex items-center justify-center shrink-0">
                            {{ strtoupper(substr($sol->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ $sol->user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $sol->user->role?->name }} · {{ $sol->created_at->diffForHumans() }}</p>
                            @if($sol->nota)
                                <p class="text-xs text-gray-500 italic mt-0.5">"{{ $sol->nota }}"</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <form action="{{ route('membresia.aprobar', $sol->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    onclick="return confirm('¿Aprobar a {{ addslashes($sol->user->name) }} en esta área?')"
                                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-full transition">
                                Aprobar
                            </button>
                        </form>
                        <form action="{{ route('membresia.rechazar', $sol->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    onclick="return confirm('¿Rechazar esta solicitud?')"
                                    class="px-3 py-2 border border-red-300 text-red-600 hover:bg-red-50 text-xs font-semibold rounded-full transition">
                                Rechazar
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Remover miembros del área --}}
        @if($area->users->whereIn('role_id', [3, 4])->isNotEmpty())
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800 text-sm">Remover miembros</h3>
                <p class="text-xs text-gray-400 mt-0.5">Libera la asignación sin eliminar la cuenta.</p>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($area->users->whereIn('role_id', [3, 4]) as $u)
                <div class="px-6 py-3 flex items-center gap-3">
                    <div class="flex-1 min-w-0 flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-gray-100 text-gray-600 text-xs font-bold flex items-center justify-center shrink-0">
                            {{ strtoupper(substr($u->name, 0, 1)) }}
                        </div>
                        <span class="text-sm text-gray-700 font-medium">{{ $u->name }}</span>
                        <span class="text-xs text-gray-400">{{ $u->role?->name }}</span>
                    </div>
                    <form action="{{ route('membresia.remover', $u->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                onclick="return confirm('¿Remover a {{ addslashes($u->name) }} de esta área?')"
                                class="text-xs text-gray-400 hover:text-red-600 hover:underline transition">
                            Remover del área
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @endif

        {{-- Nota para niveles sin gestión --}}
        @cannot('puede-actualizar')
        <div class="flex items-start gap-3 bg-gray-50 border border-gray-200 rounded-xl px-5 py-4">
            <svg class="w-4 h-4 text-gray-400 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <p class="text-xs text-gray-500 leading-relaxed">
                Vista de solo lectura del equipo. Para gestionar usuarios contacta al coordinador o administrador.
            </p>
        </div>
        @endcannot

    </div>
</x-app-layout>
