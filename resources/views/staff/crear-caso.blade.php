<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">Crear nuevo caso</h2>
    </x-slot>

    <div class="max-w-2xl mx-auto px-4 py-8 space-y-6">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-xs text-gray-400">
            <a href="{{ route('casos.mios') }}" class="hover:text-indigo-400 transition">Casos del área</a>
            <span>/</span>
            <span class="text-gray-300">Nuevo caso</span>
        </div>

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl px-5 py-3 text-sm text-red-700 space-y-1">
            @foreach($errors->all() as $e)
            <p>{{ $e }}</p>
            @endforeach
        </div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <h3 class="font-bold text-gray-800 text-base mb-1">Abrir caso para un migrante</h3>
            <p class="text-sm text-gray-500 mb-6">
                El caso aparecerá en el portal del migrante como si él lo hubiera generado.
                Asigna a un colaborador del área para que lo atienda.
            </p>

            <form method="POST" action="{{ route('casos.crear') }}" class="space-y-5">
                @csrf

                {{-- Migrante --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">
                        Migrante
                    </label>
                    <select name="migrante_id" required
                            class="w-full text-sm border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-400 focus:outline-none bg-white">
                        <option value="">Selecciona un migrante…</option>
                        @foreach($migrantes as $m)
                        @php
                            $p = $m->migrantePerfil;
                            $label = $p
                                ? trim($p->nombre . ' ' . $p->primer_apellido) . ' — ' . $p->pais_origen
                                : $m->name;
                        @endphp
                        <option value="{{ $m->id }}" {{ old('migrante_id') == $m->id ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tipo --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">
                        Tipo de solicitud
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        @foreach($tipos as $t)
                        <label class="flex items-center gap-2 cursor-pointer border border-gray-200 rounded-lg px-3 py-2 hover:border-indigo-300 hover:bg-indigo-50 transition has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50">
                            <input type="radio" name="tipo" value="{{ $t }}"
                                   class="text-indigo-600"
                                   {{ old('tipo') === $t ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700 capitalize">{{ ucfirst($t) }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('tipo')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Descripción --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">
                        Descripción del caso
                    </label>
                    <textarea name="descripcion" rows="4" required maxlength="1000"
                              placeholder="Describe la situación o necesidad del migrante…"
                              class="w-full text-sm border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-400 focus:outline-none resize-none">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Colaborador --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">
                        Asignar a colaborador
                    </label>
                    <select name="colaborador_id" required
                            class="w-full text-sm border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-400 focus:outline-none bg-white">
                        <option value="">Selecciona un colaborador…</option>
                        @foreach($colaboradores as $c)
                        <option value="{{ $c->id }}" {{ old('colaborador_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->name }} — {{ $c->role?->name ?? 'Colaborador' }}
                        </option>
                        @endforeach
                    </select>
                    @error('colaborador_id')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-full transition shadow-sm">
                        Crear caso
                    </button>
                    <a href="{{ route('casos.mios') }}"
                       class="px-4 py-2.5 text-sm text-gray-500 hover:text-gray-700 transition">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
