<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Bandeja de Accesos Pendientes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <h3 class="text-lg font-bold mb-4 text-gray-800 border-b pb-2">Solicitudes Requiriendo Revisión</h3>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-600">
                            <thead class="text-xs text-gray-400 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">NOMBRE</th>
                                    <th scope="col" class="px-6 py-3">EMAIL</th>
                                    <th scope="col" class="px-6 py-3">ÁREA</th>
                                    <th scope="col" class="px-6 py-3">ROL SOLICITADO</th>
                                    <th scope="col" class="px-6 py-3 text-right">ACCIÓN</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pendientes as $usuario)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-6 py-4 font-medium text-gray-900">{{ $usuario->name }}</td>
                                        <td class="px-6 py-4">{{ $usuario->email }}</td>
                                        <td class="px-6 py-4">{{ $usuario->area ? $usuario->area->nombre : 'N/A' }}</td>
                                        <td class="px-6 py-4">{{ $usuario->role ? $usuario->role->name : 'N/A' }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <form action="{{ route('users.approve', $usuario->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-md transition-colors">
                                                    APROBAR ALTA
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                            🎉 ¡Todo al día! No hay solicitudes de acceso pendientes.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>