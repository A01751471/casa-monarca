<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Áreas - Casa Monarca') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('areas.store') }}" method="POST" class="mb-8">
                    @csrf
                    <div class="flex items-center gap-4">
                        <div>
                            <x-input-label for="nombre" :value="__('Nombre de la nueva Área')" />
                            <x-text-input id="nombre" name="nombre" type="text" class="mt-1 block w-full" required />
                        </div>
                        <div class="mt-6">
                            <x-primary-button>Crear Área</x-primary-button>
                        </div>
                    </div>
                </form>

                <hr class="mb-6">

                <h3 class="text-lg font-medium text-gray-900 mb-4">Áreas actuales</h3>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($areas as $area)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $area->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $area->nombre }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>