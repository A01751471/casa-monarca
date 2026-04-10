<x-migrante-layout>

<div x-data="migranteForm()" x-cloak>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">

        {{-- ── Barra de progreso ─────────────────────────────── --}}
        <div class="px-8 pt-5 pb-4 border-b border-gray-100">
            <div class="flex justify-end mb-2">
                <span class="text-xs text-gray-400">
                    <span x-text="t('step')"></span>
                    <span x-text="paso" class="font-semibold text-gray-600"></span>
                    <span x-text="t('of3')"></span> —
                    <span class="font-semibold text-gray-600" x-show="paso === 1" x-text="t('stepName1')"></span>
                    <span class="font-semibold text-gray-600" x-show="paso === 2" x-text="t('stepName2')"></span>
                    <span class="font-semibold text-gray-600" x-show="paso === 3" x-text="t('stepName3')"></span>
                </span>
            </div>
            <div class="flex gap-1.5">
                <div class="flex-1 h-1 rounded-full transition-colors duration-300"
                     :class="paso >= 1 ? 'bg-green-600' : 'bg-gray-200'"></div>
                <div class="flex-1 h-1 rounded-full transition-colors duration-300"
                     :class="paso >= 2 ? 'bg-green-600' : 'bg-gray-200'"></div>
                <div class="flex-1 h-1 rounded-full transition-colors duration-300"
                     :class="paso >= 3 ? 'bg-green-600' : 'bg-gray-200'"></div>
            </div>
        </div>

        <form method="POST" action="{{ route('register.migrante.store') }}">
        @csrf

        {{-- ══════════════════════════════════════════════
             PASO 1: Datos Personales
        ═══════════════════════════════════════════════ --}}
        <div x-show="paso === 1" class="px-8 py-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">

                {{-- 1. Fecha de atención --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <span x-text="'1. ' + t('f_fecha_atencion')"></span>
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="fecha_atencion" x-model="datos.fecha_atencion"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500"
                           required>
                    @error('fecha_atencion') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- 2. Nombre --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <span x-text="'2. ' + t('f_nombre')"></span>
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nombre" x-model="datos.nombre"
                           :placeholder="t('ph_nombre')"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500"
                           required>
                    @error('nombre') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- 3. Primer apellido --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <span x-text="'3. ' + t('f_primer_apellido')"></span>
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="primer_apellido" x-model="datos.primer_apellido"
                           :placeholder="t('f_primer_apellido')"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500"
                           required>
                    @error('primer_apellido') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- 4. Segundo apellido --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <span x-text="'4. ' + t('f_segundo_apellido')"></span>
                    </label>
                    <input type="text" name="segundo_apellido" x-model="datos.segundo_apellido"
                           :placeholder="t('ph_segundo_apellido')"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500">
                    @error('segundo_apellido') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- 5. Teléfono --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <span x-text="'5. ' + t('f_telefono')"></span>
                    </label>
                    <input type="tel" name="telefono" x-model="datos.telefono"
                           placeholder="+52 (000) 000-0000"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500">
                    @error('telefono') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- 6. Género --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <span x-text="'6. ' + t('f_genero')"></span>
                        <span class="text-red-500">*</span>
                    </label>
                    <select name="genero" x-model="datos.genero"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500 bg-white"
                            required>
                        <option value="" disabled selected x-text="t('select_option')"></option>
                        <option value="Hombre"     x-text="t('g_hombre')"></option>
                        <option value="Mujer"      x-text="t('g_mujer')"></option>
                        <option value="No binario" x-text="t('g_nb')"></option>
                        <option value="Prefiero no decir" x-text="t('g_nodecir')"></option>
                    </select>
                    @error('genero') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- 7. País de origen --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <span x-text="'7. ' + t('f_pais_origen')"></span>
                        <span class="text-red-500">*</span>
                    </label>
                    <select name="pais_origen" x-model="datos.pais_origen"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500 bg-white"
                            required>
                        <option value="" disabled selected x-text="t('select_country')"></option>
                        @foreach([
                            'México','Guatemala','Honduras','El Salvador','Nicaragua',
                            'Costa Rica','Panamá','Colombia','Venezuela','Ecuador',
                            'Perú','Bolivia','Brasil','Argentina','Chile',
                            'Paraguay','Uruguay','Cuba','Haití','República Dominicana',
                            'Jamaica','Trinidad y Tobago','Estados Unidos','Canadá','Otro',
                        ] as $pais)
                            <option value="{{ $pais }}">{{ $pais }}</option>
                        @endforeach
                    </select>
                    @error('pais_origen') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- 8. Departamento / Estado --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <span x-text="'8. ' + t('f_depto')"></span>
                    </label>
                    <input type="text" name="departamento_estado" x-model="datos.departamento_estado"
                           :placeholder="t('ph_depto')"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500">
                    @error('departamento_estado') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- 9. Estado civil --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <span x-text="'9. ' + t('f_estado_civil')"></span>
                        <span class="text-red-500">*</span>
                    </label>
                    <select name="estado_civil" x-model="datos.estado_civil"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500 bg-white"
                            required>
                        <option value="" disabled selected x-text="t('select_option')"></option>
                        <option value="Soltero/a"      x-text="t('ec_soltero')"></option>
                        <option value="Casado/a"       x-text="t('ec_casado')"></option>
                        <option value="Unión libre"    x-text="t('ec_union')"></option>
                        <option value="Divorciado/a"   x-text="t('ec_divorciado')"></option>
                        <option value="Viudo/a"        x-text="t('ec_viudo')"></option>
                        <option value="Separado/a"     x-text="t('ec_separado')"></option>
                    </select>
                    @error('estado_civil') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- 10. Fecha de nacimiento --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <span x-text="'10. ' + t('f_fecha_nacimiento')"></span>
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="fecha_nacimiento" x-model="datos.fecha_nacimiento"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500"
                           required>
                    @error('fecha_nacimiento') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- 11. Edad (rango) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <span x-text="'11. ' + t('f_edad')"></span>
                        <span class="text-red-500">*</span>
                    </label>
                    <select name="rango_edad" x-model="datos.rango_edad"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500 bg-white"
                            required>
                        <option value="" disabled selected x-text="t('select_range')"></option>
                        <option value="0-11"  x-text="t('edad_nino')"></option>
                        <option value="12-17" x-text="t('edad_adolescente')"></option>
                        <option value="18-59" x-text="t('edad_adulto')"></option>
                        <option value="60+"   x-text="t('edad_mayor')"></option>
                    </select>
                    @error('rango_edad') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

            </div>

            {{-- 12. Grupo de población --}}
            <div class="mt-5">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    <span x-text="'12. ' + t('f_grupo_poblacion')"></span>
                    <span class="text-red-500">*</span>
                </label>
                <div class="flex flex-wrap gap-x-6 gap-y-2">
                    <template x-for="(grupo, i) in gruposPoblacion()" :key="i">
                        <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer whitespace-nowrap">
                            <input type="radio" name="grupo_poblacion" :value="grupo.val"
                                   x-model="datos.grupo_poblacion"
                                   class="text-green-600 focus:ring-green-500">
                            <span x-text="grupo.label"></span>
                        </label>
                    </template>
                </div>
                @error('grupo_poblacion') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- Aviso de privacidad --}}
            <div class="mt-6 flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-lg p-4">
                <svg class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                          d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                          clip-rule="evenodd"/>
                </svg>
                <p class="text-xs text-amber-700 leading-relaxed" x-text="t('privacy_notice')"></p>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════
             PASO 2: Situación Migratoria
        ═══════════════════════════════════════════════ --}}
        <div x-show="paso === 2" class="px-8 py-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" x-text="'1. ' + t('f_motivo_salida')"></label>
                    <select name="motivo_salida" x-model="datos.motivo_salida"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-green-500 bg-white">
                        <option value="" x-text="t('select_option')"></option>
                        <option value="Violencia"             x-text="t('ms_violencia')"></option>
                        <option value="Persecución"           x-text="t('ms_persecucion')"></option>
                        <option value="Económico"             x-text="t('ms_economico')"></option>
                        <option value="Reunificación familiar" x-text="t('ms_reunificacion')"></option>
                        <option value="Desastre natural"      x-text="t('ms_desastre')"></option>
                        <option value="Otro"                  x-text="t('otro')"></option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" x-text="'2. ' + t('f_destino')"></label>
                    <input type="text" name="destino_final" x-model="datos.destino_final"
                           :placeholder="t('ph_destino')"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" x-text="'3. ' + t('f_acompanantes')"></label>
                    <input type="number" name="num_acompanantes" x-model="datos.num_acompanantes"
                           min="0"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" x-text="'4. ' + t('f_documentacion')"></label>
                    <select name="documentacion" x-model="datos.documentacion"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-green-500 bg-white">
                        <option value="" x-text="t('select_option')"></option>
                        <option value="Pasaporte vigente"  x-text="t('doc_pasaporte_vigente')"></option>
                        <option value="Pasaporte vencido"  x-text="t('doc_pasaporte_vencido')"></option>
                        <option value="Acta de nacimiento" x-text="t('doc_acta')"></option>
                        <option value="DPI / Cédula"       x-text="t('doc_dpi')"></option>
                        <option value="Sin documentos"     x-text="t('doc_sin')"></option>
                        <option value="Otro"               x-text="t('otro')"></option>
                    </select>
                </div>

            </div>

            <div class="mt-5">
                <label class="block text-sm font-medium text-gray-700 mb-1" x-text="'5. ' + t('f_integrantes')"></label>
                <textarea name="integrantes_grupo" x-model="datos.integrantes_grupo" rows="3"
                          :placeholder="t('ph_integrantes')"
                          class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-green-500 resize-none"></textarea>
            </div>

            <div class="mt-5">
                <label class="block text-sm font-medium text-gray-700 mb-1" x-text="'6. ' + t('f_necesidades')"></label>
                <textarea name="necesidades_especiales" x-model="datos.necesidades_especiales" rows="3"
                          :placeholder="t('ph_necesidades')"
                          class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-green-500 resize-none"></textarea>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════
             PASO 3: Confirmación
        ═══════════════════════════════════════════════ --}}
        <div x-show="paso === 3" class="px-8 py-6">
            <p class="text-sm text-gray-500 mb-5" x-text="t('review_intro')"></p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div class="bg-gray-50 rounded-lg p-4 space-y-2.5">
                    <p class="text-xs font-bold uppercase tracking-wide text-gray-400 mb-2" x-text="t('stepName1')"></p>
                    <div class="flex justify-between gap-2">
                        <span class="text-gray-500" x-text="t('f_fecha_atencion')"></span>
                        <span x-text="datos.fecha_atencion" class="font-medium text-gray-800"></span>
                    </div>
                    <div class="flex justify-between gap-2">
                        <span class="text-gray-500" x-text="t('rv_nombre_completo')"></span>
                        <span x-text="datos.nombre + ' ' + datos.primer_apellido" class="font-medium text-gray-800"></span>
                    </div>
                    <div class="flex justify-between gap-2">
                        <span class="text-gray-500" x-text="t('f_genero')"></span>
                        <span x-text="datos.genero" class="font-medium text-gray-800"></span>
                    </div>
                    <div class="flex justify-between gap-2">
                        <span class="text-gray-500" x-text="t('f_pais_origen')"></span>
                        <span x-text="datos.pais_origen" class="font-medium text-gray-800"></span>
                    </div>
                    <div class="flex justify-between gap-2">
                        <span class="text-gray-500" x-text="t('f_estado_civil')"></span>
                        <span x-text="datos.estado_civil" class="font-medium text-gray-800"></span>
                    </div>
                    <div class="flex justify-between gap-2">
                        <span class="text-gray-500" x-text="t('f_grupo_poblacion')"></span>
                        <span x-text="datos.grupo_poblacion" class="font-medium text-gray-800 text-right"></span>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-4 space-y-2.5">
                    <p class="text-xs font-bold uppercase tracking-wide text-gray-400 mb-2" x-text="t('stepName2')"></p>
                    <div class="flex justify-between gap-2">
                        <span class="text-gray-500" x-text="t('f_motivo_salida')"></span>
                        <span x-text="datos.motivo_salida || '—'" class="font-medium text-gray-800"></span>
                    </div>
                    <div class="flex justify-between gap-2">
                        <span class="text-gray-500" x-text="t('f_destino')"></span>
                        <span x-text="datos.destino_final || '—'" class="font-medium text-gray-800"></span>
                    </div>
                    <div class="flex justify-between gap-2">
                        <span class="text-gray-500" x-text="t('f_acompanantes')"></span>
                        <span x-text="datos.num_acompanantes" class="font-medium text-gray-800"></span>
                    </div>
                    <div class="flex justify-between gap-2">
                        <span class="text-gray-500" x-text="t('f_documentacion')"></span>
                        <span x-text="datos.documentacion || '—'" class="font-medium text-gray-800"></span>
                    </div>
                </div>
            </div>

            <div class="mt-5 flex items-start gap-3 bg-green-50 border border-green-200 rounded-lg p-4">
                <svg class="w-4 h-4 text-green-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <p class="text-xs text-green-700 leading-relaxed" x-text="t('consent_notice')"></p>
            </div>
        </div>

        {{-- ── Botones de navegación ──────────────────────────── --}}
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
            <button type="button" x-show="paso > 1" @click="paso--"
                    class="inline-flex items-center gap-1.5 px-5 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-full hover:bg-gray-50 transition shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                <span x-text="t('btn_anterior')"></span>
            </button>
            <span x-show="paso === 1"></span>

            <button type="button" x-show="paso < 3" @click="siguientePaso()"
                    class="inline-flex items-center gap-1.5 px-6 py-2 text-sm font-semibold bg-green-700 text-white rounded-full hover:bg-green-800 transition shadow-sm">
                <span x-text="t('btn_siguiente')"></span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <button type="submit" x-show="paso === 3"
                    class="inline-flex items-center gap-1.5 px-6 py-2 text-sm font-semibold bg-green-700 text-white rounded-full hover:bg-green-800 transition shadow-sm">
                <span x-text="t('btn_enviar')"></span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </button>
        </div>

        </form>
    </div>

</div>

<script>
const i18n = {
    es: {
        step: 'Paso', of3: 'de 3',
        stepName1: 'Datos personales',
        stepName2: 'Situación migratoria',
        stepName3: 'Confirmación',
        // Campos paso 1
        f_fecha_atencion: 'Fecha de atención',
        f_nombre: 'Nombre de pila (sin apellidos)',
        ph_nombre: 'Ej. María',
        f_primer_apellido: 'Primer apellido',
        f_segundo_apellido: 'Segundo apellido',
        ph_segundo_apellido: 'En caso de no tener, escriba X',
        f_telefono: 'Número telefónico de contacto',
        f_genero: 'Género',
        f_pais_origen: 'País de origen',
        f_depto: 'Departamento / Estado',
        ph_depto: 'Ej. Cundinamarca',
        f_estado_civil: 'Estado civil',
        f_fecha_nacimiento: 'Fecha de nacimiento',
        f_edad: 'Edad',
        f_grupo_poblacion: '¿A qué grupo de población pertenece?',
        // Opciones género
        g_hombre: 'Hombre', g_mujer: 'Mujer', g_nb: 'No binario', g_nodecir: 'Prefiero no decir',
        // Opciones estado civil
        ec_soltero: 'Soltero/a', ec_casado: 'Casado/a', ec_union: 'Unión libre',
        ec_divorciado: 'Divorciado/a', ec_viudo: 'Viudo/a', ec_separado: 'Separado/a',
        // Opciones edad
        edad_nino: '0–11 años (Niño/a)', edad_adolescente: '12–17 años (Adolescente)',
        edad_adulto: '18–59 años (Adulto)', edad_mayor: '60+ años (Adulto mayor)',
        // Grupos de población
        gp: ['Adulto (18–59 años)', 'Adulto mayor (+60 años)', 'Niña acompañada',
             'Niño acompañado', 'Adolescente hombre acompañado',
             'Adolescente mujer acompañada', 'NNA No acompañado'],
        // Campos paso 2
        f_motivo_salida: 'Motivo de salida del país',
        f_destino: 'Destino final',
        ph_destino: 'Ej. Estados Unidos, Ciudad de México...',
        f_acompanantes: 'Número de acompañantes',
        f_documentacion: 'Documentación disponible',
        f_integrantes: 'Integrantes del grupo familiar',
        ph_integrantes: 'Ej. Esposa (32 años), 2 hijos menores (5 y 8 años)...',
        f_necesidades: 'Necesidades especiales o de atención prioritaria',
        ph_necesidades: 'Ej. Atención médica, discapacidad, embarazo, trauma psicológico...',
        // Opciones motivo salida
        ms_violencia: 'Violencia / Inseguridad', ms_persecucion: 'Persecución / Amenazas',
        ms_economico: 'Razones económicas', ms_reunificacion: 'Reunificación familiar',
        ms_desastre: 'Desastre natural',
        // Opciones documentación
        doc_pasaporte_vigente: 'Pasaporte vigente', doc_pasaporte_vencido: 'Pasaporte vencido',
        doc_acta: 'Acta de nacimiento', doc_dpi: 'DPI / Cédula de identidad',
        doc_sin: 'Sin documentos',
        // Paso 3 revisión
        review_intro: 'Revise que los datos sean correctos antes de enviar.',
        rv_nombre_completo: 'Nombre completo',
        consent_notice: 'Al enviar, acepta que Casa Monarca almacene sus datos únicamente para brindarle ayuda humanitaria, conforme al Aviso de Privacidad y los Derechos ARCO.',
        // Aviso privacidad
        privacy_notice: 'Aviso de privacidad: Sus datos personales son protegidos conforme a la Ley Federal de Protección de Datos Personales en Posesión de Particulares. Tiene derecho a Acceso, Rectificación, Cancelación y Oposición (Derechos ARCO).',
        // Selects genéricos
        select_option: 'Selecciona una opción',
        select_country: 'Selecciona un país',
        select_range: 'Selecciona rango',
        otro: 'Otro',
        // Botones
        btn_anterior: 'Anterior', btn_siguiente: 'Siguiente', btn_enviar: 'Enviar registro',
        // Validación
        val_required: 'Por favor complete todos los campos obligatorios marcados con *.',
    },
    en: {
        step: 'Step', of3: 'of 3',
        stepName1: 'Personal data',
        stepName2: 'Migration situation',
        stepName3: 'Confirmation',
        f_fecha_atencion: 'Date of care',
        f_nombre: 'First name (no surnames)',
        ph_nombre: 'E.g. Mary',
        f_primer_apellido: 'Last name',
        f_segundo_apellido: 'Second last name',
        ph_segundo_apellido: 'If none, write X',
        f_telefono: 'Contact phone number',
        f_genero: 'Gender',
        f_pais_origen: 'Country of origin',
        f_depto: 'Department / State',
        ph_depto: 'E.g. Cundinamarca',
        f_estado_civil: 'Marital status',
        f_fecha_nacimiento: 'Date of birth',
        f_edad: 'Age',
        f_grupo_poblacion: 'Which population group do you belong to?',
        g_hombre: 'Male', g_mujer: 'Female', g_nb: 'Non-binary', g_nodecir: 'Prefer not to say',
        ec_soltero: 'Single', ec_casado: 'Married', ec_union: 'Common-law partner',
        ec_divorciado: 'Divorced', ec_viudo: 'Widowed', ec_separado: 'Separated',
        edad_nino: '0–11 years (Child)', edad_adolescente: '12–17 years (Teenager)',
        edad_adulto: '18–59 years (Adult)', edad_mayor: '60+ years (Senior)',
        gp: ['Adult (18–59 years)', 'Senior adult (+60 years)', 'Accompanied girl',
             'Accompanied boy', 'Accompanied teenage male',
             'Accompanied teenage female', 'Unaccompanied minor'],
        f_motivo_salida: 'Reason for leaving the country',
        f_destino: 'Final destination',
        ph_destino: 'E.g. United States, Mexico City...',
        f_acompanantes: 'Number of companions',
        f_documentacion: 'Available documentation',
        f_integrantes: 'Family group members',
        ph_integrantes: 'E.g. Wife (32 years), 2 young children (5 and 8 years)...',
        f_necesidades: 'Special or priority care needs',
        ph_necesidades: 'E.g. Medical care, disability, pregnancy, psychological trauma...',
        ms_violencia: 'Violence / Insecurity', ms_persecucion: 'Persecution / Threats',
        ms_economico: 'Economic reasons', ms_reunificacion: 'Family reunification',
        ms_desastre: 'Natural disaster',
        doc_pasaporte_vigente: 'Valid passport', doc_pasaporte_vencido: 'Expired passport',
        doc_acta: 'Birth certificate', doc_dpi: 'National ID card',
        doc_sin: 'No documents',
        review_intro: 'Please review your information before submitting.',
        rv_nombre_completo: 'Full name',
        consent_notice: 'By submitting, you agree that Casa Monarca stores your data solely to provide humanitarian assistance, in accordance with the Privacy Notice and ARCO Rights.',
        privacy_notice: 'Privacy notice: Your personal data is protected under Mexican Federal Law on Personal Data Protection. You have the right to Access, Rectification, Cancellation, and Opposition (ARCO Rights).',
        select_option: 'Select an option',
        select_country: 'Select a country',
        select_range: 'Select range',
        otro: 'Other',
        btn_anterior: 'Back', btn_siguiente: 'Next', btn_enviar: 'Submit registration',
        val_required: 'Please complete all required fields marked with *.',
    },
    fr: {
        step: 'Étape', of3: 'sur 3',
        stepName1: 'Données personnelles',
        stepName2: 'Situation migratoire',
        stepName3: 'Confirmation',
        f_fecha_atencion: 'Date de prise en charge',
        f_nombre: 'Prénom (sans nom de famille)',
        ph_nombre: 'Ex. Marie',
        f_primer_apellido: 'Nom de famille',
        f_segundo_apellido: 'Deuxième nom',
        ph_segundo_apellido: 'Si absent, écrire X',
        f_telefono: 'Numéro de téléphone',
        f_genero: 'Genre',
        f_pais_origen: 'Pays d\'origine',
        f_depto: 'Département / État',
        ph_depto: 'Ex. Cundinamarca',
        f_estado_civil: 'État civil',
        f_fecha_nacimiento: 'Date de naissance',
        f_edad: 'Âge',
        f_grupo_poblacion: 'À quel groupe de population appartenez-vous ?',
        g_hombre: 'Homme', g_mujer: 'Femme', g_nb: 'Non-binaire', g_nodecir: 'Préfère ne pas dire',
        ec_soltero: 'Célibataire', ec_casado: 'Marié(e)', ec_union: 'Union libre',
        ec_divorciado: 'Divorcé(e)', ec_viudo: 'Veuf / Veuve', ec_separado: 'Séparé(e)',
        edad_nino: '0–11 ans (Enfant)', edad_adolescente: '12–17 ans (Adolescent)',
        edad_adulto: '18–59 ans (Adulte)', edad_mayor: '60+ ans (Senior)',
        gp: ['Adulte (18–59 ans)', 'Senior (+60 ans)', 'Fille accompagnée',
             'Garçon accompagné', 'Adolescent accompagné',
             'Adolescente accompagnée', 'Mineur non accompagné'],
        f_motivo_salida: 'Raison du départ du pays',
        f_destino: 'Destination finale',
        ph_destino: 'Ex. États-Unis, Mexico...',
        f_acompanantes: 'Nombre d\'accompagnants',
        f_documentacion: 'Documents disponibles',
        f_integrantes: 'Membres du groupe familial',
        ph_integrantes: 'Ex. Épouse (32 ans), 2 enfants (5 et 8 ans)...',
        f_necesidades: 'Besoins spéciaux ou prioritaires',
        ph_necesidades: 'Ex. Soins médicaux, handicap, grossesse, traumatisme...',
        ms_violencia: 'Violence / Insécurité', ms_persecucion: 'Persécution / Menaces',
        ms_economico: 'Raisons économiques', ms_reunificacion: 'Réunification familiale',
        ms_desastre: 'Catastrophe naturelle',
        doc_pasaporte_vigente: 'Passeport valide', doc_pasaporte_vencido: 'Passeport expiré',
        doc_acta: 'Acte de naissance', doc_dpi: 'Carte d\'identité nationale',
        doc_sin: 'Sans documents',
        review_intro: 'Vérifiez vos informations avant d\'envoyer.',
        rv_nombre_completo: 'Nom complet',
        consent_notice: 'En soumettant, vous acceptez que Casa Monarca conserve vos données uniquement pour fournir une aide humanitaire, conformément à l\'Avis de confidentialité et aux Droits ARCO.',
        privacy_notice: 'Avis de confidentialité : Vos données personnelles sont protégées conformément à la loi mexicaine sur la protection des données personnelles. Vous avez le droit d\'Accès, de Rectification, d\'Annulation et d\'Opposition (Droits ARCO).',
        select_option: 'Sélectionnez une option',
        select_country: 'Sélectionnez un pays',
        select_range: 'Sélectionnez une tranche',
        otro: 'Autre',
        btn_anterior: 'Précédent', btn_siguiente: 'Suivant', btn_enviar: 'Envoyer',
        val_required: 'Veuillez compléter tous les champs obligatoires marqués avec *.',
    },
};

function migranteForm() {
    return {
        paso: 1,
        datos: {
            fecha_atencion: '', nombre: '', primer_apellido: '', segundo_apellido: '',
            telefono: '', genero: '', pais_origen: '', departamento_estado: '',
            estado_civil: '', fecha_nacimiento: '', rango_edad: '', grupo_poblacion: '',
            motivo_salida: '', destino_final: '', num_acompanantes: 0,
            documentacion: '', integrantes_grupo: '', necesidades_especiales: '',
        },
        t(key) {
            const lang = Alpine.store('lang') || 'es';
            return i18n[lang]?.[key] ?? i18n.es[key] ?? key;
        },
        gruposPoblacion() {
            const lang = Alpine.store('lang') || 'es';
            const labels = i18n[lang]?.gp ?? i18n.es.gp;
            // The value stored is always the Spanish canonical value for DB consistency
            const vals = i18n.es.gp;
            return labels.map((label, i) => ({ label, val: vals[i] }));
        },
        siguientePaso() {
            if (this.paso === 1 && !this.validarPaso1()) return;
            this.paso++;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        validarPaso1() {
            const req = ['fecha_atencion','nombre','primer_apellido','genero',
                         'pais_origen','estado_civil','fecha_nacimiento','rango_edad','grupo_poblacion'];
            for (const c of req) {
                if (!this.datos[c]) {
                    alert(this.t('val_required'));
                    return false;
                }
            }
            return true;
        }
    }
}
</script>

</x-migrante-layout>
