<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Aprobar Colaboradores - Casa Monarca') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="min-w-full bg-white border">
                        <thead>
                            <tr class="bg-gray-100 border-b">
                                <th class="px-6 py-3 text-left">Usuario</th>
                                <th class="px-6 py-3 text-left">Rol a Asignar</th>
                                <th class="px-6 py-3 text-left">Área a Asignar</th>
                                <th class="px-6 py-3 text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <span class="font-bold">{{ $user->name }}</span><br>
                                    <span class="text-xs text-gray-500">{{ $user->email }}</span>
                                </td>
                                
                                <td class="px-6 py-4">
                                    {{-- Selector de Rol con ID único --}}
                                    <select id="role-{{ $user->id }}" class="rounded-md border-gray-300 text-sm">
                                        <option value="migrante" {{ $user->role_requested == 'migrante' ? 'selected' : '' }}>Migrante</option>
                                        <option value="voluntario" {{ $user->role_requested == 'voluntario' ? 'selected' : '' }}>Voluntario</option>
                                        <option value="admin" {{ $user->role_requested == 'admin' ? 'selected' : '' }}>Administrador</option>
                                    </select>
                                </td>

                                <td class="px-6 py-4">
                                    {{-- Selector de Área con ID único --}}
                                    <select id="area-{{ $user->id }}" class="rounded-md border-gray-300 text-sm">
                                        <option value="">Seleccionar Área...</option>
                                        @foreach($areas as $area)
                                            <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                                        @endforeach
                                    </select>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    {{-- El Formulario real vive aquí --}}
                                    <form action="{{ route('users.approve', $user) }}" method="POST" 
                                        onsubmit="document.getElementById('hidden-area-{{ $user->id }}').value = document.getElementById('area-{{ $user->id }}').value; 
                                                    document.getElementById('hidden-role-{{ $user->id }}').value = document.getElementById('role-{{ $user->id }}').value;">
                                        @csrf
                                        <input type="hidden" name="area_id" id="hidden-area-{{ $user->id }}">
                                        <input type="hidden" name="role_requested" id="hidden-role-{{ $user->id }}">
                                        
                                        <button type="submit" class="!bg-green-600 hover:!bg-green-700 text-white font-bold py-2 px-4 rounded shadow-md text-sm transition-colors duration-150">
                                            Aprobar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($users->isEmpty())
                        <div class="text-center py-10 text-gray-500">
                            No hay solicitudes pendientes en este momento.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>