<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            Archivos · {{ $migrante->name }}
        </h2>
    </x-slot>

    <div class="max-w-5xl mx-auto px-4 py-8 space-y-6">

        @if(session('status'))
        <div class="flex items-center gap-2 bg-green-50 border border-green-200 rounded-xl px-5 py-3 text-sm text-green-700">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('status') }}
        </div>
        @endif

        {{-- Breadcrumb + perfil --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm px-6 py-5">
            <div class="text-xs text-gray-400 mb-3">
                <a href="{{ route('admin.archivos.index') }}" class="hover:text-indigo-500">Archivos</a>
                <span class="mx-1">/</span>
                <span class="text-gray-600 font-medium">{{ $migrante->name }}</span>
            </div>
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-indigo-100 text-indigo-700 font-bold text-lg flex items-center justify-center shrink-0">
                    {{ strtoupper(substr($migrante->name, 0, 1)) }}
                </div>
                <div>
                    <p class="font-semibold text-gray-800">{{ $migrante->name }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">
                        {{ $migrante->migrantePerfil?->pais_origen ?? '—' }}
                        @if($migrante->migrantePerfil?->fecha_atencion)
                        · Ingreso {{ \Carbon\Carbon::parse($migrante->migrantePerfil->fecha_atencion)->format('d/m/Y') }}
                        @endif
                        · {{ $documentos->count() }} documento(s)
                    </p>
                </div>
            </div>
        </div>

        {{-- Documentos --}}
        @if($documentos->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm px-6 py-12 text-center text-sm text-gray-400">
            Este migrante no ha subido documentos de identidad.
        </div>
        @else
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px;">
            @foreach($documentos as $doc)
            @php
                $ext     = strtolower($doc->tipo);
                $isImage = in_array($ext, ['jpg','jpeg','png']);
                $selloOk = $doc->sello_valido ?? false;
            @endphp
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex flex-col">

                {{-- Barra de estado de integridad --}}
                <div style="height:3px;background:{{ $selloOk ? '#10b981' : '#e5e7eb' }};"></div>

                {{-- Ícono --}}
                <div class="bg-gray-50 border-b border-gray-100 px-5 py-5 flex flex-col items-center gap-2">
                    <div class="w-14 h-14 rounded-xl bg-white border border-gray-200 flex items-center justify-center shadow-sm">
                        <svg class="w-7 h-7 {{ $isImage ? 'text-orange-500' : 'text-red-500' }}"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                  d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    {{-- Sello de integridad --}}
                    @if($selloOk)
                    <div id="badge-{{ $doc->id }}"
                         class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-100 border border-green-300 text-green-700 text-xs font-semibold rounded-full cursor-pointer select-none"
                         onclick="toggleHash('{{ $doc->id }}')"
                         title="Clic para ver código de integridad">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        SELLADO Y ASEGURADO
                    </div>
                    <div id="hash-{{ $doc->id }}" class="hidden text-center">
                        <p class="text-xs text-gray-400 mb-1">Código de integridad</p>
                        <code class="text-xs font-mono text-gray-600 break-all">{{ $doc->hash_sha256 }}</code>
                    </div>
                    @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-gray-100 text-gray-500 text-xs font-semibold rounded-full">
                        Sin sello
                    </span>
                    @endif
                </div>

                {{-- Info --}}
                <div class="px-4 py-3 flex-1">
                    <p class="text-sm font-semibold text-gray-800 leading-snug mb-1">{{ $doc->nombre }}</p>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-xs font-bold uppercase tracking-wide text-orange-700 bg-orange-50 border border-orange-200 px-2 py-0.5 rounded-full">
                            {{ $doc->etiqueta }}
                        </span>
                        <span class="text-xs text-gray-400">.{{ strtoupper($ext) }}</span>
                        <span class="text-xs text-gray-400">{{ $doc->created_at->format('d/m/Y') }}</span>
                    </div>
                    @if($doc->sellado_at)
                    <p class="text-xs text-gray-400 mt-1">Sellado {{ $doc->sellado_at->format('d/m/Y H:i') }}</p>
                    @endif
                </div>

                {{-- Acciones --}}
                <div class="px-4 pb-4 flex gap-2">
                    <a href="{{ route('documentos.download', $doc->id) }}"
                       class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Descargar
                    </a>
                    {{-- Verificación profunda (lee el archivo del disco) --}}
                    <button onclick="verificarIntegridad({{ $doc->id }})"
                            id="btn-verificar-{{ $doc->id }}"
                            class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-semibold rounded-lg transition"
                            title="Verificar integridad del archivo en disco">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </button>
                </div>
                <div id="verificar-result-{{ $doc->id }}" class="hidden px-4 pb-3 text-xs"></div>

            </div>
            @endforeach
        </div>
        @endif

    </div>

    <script>
    function toggleHash(docId) {
        const el = document.getElementById('hash-' + docId);
        el.classList.toggle('hidden');
    }

    async function verificarIntegridad(docId) {
        const btn = document.getElementById('btn-verificar-' + docId);
        const resultEl = document.getElementById('verificar-result-' + docId);
        btn.disabled = true;
        btn.innerHTML = '<svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-dasharray="30 70"/></svg>';

        try {
            const resp = await fetch('{{ url("/admin/archivos/doc") }}/' + docId + '/verificar', {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await resp.json();

            let html, color;
            if (data.status === 'integro') {
                color = 'text-green-700 bg-green-50 border-green-200';
                html = '✓ Archivo íntegro · hash verificado en disco';
            } else if (data.status === 'corrupto') {
                color = 'text-red-700 bg-red-50 border-red-200';
                html = '⚠ Hash no coincide · el archivo puede haber sido modificado';
            } else if (data.status === 'sin_sello') {
                color = 'text-amber-700 bg-amber-50 border-amber-200';
                html = '○ Archivo íntegro pero sin sello de confirmación del migrante';
            } else {
                color = 'text-gray-600 bg-gray-50 border-gray-200';
                html = 'Archivo no encontrado en storage';
            }

            resultEl.className = 'px-4 pb-3 text-xs border rounded-b-xl mx-4 mb-3 py-2 ' + color;
            resultEl.innerHTML = html;
            resultEl.classList.remove('hidden');
        } catch {
            resultEl.className = 'px-4 pb-3 text-xs text-red-600';
            resultEl.textContent = 'Error al verificar.';
            resultEl.classList.remove('hidden');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>';
        }
    }
    </script>
</x-app-layout>
