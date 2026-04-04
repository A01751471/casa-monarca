<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de Control - Casa Monarca') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-sm font-medium text-gray-500 truncate">Total Colaboradores Activos</div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">{{ $totalUsuarios }}</div>
                </div>
            </div>

            <h3 class="text-lg font-bold mb-4 text-gray-700">Distribución por Áreas</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($areas as $area)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <h4 class="font-bold text-gray-800">{{ $area->nombre }}</h4>
                        <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-sm font-bold">
                            {{ $area->users_count }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Personal registrado en esta área.</p>
                    <div class="mt-4">
                        <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Gestionar área →</a>
                    </div>
                </div>
                @endforeach
            </div> 
            <div class="mt-12 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
            <h3 class="text-lg font-bold mb-4 text-gray-700">Solicitudes de Acceso Pendientes</h3>

                @if($pendientes->isEmpty())
                    <p class="text-gray-500 italic">No hay solicitudes nuevas por el momento.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Área</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($pendientes as $p)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $p->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $p->area->nombre ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $p->role->n ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <form action="{{ route('users.approve', $p->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-sm">
                                                Aprobar Alta
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
