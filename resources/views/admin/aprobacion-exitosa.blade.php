<x-app-layout>
<div class="max-w-2xl mx-auto py-12 px-4">

    {{-- Alerta crítica --}}
    <div class="bg-amber-50 border-l-4 border-amber-500 rounded-xl p-5 mb-6 flex gap-3">
        <svg class="w-6 h-6 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>
        <div>
            <p class="font-bold text-amber-800 text-sm">Esta llave privada se muestra una sola vez</p>
            <p class="text-amber-700 text-xs mt-0.5 leading-relaxed">
                Entregue esta llave al colaborador de forma segura (en persona o canal cifrado).
                Casa Monarca <strong>no almacena</strong> la llave privada. Si se pierde, deberá revocar
                el certificado y generar uno nuevo.
            </p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

        {{-- Encabezado --}}
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div>
                <h2 class="text-base font-semibold text-gray-800">
                    Colaborador aprobado: <span class="text-green-700">{{ $userName }}</span>
                </h2>
                <p class="text-xs text-gray-500">Certificado RSA-2048 generado correctamente</p>
            </div>
        </div>

        {{-- Llave privada --}}
        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
            Llave privada (PEM) — copiar y entregar al colaborador
        </label>
        <div class="relative">
            <textarea id="private-key-field" rows="14" readonly
                      class="w-full font-mono text-xs bg-gray-950 text-green-400 rounded-lg p-4 border-0 resize-none focus:ring-2 focus:ring-green-500"
            >{{ $privateKey }}</textarea>
            <button onclick="copyKey()"
                    class="absolute top-2 right-2 px-3 py-1.5 bg-gray-700 hover:bg-gray-600 text-white text-xs rounded-lg transition flex items-center gap-1.5">
                <svg id="copy-icon" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                <span id="copy-label">Copiar</span>
            </button>
        </div>

        {{-- Confirmar entrega --}}
        <form method="POST" action="{{ route('admin.users.approvals') }}" class="mt-6">
            @csrf
            @method('GET')
            <a href="{{ route('admin.users.approvals') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-700 hover:bg-green-800 text-white text-sm font-semibold rounded-full transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Confirmar entrega y volver a aprobaciones
            </a>
        </form>
    </div>
</div>

<script>
function copyKey() {
    const field = document.getElementById('private-key-field');
    field.select();
    navigator.clipboard.writeText(field.value).then(() => {
        document.getElementById('copy-label').textContent = '¡Copiado!';
        setTimeout(() => { document.getElementById('copy-label').textContent = 'Copiar'; }, 2000);
    });
}
</script>
</x-app-layout>
