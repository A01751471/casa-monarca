<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">Certificados Digitales</h2>
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

        {{-- Nota de privacidad --}}
        <div class="flex items-start gap-3 bg-green-50 border border-green-200 rounded-xl px-5 py-4">
            <svg class="w-4 h-4 text-green-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            <p class="text-xs text-green-700 leading-relaxed">
                Solo se muestra la <strong>llave pública</strong> y los metadatos del certificado.
                Las llaves privadas <strong>nunca se almacenan</strong> en este sistema.
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">Todos los certificados</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ $certificados->count() }} certificado(s) en el sistema</p>
            </div>

            @if($certificados->isEmpty())
                <div class="px-6 py-12 text-center">
                    <p class="text-sm text-gray-500">No hay certificados registrados aún.</p>
                    <p class="text-xs text-gray-400 mt-1">Los certificados se generan al aprobar colaboradores.</p>
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($certificados as $cert)
                    <div class="px-6 py-4 hover:bg-gray-50 transition">
                        <div class="flex flex-col sm:flex-row sm:items-start gap-4">

                            {{-- Icono de estado --}}
                            <div class="shrink-0 mt-0.5">
                                @if($cert->status === 'activo')
                                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                    </div>
                                @else
                                    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            {{-- Info principal --}}
                            <div class="flex-1 min-w-0 space-y-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="text-sm font-semibold text-gray-800">
                                        {{ $cert->user?->name ?? '(usuario eliminado)' }}
                                    </span>
                                    @if($cert->status === 'activo')
                                        <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Activo</span>
                                    @elseif($cert->status === 'revocado')
                                        <span class="px-2 py-0.5 bg-red-100 text-red-700 text-xs font-semibold rounded-full">Revocado</span>
                                    @else
                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full">Vencido</span>
                                    @endif
                                    <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-full">{{ $cert->algoritmo }}</span>
                                </div>

                                <p class="text-xs text-gray-500 font-mono truncate">
                                    SHA-256: {{ $cert->fingerprint }}
                                </p>

                                <div class="flex flex-wrap gap-4 text-xs text-gray-400 mt-1">
                                    <span>Emitido: <span class="text-gray-600">{{ $cert->emitido_at->format('d/m/Y') }}</span></span>
                                    <span>Vence: <span class="{{ $cert->vence_at->isPast() ? 'text-red-500' : 'text-gray-600' }}">{{ $cert->vence_at->format('d/m/Y') }}</span></span>
                                    @if($cert->revocado_at)
                                        <span>Revocado: <span class="text-red-500">{{ $cert->revocado_at->format('d/m/Y H:i') }}</span></span>
                                    @endif
                                    <span>Emitido por: <span class="text-gray-600">{{ $cert->emisor?->name ?? 'Sistema' }}</span></span>
                                </div>

                                {{-- Llave pública colapsable --}}
                                <details class="mt-2">
                                    <summary class="text-xs text-indigo-500 hover:text-indigo-700 cursor-pointer select-none">
                                        Ver llave pública
                                    </summary>
                                    <textarea readonly rows="5"
                                              class="mt-2 w-full font-mono text-xs bg-gray-950 text-green-400 rounded-lg p-3 border-0 resize-none">{{ $cert->public_key }}</textarea>
                                </details>
                            </div>

                            {{-- Acción revocar --}}
                            @if($cert->status === 'activo')
                            <div class="shrink-0">
                                <form action="{{ route('admin.certificados.revoke', $cert->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            onclick="return confirm('¿Revocar este certificado? El colaborador no podrá firmar documentos hasta que se genere uno nuevo.')"
                                            class="inline-flex items-center gap-1.5 px-4 py-2 border border-red-300 text-red-600 hover:bg-red-50 text-xs font-semibold rounded-full transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                        </svg>
                                        Revocar
                                    </button>
                                </form>
                            </div>
                            @endif

                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
