<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('¡Registro recibido! Tu cuenta está en proceso de revisión por parte del Administrador de Casa Monarca. Recibirás un correo cuando seas dado de alta.') }}
    </div>

    <div class="flex items-center justify-end mt-4">
        <a href="{{ route('login') }}" class="underline text-sm text-gray-600 hover:text-gray-900">
            {{ __('Volver al inicio de sesión') }}
        </a>
    </div>
</x-guest-layout>