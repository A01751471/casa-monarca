<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>
        {{-- ¿Cómo participas? --}}
        <div class="mt-4">
            <x-input-label for="tipo_participacion" :value="__('¿Cómo participas en Casa Monarca?')" />
            <select id="tipo_participacion" name="tipo_participacion"
                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                <option value="" disabled {{ old('tipo_participacion') ? '' : 'selected' }}>Selecciona tu perfil</option>
                <option value="interno" {{ old('tipo_participacion') === 'interno' ? 'selected' : '' }}>
                    Personal de Casa Monarca (staff interno)
                </option>
                <option value="externo" {{ old('tipo_participacion') === 'externo' ? 'selected' : '' }}>
                    Agente externo (becario · voluntario · servicio social · recepción)
                </option>
            </select>
            <p class="mt-1 text-xs text-gray-400">El administrador habilitará tu acceso en un plazo de 5 días hábiles.</p>
            <x-input-error :messages="$errors->get('tipo_participacion')" class="mt-2" />
        </div>

        {{-- Rol (solo para personal interno) --}}
        <div id="role_container" class="mt-4" style="display: none;">
            <x-input-label for="role_id" :value="__('Nivel dentro del equipo')" />
            <select id="role_id" name="role_id"
                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="" disabled selected>Selecciona tu nivel</option>
                @foreach($roles->whereIn('id', [2, 3]) as $role)
                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                        @if($role->id === 2) Coordinador de área
                        @elseif($role->id === 3) Operativo
                        @endif
                    </option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-gray-400">Coordinador: CRU · Operativo: CR</p>
            <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
        </div>

        {{-- Área (solo para coordinador u operativo) --}}
        <div id="area_container" class="mt-4" style="display: none;">
            <x-input-label for="area_id" :value="__('Área a la que perteneces')" />
            <select id="area_id" name="area_id"
                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="" selected disabled>Selecciona un área...</option>
                @foreach($areas->whereNotIn('id', [6]) as $area)
                    <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>
                        {{ $area->nombre }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('area_id')" class="mt-2" />
        </div>
        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>
            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tipoSelect    = document.getElementById('tipo_participacion');
        const roleContainer = document.getElementById('role_container');
        const roleSelect    = document.getElementById('role_id');
        const areaContainer = document.getElementById('area_container');
        const areaSelect    = document.getElementById('area_id');

        function aplicarTipo() {
            const tipo = tipoSelect.value;

            if (tipo === 'externo') {
                // Agente externo: ocultar rol y área, rol queda sin valor (el backend fuerza 4)
                roleContainer.style.display = 'none';
                areaContainer.style.display = 'none';
                roleSelect.removeAttribute('required');
                areaSelect.removeAttribute('required');
                roleSelect.value = '';
                areaSelect.value = '';
            } else if (tipo === 'interno') {
                // Personal interno: mostrar selector de nivel
                roleContainer.style.display = 'block';
                roleSelect.setAttribute('required', 'required');
                aplicarRol();
            } else {
                roleContainer.style.display = 'none';
                areaContainer.style.display = 'none';
            }
        }

        function aplicarRol() {
            const rol = roleSelect.value;
            if (rol === '2' || rol === '3') {
                areaContainer.style.display = 'block';
                areaSelect.setAttribute('required', 'required');
            } else {
                areaContainer.style.display = 'none';
                areaSelect.removeAttribute('required');
                areaSelect.value = '';
            }
        }

        tipoSelect.addEventListener('change', aplicarTipo);
        roleSelect.addEventListener('change', aplicarRol);

        // Restaurar estado si hay old() values tras un error de validación
        aplicarTipo();
    });
</script>
