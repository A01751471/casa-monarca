<x-migrante-portal-layout>

<div class="max-w-xl space-y-5">

    {{-- Encabezado --}}
    <div>
        <a href="{{ route('migrante.dashboard') }}"
           class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver
        </a>
        <h1 class="text-xl font-bold text-gray-800">Nueva solicitud</h1>
        <p class="text-sm text-gray-500 mt-0.5">
            Indique el área y describa lo que necesita. El personal de Casa Monarca le dará seguimiento.
        </p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 mb-5">
                <ul class="text-sm text-red-700 space-y-1 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('migrante.solicitudes.store') }}" class="space-y-5">
            @csrf

            {{-- Área --}}
            <div>
                <label for="area_id" class="block text-xs font-semibold text-gray-600 mb-1.5">
                    ¿A qué área dirige su solicitud? <span class="text-red-500">*</span>
                </label>
                <select name="area_id" id="area_id" required
                        class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <option value="">— Seleccione un área —</option>
                    @foreach($areas as $area)
                        <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>
                            {{ $area->nombre }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-400 mt-1">
                    Humanitaria (alojamiento/comida), Legal (documentos), PsicoSocial (apoyo emocional), etc.
                </p>
            </div>

            {{-- Tipo --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-2">
                    Tipo de solicitud <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                    @foreach([
                        'documento'   => 'Documento',
                        'proceso'     => 'Proceso/trámite',
                        'apoyo'       => 'Apoyo',
                        'informacion' => 'Información',
                        'otro'        => 'Otro',
                    ] as $val => $label)
                    <label class="relative cursor-pointer">
                        <input type="radio" name="tipo" value="{{ $val }}"
                               {{ old('tipo') === $val ? 'checked' : '' }}
                               class="peer sr-only">
                        <div class="border border-gray-200 rounded-xl px-3 py-2.5 text-center text-xs font-medium
                                    text-gray-600 hover:border-green-400 transition
                                    peer-checked:border-green-600 peer-checked:bg-green-50 peer-checked:text-green-700">
                            {{ $label }}
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Descripción --}}
            <div>
                <label for="descripcion" class="block text-xs font-semibold text-gray-600 mb-1.5">
                    Descripción de lo que necesita <span class="text-red-500">*</span>
                </label>
                <textarea name="descripcion" id="descripcion" rows="4" required maxlength="1000"
                          placeholder="Describa con detalle qué necesita o cuál es su situación..."
                          class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 resize-none"
                >{{ old('descripcion') }}</textarea>
                <p class="text-xs text-gray-400 mt-1">Máximo 1000 caracteres</p>
            </div>

            <button type="submit"
                    class="w-full py-3 bg-green-700 hover:bg-green-800 text-white font-semibold text-sm rounded-xl transition shadow-sm">
                Enviar solicitud
            </button>
        </form>
    </div>

</div>

</x-migrante-portal-layout>
