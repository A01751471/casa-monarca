<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">Bandeja de Accesos</h2>
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 py-8 space-y-6">

        {{-- Aviso PKI — solo aplica a coordinadores --}}
        <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-xl px-5 py-4">
            <svg class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <div class="text-xs text-amber-700 leading-relaxed space-y-1">
                <p>
                    <strong>Coordinadores:</strong> Al aprobar se genera un par de llaves RSA-2048.
                    La llave privada se muestra <strong>una sola vez</strong> — entréguela en persona o por canal cifrado.
                    La llave privada <strong>nunca se almacena</strong> en el sistema.
                </p>
                <p>
                    <strong>Operativos, Usuarios y Migrantes:</strong> Solo se habilita el acceso con password.
                    No se genera certificado digital.
                </p>
            </div>
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
                    @php $esCoordinador = $usuario->role_id === 2; @endphp
                    <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center gap-4 hover:bg-gray-50 transition">

                        {{-- Avatar + info --}}
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0 font-bold text-sm
                                {{ $esCoordinador ? 'bg-indigo-100 text-indigo-700' : 'bg-teal-100 text-teal-700' }}">
                                {{ strtoupper(substr($usuario->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-800 truncate">{{ $usuario->name }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ $usuario->email }}</p>
                            </div>
                        </div>

                        {{-- Rol, área y antigüedad --}}
                        <div class="flex gap-2 text-xs flex-wrap items-center">
                            {{-- Nivel con etiqueta de permisos --}}
                            @if($esCoordinador)
                                <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-full font-semibold flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    Coordinador · CRU + PKI
                                </span>
                            @elseif($usuario->role_id === 3)
                                <span class="px-2.5 py-1 bg-teal-50 text-teal-700 rounded-full font-semibold">
                                    Operativo · CR
                                </span>
                            @elseif($usuario->role_id === 4)
                                <span class="px-2.5 py-1 bg-green-50 text-green-700 rounded-full font-semibold">
                                    Usuario · C
                                </span>
                            @else
                                <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-full font-semibold">
                                    {{ $usuario->role?->name ?? '—' }}
                                </span>
                            @endif

                            @if($usuario->area)
                                <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-full">
                                    {{ $usuario->area->nombre }}
                                </span>
                            @endif
                            <span class="text-gray-400">
                                Registrado {{ $usuario->created_at->diffForHumans() }}
                            </span>
                        </div>

                        {{-- Acciones --}}
                        <div class="flex items-center gap-2 shrink-0">
                            @if($esCoordinador)
                                {{-- Aprobar coordinador → genera PKI --}}
                                <form action="{{ route('users.approve', $usuario->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            onclick="return confirm('¿Aprobar a {{ addslashes($usuario->name) }} como Coordinador?\n\nSe generará su certificado digital RSA-2048.\nLa llave privada se mostrará UNA SOLA VEZ.')"
                                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-full transition shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                        Aprobar + Certificado
                                    </button>
                                </form>
                            @else
                                {{-- Aprobar operativo/usuario/migrante → solo password --}}
                                <form action="{{ route('users.approve', $usuario->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            onclick="return confirm('¿Habilitar acceso para {{ addslashes($usuario->name) }}?\n\nSolo se activará su cuenta con password. Sin certificado digital.')"
                                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-full transition shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Habilitar acceso
                                    </button>
                                </form>
                            @endif

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
