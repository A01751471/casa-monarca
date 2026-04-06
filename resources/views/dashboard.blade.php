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
                        <a href="{{ route('admin.areas.show', $area->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Gestionar área →</a>
                    </div>
                </div>
                @endforeach
            </div> 
        </div>
    </div>
</x-app-layout>
