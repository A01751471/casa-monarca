<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 flex justify-between items-center">
                <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    Gestión del Área: <span class="text-blue-500">{{ $area->nombre }}</span>
                </h2>
                <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-white transition">
                    &larr; Volver al Panel
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-visible shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full text-left text-sm whitespace-nowrap text-gray-200">
                    <thead class="uppercase tracking-wider border-b-2 dark:border-gray-600">
                        <tr>
                            <th scope="col" class="px-6 py-4">Nombre</th>
                            <th scope="col" class="px-6 py-4">Email</th>
                            <th scope="col" class="px-6 py-4">Rol</th>
                            <th scope="col" class="px-6 py-4">Estatus</th>
                            <th scope="col" class="px-6 py-4 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($area->users as $user)
                            <tr class="border-b dark:border-gray-700 hover:bg-gray-700 transition">
                                <td class="px-6 py-4">{{ $user->name }}</td>
                                <td class="px-6 py-4">{{ $user->email }}</td>
                                <td class="px-6 py-4">{{ $user->role->name ?? 'Sin rol' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-1 rounded text-xs font-bold 
                                        {{ $user->status === 'alta' ? 'bg-green-500 text-white' : 'bg-yellow-500 text-black' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($user->status === 'pendiente')
                                        <div class="flex justify-center space-x-2">
                                            <form action="{{ route('users.approve', $user->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-green-600 hover:bg-green-500 text-white font-bold py-1 px-3 rounded text-xs transition">Aprobar</button>
                                            </form>
                                            <form action="{{ route('users.reject', $user->id) }}" method="POST" onsubmit="return confirm('¿Rechazar esta solicitud?');">
                                                @csrf
                                                <button type="submit" class="bg-red-600 hover:bg-red-500 text-white font-bold py-1 px-3 rounded text-xs transition">Rechazar</button>
                                            </form>
                                        </div>

                                    @elseif ($user->status === 'revocacion')
                                        <form action="{{ route('users.restore', $user->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-1 px-3 rounded text-xs transition">Ratificar Acceso</button>
                                        </form>

                                    @else
                                        <div x-data="{ open: false }" class="relative inline-block text-left">
                                            <button @click="open = !open" @click.away="open = false" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-3 py-1 bg-white text-xs font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                                                Acciones
                                                <svg class="-mr-1 ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>

                                            <div x-show="open" style="display: none;" class="origin-top-right absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                                <div class="py-1">
                                                    
                                                    @if ($user->status === 'alta')
                                                        <form action="{{ route('users.toggleRole', $user->id) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="w-full text-left block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 hover:text-blue-600">
                                                                @if($user->role_id == 3) Ascender a Coordinador @else Degradar a Operativo @endif
                                                            </button>
                                                        </form>

                                                        <form action="{{ route('users.revoke', $user->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas revocar el acceso a este usuario?');">
                                                            @csrf
                                                            <button type="submit" class="w-full text-left block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 hover:text-yellow-600">
                                                                Revocar Acceso
                                                            </button>
                                                        </form>
                                                    @endif

                                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('ADVERTENCIA: ¿Borrar usuario permanentemente? Esta acción no se puede deshacer.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="w-full text-left block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 hover:text-red-600 font-bold">
                                                            Borrar Registro
                                                        </button>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-400">
                                    No hay colaboradores registrados en esta área aún.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>