<x-migrante-portal-layout>

<div class="max-w-xl space-y-5">

    {{-- Encabezado --}}
    <div>
        <a href="{{ route('migrante.solicitudes.index') }}"
           class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver a mis solicitudes
        </a>
        <div class="flex items-center gap-3">
            <span class="font-mono text-sm font-bold text-indigo-700 bg-indigo-50 px-3 py-1 rounded-full">
                {{ $expediente->folio ?? 'Sin folio' }}
            </span>
            @if($expediente->status === 'en_proceso')
                <span class="px-2.5 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">En proceso</span>
            @elseif($expediente->status === 'terminado')
                <span class="px-2.5 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Resuelto</span>
            @endif
        </div>
        <h1 class="text-xl font-bold text-gray-800 mt-2">Documentos de mi caso</h1>
        <p class="text-sm text-gray-500 mt-0.5">
            Área {{ $expediente->area?->nombre }} · estos documentos fueron generados por el equipo de Casa Monarca para su caso.
        </p>
    </div>

    {{-- Lista de documentos --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        @if($expediente->documentos->isEmpty())
            <div class="py-14 text-center">
                <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <p class="text-sm text-gray-500">Aún no hay documentos en su caso.</p>
                <p class="text-xs text-gray-400 mt-1">El equipo los irá agregando conforme avance su atención.</p>
            </div>
        @else
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                <p class="text-xs text-gray-500">{{ $expediente->documentos->count() }} documento(s) disponible(s)</p>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($expediente->documentos as $doc)
                <div class="px-5 py-4 flex items-center gap-4">
                    <div class="w-9 h-9 rounded-xl bg-indigo-50 flex items-center justify-center shrink-0">
                        @php
                            $icon = match(strtolower($doc->tipo)) {
                                'pdf'           => '📄',
                                'jpg', 'jpeg', 'png' => '🖼️',
                                'doc', 'docx'   => '📝',
                                default         => '📎',
                            };
                        @endphp
                        <span class="text-base">{{ $icon }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 leading-snug">{{ $doc->nombre }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            {{ strtoupper($doc->tipo) }}
                            · Subido por {{ $doc->autor?->name ?? 'Casa Monarca' }}
                            · {{ $doc->created_at->format('d/m/Y') }}
                        </p>
                    </div>
                    {{-- Solo lectura: no hay botón de descarga directo a storage privado --}}
                    <div class="shrink-0">
                        <span class="inline-flex items-center gap-1 text-xs text-gray-400 bg-gray-100 px-2.5 py-1 rounded-full">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Solo lectura
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Nota informativa --}}
    <div class="flex items-start gap-3 bg-blue-50 border border-blue-100 rounded-xl px-4 py-3">
        <svg class="w-4 h-4 text-blue-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
        </svg>
        <p class="text-xs text-blue-700 leading-relaxed">
            Estos documentos son generados y administrados por el personal de Casa Monarca.
            Si tiene alguna duda sobre su contenido, por favor contáctenos directamente.
        </p>
    </div>

</div>

</x-migrante-portal-layout>
