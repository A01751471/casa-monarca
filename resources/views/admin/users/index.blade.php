<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">Gestión de Usuarios</h2>
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 py-8 space-y-6">

        @if(session('status'))
            <div class="flex items-center gap-2 bg-green-50 border border-green-200 rounded-xl px-5 py-3 text-sm text-green-700">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-gray-800">Directorio global de colaboradores</h3>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $users->count() }} usuarios registrados</p>
                </div>
                <a href="{{ route('admin.users.approvals') }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full border border-amber-300 bg-amber-50 text-amber-700 text-xs font-medium hover:bg-amber-100 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Ver pendientes
                </a>
            </div>

            <div class="divide-y divide-gray-100">
                @foreach($users as $user)
                <div class="px-6 py-4 hover:bg-gray-50 transition">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">

                        {{-- Avatar + info --}}
                        <div class="flex items-center gap-3 w-52 shrink-0">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0 text-sm font-bold
                                @if($user->status === 'alta') bg-indigo-100 text-indigo-700
                                @elseif($user->status === 'pendiente') bg-amber-100 text-amber-700
                                @else bg-gray-100 text-gray-500 @endif">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <a href="{{ route('admin.users.show', $user->id) }}"
                                   class="text-sm font-semibold text-gray-800 hover:text-indigo-600 transition truncate block">
                                    {{ $user->name }}
                                </a>
                                <p class="text-xs text-gray-400 truncate">{{ $user->email }}</p>
                            </div>
                        </div>

                        {{-- Formulario rol + área --}}
                        <form action="{{ route('admin.users.update', $user->id) }}" method="POST"
                              class="flex flex-1 flex-wrap items-center gap-3">
                            @csrf
                            @method('PATCH')

                            <select name="role_id"
                                    class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 bg-white focus:ring-1 focus:ring-indigo-400 focus:outline-none">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>

                            <select name="area_id"
                                    class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 bg-white focus:ring-1 focus:ring-indigo-400 focus:outline-none">
                                <option value="">Sin área</option>
                                @foreach($areas as $area)
                                    <option value="{{ $area->id }}" {{ $user->area_id == $area->id ? 'selected' : '' }}>
                                        {{ $area->nombre }}
                                    </option>
                                @endforeach
                            </select>

                            <button type="submit"
                                    class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-full transition">
                                Guardar
                            </button>
                        </form>

                        {{-- Estatus + acciones --}}
                        <div class="flex items-center gap-2 shrink-0">
                            @if($user->status === 'alta')
                                <span class="px-2.5 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Activo</span>
                                <form action="{{ route('users.revoke', $user->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            onclick="return confirm('¿Suspender a {{ addslashes($user->name) }}? Se revocarán sus certificados activos.')"
                                            class="px-3 py-1.5 border border-red-300 text-red-600 hover:bg-red-50 text-xs font-semibold rounded-full transition">
                                        Suspender
                                    </button>
                                </form>
                            @elseif($user->status === 'pendiente')
                                <span class="px-2.5 py-1 bg-amber-100 text-amber-700 text-xs font-semibold rounded-full">Pendiente</span>
                            @elseif($user->status === 'revocacion')
                                <span class="px-2.5 py-1 bg-orange-100 text-orange-700 text-xs font-semibold rounded-full">Suspendido</span>
                                <form action="{{ route('users.restore', $user->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="px-3 py-1.5 border border-green-300 text-green-600 hover:bg-green-50 text-xs font-semibold rounded-full transition">
                                        Restaurar
                                    </button>
                                </form>
                            @else
                                <span class="px-2.5 py-1 bg-gray-100 text-gray-500 text-xs font-semibold rounded-full">Baja</span>
                            @endif

                            {{-- Ver detalle --}}
                            <a href="{{ route('admin.users.show', $user->id) }}"
                               class="p-1.5 text-gray-400 hover:text-indigo-600 transition" title="Ver detalle">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                        </div>

                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
