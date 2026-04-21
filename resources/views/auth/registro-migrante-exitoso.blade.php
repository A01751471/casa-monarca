<x-migrante-layout>
    <div class="bg-white rounded-xl shadow-md p-10 text-center max-w-lg mx-auto">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h2 class="text-xl font-bold text-gray-800 mb-2">¡Registro recibido!</h2>
        <p class="text-sm text-gray-500 leading-relaxed mb-2">
            La información de <strong>{{ session('migrante_registrado_nombre', 'el migrante') }}</strong>
            ha sido registrada correctamente.
        </p>
        <p class="text-sm text-gray-500 leading-relaxed mb-6">
            El personal de Casa Monarca revisará el perfil y, una vez aprobado, generará las credenciales
            de acceso seguro. Se le entregará un archivo de llave para ingresar al portal de solicitudes.
        </p>
        <a href="{{ route('tipo-registro') }}"
           class="inline-block px-6 py-2 bg-green-700 text-white text-sm font-semibold rounded-lg hover:bg-green-800 transition">
            Volver al inicio
        </a>
    </div>
</x-migrante-layout>
