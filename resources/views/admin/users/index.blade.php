<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Directorio Global de Usuarios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h3 class="text-lg font-bold text-gray-800">Todos los Usuarios Registrados</h3>
                        </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-600">
                            <thead class="text-xs text-gray-400 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">USUARIO</th>
                                    <th scope="col" class="px-6 py-3">ROL</th>
                                    <th scope="col" class="px-6 py-3">ÁREA</th>
                                    <th scope="col" class="px-6 py-3 text-center">ESTATUS</th>
                                    <th scope="col" class="px-6 py-3 text-center">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <span class="font-bold text-gray-900">{{ $user->name }}</span><br>
                                            <span class="text-xs text-gray-500">{{ $user->email }}</span>
                                        </td>

                                        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            
                                            <td class="px-6 py-4">
                                                <select name="role_id" class="rounded-md border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                                            {{ $role->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td class="px-6 py-4">
                                                <select name="area_id" class="rounded-md border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                                    <option value="">-- Sin Área --</option>
                                                    @foreach($areas as $area)
                                                        <option value="{{ $area->id }}" {{ $user->area_id == $area->id ? 'selected' : '' }}>
                                                            {{ $area->nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td class="px-6 py-4 text-center">
                                                @if($user->status == 'alta')
                                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full">Activo</span>
                                                @elseif($user->status == 'pendiente')
                                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-bold rounded-full">Pendiente</span>
                                                @else
                                                    <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-bold rounded-full">Baja/Suspendido</span>
                                                @endif
                                            </td>

                                            <td class="px-6 py-4 text-center space-x-2 flex justify-center">
                                                <button type="submit" class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded">
                                                    Guardar
                                                </button>
                                        </form>

                                                @if($user->status == 'alta')
                                                    <form action="{{ route('users.revoke', $user->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded" onclick="return confirm('¿Seguro que deseas suspender a este usuario?')">
                                                            Suspender
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>