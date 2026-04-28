<x-app-layout>
@php
    $esMigrante      = ($approvedUserRole ?? 0) === 5;
    $esCoordinador   = ($approvedUserRole ?? 0) === 2;
    $credencial      = $privateKey; // para coordinador = PEM, para migrante = password
@endphp

<div class="max-w-2xl mx-auto py-12 px-4">

    {{-- Alerta crítica --}}
    <div class="border-l-4 rounded-xl p-5 mb-6 flex gap-3
        {{ $esMigrante ? 'bg-green-50 border-green-500' : 'bg-amber-50 border-amber-500' }}">
        <svg class="w-6 h-6 shrink-0 mt-0.5 {{ $esMigrante ? 'text-green-500' : 'text-amber-500' }}"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>
        <div>
            @if($esMigrante)
                <p class="font-bold text-green-800 text-sm">Contraseña generada — entréguela en persona</p>
                <p class="text-green-700 text-xs mt-0.5 leading-relaxed">
                    Esta contraseña se muestra <strong>una sola vez</strong>.
                    Entréguela verbalmente o por escrito al migrante.
                    Casa Monarca <strong>no la almacena</strong> en texto claro. Si se pierde, el administrador
                    debe revocar la cuenta y aprobarla nuevamente.
                </p>
            @else
                <p class="font-bold text-amber-800 text-sm">Llave privada — se muestra una sola vez</p>
                <p class="text-amber-700 text-xs mt-0.5 leading-relaxed">
                    Entregue esta llave al coordinador <strong>en persona o por canal cifrado</strong>.
                    Casa Monarca <strong>no almacena</strong> la llave privada. Si se pierde, deberá
                    revocar el certificado y generar uno nuevo.
                </p>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

        {{-- Encabezado --}}
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0
                {{ $esMigrante ? 'bg-green-100' : 'bg-indigo-100' }}">
                @if($esMigrante)
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                @else
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                @endif
            </div>
            <div>
                <h2 class="text-base font-semibold text-gray-800">
                    {{ $esMigrante ? 'Migrante aprobado' : 'Coordinador aprobado' }}:
                    <span class="{{ $esMigrante ? 'text-green-700' : 'text-indigo-700' }}">{{ $userName }}</span>
                </h2>
                <p class="text-xs text-gray-500">
                    {{ $esMigrante
                        ? 'Acceso por contraseña habilitado · Portal migrante'
                        : 'Certificado RSA-2048 generado · Nivel 2 · CRU' }}
                </p>
            </div>
        </div>

        {{-- Credencial --}}
        @if($esMigrante)
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                Contraseña de acceso para {{ $userName }}
            </label>
            <div class="relative">
                <div id="password-display"
                     class="w-full font-mono text-2xl font-bold text-center bg-gray-950 text-green-400 rounded-lg p-6 tracking-[0.3em] select-all">
                    {{ $credencial }}
                </div>
                <button onclick="copyKey()"
                        class="absolute top-2 right-2 px-3 py-1.5 bg-gray-700 hover:bg-gray-600 text-white text-xs rounded-lg transition flex items-center gap-1.5">
                    <svg id="copy-icon" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    <span id="copy-label">Copiar</span>
                </button>
            </div>
            <p class="text-xs text-gray-400 mt-3 leading-relaxed">
                El migrante ingresa en <strong>/acceso</strong> con su nombre y esta contraseña.
                Anótela y entréguensela antes de cerrar esta página.
            </p>
        @else
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                Llave privada (PEM) — archivo .pem para {{ $userName }}
            </label>
            <div class="relative">
                <textarea id="password-display" rows="14" readonly
                          class="w-full font-mono text-xs bg-gray-950 text-green-400 rounded-lg p-4 border-0 resize-none focus:ring-2 focus:ring-indigo-500"
                >{{ $credencial }}</textarea>
                <button onclick="copyKey()"
                        class="absolute top-2 right-2 px-3 py-1.5 bg-gray-700 hover:bg-gray-600 text-white text-xs rounded-lg transition flex items-center gap-1.5">
                    <svg id="copy-icon" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    <span id="copy-label">Copiar</span>
                </button>
            </div>
        @endif

        {{-- Acciones --}}
        <div class="mt-5 flex flex-wrap items-center gap-3">
            @if(!$esMigrante)
            <button onclick="downloadKey()"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-full transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Descargar {{ Str::slug($userName) }}.pem
            </button>
            @endif

            <a href="{{ route('admin.users.approvals') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-full transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Confirmar entrega y volver
            </a>
        </div>
    </div>
</div>

<script>
function copyKey() {
    const el = document.getElementById('password-display');
    const text = el.tagName === 'TEXTAREA' ? el.value : el.textContent.trim();
    navigator.clipboard.writeText(text).then(() => {
        document.getElementById('copy-label').textContent = '¡Copiado!';
        setTimeout(() => { document.getElementById('copy-label').textContent = 'Copiar'; }, 2000);
    });
}

function downloadKey() {
    const content = document.getElementById('password-display').value;
    const blob = new Blob([content], { type: 'application/x-pem-file' });
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = '{{ Str::slug($userName) }}.pem';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(a.href);
}
</script>
</x-app-layout>
