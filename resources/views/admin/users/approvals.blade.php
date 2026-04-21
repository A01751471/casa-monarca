<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">Bandeja de Accesos</h2>
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 py-8 space-y-6">

        {{-- Aviso PKI --}}
        <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-xl px-5 py-4">
            <svg class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <p class="text-xs text-amber-700 leading-relaxed">
                <strong>Al aprobar</strong> se genera automáticamente un par de llaves RSA-2048.
                La llave privada se muestra <strong>una sola vez</strong> — para colaboradores, entréguela
                en persona; para <strong>migrantes</strong>, descargue el archivo .pem y guárdelo en un USB.
                La llave privada <strong>nunca se almacena</strong> en el sistema.
            </p>
        </div>

        @if(session('status'))
            <div class="flex items-center gap-2 bg-green-50 border border-green-200 rounded-xl px-5 py-3 text-sm text-green-700">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('status') }}
            </div>
        @endif

        {{-- Tabla de pendientes --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-gray-800">Solicitudes pendientes de revisión</h3>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $pendientes->count() }} solicitud(es) en espera</p>
                </div>
            </div>

            @if($pendientes->isEmpty())
                <div class="px-6 py-12 text-center">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-700">Todo al día</p>
                    <p class="text-xs text-gray-400 mt-1">No hay solicitudes de acceso pendientes.</p>
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($pendientes as $usuario)
                    <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center gap-4 hover:bg-gray-50 transition">

                        {{-- Avatar + info --}}
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <div class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center shrink-0 text-indigo-700 font-bold text-sm">
                                {{ strtoupper(substr($usuario->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-800 truncate">{{ $usuario->name }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ $usuario->email }}</p>
                            </div>
                        </div>

                        {{-- Rol y área --}}
                        <div class="flex gap-3 text-xs flex-wrap">
                            <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-full font-medium">
                                {{ $usuario->role?->name ?? '—' }}
                            </span>
                            <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-full font-medium">
                                {{ $usuario->area?->nombre ?? 'Sin área' }}
                            </span>
                            <span class="px-2.5 py-1 bg-gray-100 text-gray-500 rounded-full">
                                Registrado {{ $usuario->created_at->diffForHumans() }}
                            </span>
                        </div>

                        {{-- Acciones --}}
                        <div class="flex items-center gap-2 shrink-0">
                            {{-- Aprobar → genera PKI --}}
                            <form action="{{ route('users.approve', $usuario->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        onclick="return confirm('¿Aprobar a {{ addslashes($usuario->name) }}? Se generará su certificado digital RSA-2048.')"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-full transition shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    Aprobar + Certificado
                                </button>
                            </form>

                            {{-- Rechazar --}}
                            <form action="{{ route('users.reject', $usuario->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        onclick="return confirm('¿Rechazar la solicitud de {{ addslashes($usuario->name) }}?')"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 border border-red-300 text-red-600 hover:bg-red-50 text-xs font-semibold rounded-full transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Rechazar
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
