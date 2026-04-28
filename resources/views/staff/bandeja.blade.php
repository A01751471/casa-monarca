<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            Bandeja — {{ $area->nombre }}
        </h2>
    </x-slot>

    <div class="max-w-5xl mx-auto px-4 py-8 space-y-8">

        @if(session('status'))
            <div class="flex items-center gap-2 bg-green-50 border border-green-200 rounded-xl px-5 py-3 text-sm text-green-700">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('status') }}
            </div>
        @endif
        @if(session('error'))
            <div class="flex items-center gap-2 bg-red-50 border border-red-200 rounded-xl px-5 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        {{-- ── SOLICITUDES PENDIENTES ─────────────────────────────── --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-gray-800 text-sm">Solicitudes pendientes</h3>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $pendientes->count() }} por atender</p>
                </div>
                <span class="text-xs px-3 py-1 bg-amber-100 text-amber-700 rounded-full font-semibold">
                    Requieren acción
                </span>
            </div>

            @if($pendientes->isEmpty())
                <div class="px-6 py-10 text-center text-sm text-gray-400">
                    No hay solicitudes nuevas en este momento.
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($pendientes as $sol)
                    @php
                        $yaPostulado = isset($misPostulaciones[$sol->id]);
                        $postulaciones = $sol->postulaciones;
                    @endphp
                    <div class="px-6 py-5" x-data="{ openPostulaciones: false, openAprobar: false, openRechazar: false }">

                        {{-- Encabezado solicitud --}}
                        <div class="flex flex-col sm:flex-row sm:items-start gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <span class="text-xs font-semibold px-2 py-0.5 bg-amber-100 text-amber-700 rounded-full">
                                        {{ ucfirst($sol->tipo) }}
                                    </span>
                                    <span class="text-xs text-gray-400">{{ $sol->created_at->diffForHumans() }}</span>
                                    @if($postulaciones->isNotEmpty())
                                        <span class="text-xs font-semibold px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded-full cursor-pointer"
                                              @click="openPostulaciones = !openPostulaciones">
                                            {{ $postulaciones->count() }} oferta(s) ▾
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-800 leading-relaxed">{{ $sol->descripcion }}</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    @php
                                        $p = $sol->migrantePerfil;
                                        $nombre = $p ? trim($p->nombre . ' ' . $p->primer_apellido) : ($sol->solicitante?->name ?? '—');
                                    @endphp
                                    Solicitante: <strong class="text-gray-600">{{ $nombre }}</strong>
                                </p>
                            </div>

                            {{-- Acciones según rol --}}
                            <div class="flex flex-wrap items-center gap-2 shrink-0">
                                @if($esCoordinador)
                                    {{-- Coordinador: aprobar y rechazar --}}
                                    <button @click="openAprobar = !openAprobar"
                                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-full transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                                        </svg>
                                        Aprobar caso
                                    </button>
                                    <button @click="openRechazar = !openRechazar"
                                            class="inline-flex items-center gap-1.5 px-3 py-2 border border-red-300 text-red-600 hover:bg-red-50 text-xs font-semibold rounded-full transition">
                                        Rechazar
                                    </button>
                                @else
                                    {{-- Colaborador: postularse o retirar --}}
                                    @if($yaPostulado)
                                        <form action="{{ route('casos.postulacion.retirar', $sol->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1.5 px-3 py-2 border border-gray-300 text-gray-600 hover:bg-gray-50 text-xs font-semibold rounded-full transition">
                                                Retirar oferta
                                            </button>
                                        </form>
                                    @else
                                        <button @click="openAprobar = !openAprobar"
                                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-green-700 hover:bg-green-800 text-white text-xs font-semibold rounded-full transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Ofrecerme
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </div>

                        {{-- Panel de postulaciones (solo coordinador) --}}
                        @if($esCoordinador && $postulaciones->isNotEmpty())
                        <div x-show="openPostulaciones" style="display:none" class="mt-4 bg-indigo-50 rounded-xl p-4 space-y-2">
                            <p class="text-xs font-semibold text-indigo-700 mb-2">Colaboradores que se ofrecieron:</p>
                            @foreach($postulaciones as $post)
                            <div class="flex items-start gap-3">
                                <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold flex items-center justify-center shrink-0">
                                    {{ strtoupper(substr($post->colaborador->name ?? '?', 0, 1)) }}
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-semibold text-gray-800">{{ $post->colaborador?->name ?? '—' }}</p>
                                    @if($post->nota)
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $post->nota }}</p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        {{-- Formulario aprobar caso (coordinador) --}}
                        @if($esCoordinador)
                        <div x-show="openAprobar" style="display:none" class="mt-4 bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <p class="text-xs font-semibold text-gray-700 mb-3">Selecciona el colaborador responsable del caso:</p>
                            <form action="{{ route('casos.aprobar', $sol->id) }}" method="POST" class="flex flex-wrap items-end gap-3">
                                @csrf
                                <div class="flex-1 min-w-[200px]">
                                    <select name="colaborador_id" required
                                            class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                                        <option value="">— Seleccionar colaborador —</option>
                                        @foreach($colaboradoresArea as $col)
                                            @php $enPostulaciones = $postulaciones->contains('user_id', $col->id); @endphp
                                            <option value="{{ $col->id }}" {{ $enPostulaciones ? 'selected' : '' }}>
                                                {{ $col->name }}{{ $enPostulaciones ? ' ✓ se ofreció' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit"
                                        class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-full transition">
                                    Crear caso y asignar
                                </button>
                                <button type="button" @click="openAprobar = false"
                                        class="px-3 py-2 text-xs text-gray-500 hover:text-gray-700 transition">
                                    Cancelar
                                </button>
                            </form>
                        </div>

                        {{-- Formulario rechazar (coordinador) --}}
                        <div x-show="openRechazar" style="display:none" class="mt-4 bg-red-50 border border-red-200 rounded-xl p-4">
                            <form action="{{ route('casos.rechazar', $sol->id) }}" method="POST" class="space-y-3">
                                @csrf
                                <textarea name="motivo" rows="2" maxlength="500"
                                          placeholder="Motivo del rechazo (opcional)..."
                                          class="w-full text-xs border border-red-200 rounded-xl px-3 py-2 focus:ring-1 focus:ring-red-400 focus:outline-none resize-none"></textarea>
                                <div class="flex gap-2">
                                    <button type="submit"
                                            class="px-4 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-full transition">
                                        Confirmar rechazo
                                    </button>
                                    <button type="button" @click="openRechazar = false"
                                            class="px-3 py-1.5 text-xs text-gray-500 hover:text-gray-700 transition">
                                        Cancelar
                                    </button>
                                </div>
                            </form>
                        </div>
                        @else
                        {{-- Formulario postularse (colaborador) --}}
                        @if(!$yaPostulado)
                        <div x-show="openAprobar" style="display:none" class="mt-4 bg-green-50 border border-green-200 rounded-xl p-4">
                            <form action="{{ route('casos.postularse', $sol->id) }}" method="POST" class="space-y-3">
                                @csrf
                                <textarea name="nota" rows="2" maxlength="500"
                                          placeholder="Describe brevemente por qué te ofreces o cómo puedes ayudar (opcional)..."
                                          class="w-full text-xs border border-green-200 rounded-xl px-3 py-2 focus:ring-1 focus:ring-green-500 focus:outline-none resize-none"></textarea>
                                <div class="flex gap-2">
                                    <button type="submit"
                                            class="px-4 py-1.5 bg-green-700 hover:bg-green-800 text-white text-xs font-semibold rounded-full transition">
                                        Confirmar oferta
                                    </button>
                                    <button type="button" @click="openAprobar = false"
                                            class="px-3 py-1.5 text-xs text-gray-500 hover:text-gray-700 transition">
                                        Cancelar
                                    </button>
                                </div>
                            </form>
                        </div>
                        @endif
                        @endif

                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ── CASOS EN PROCESO ──────────────────────────────────── --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800 text-sm">Casos en proceso</h3>
                <p class="text-xs text-gray-400 mt-0.5">
                    {{ $enProceso->count() }} caso(s) activo(s)
                    @if(!$esCoordinador)
                        · solo los tuyos
                    @endif
                </p>
            </div>

            @if($enProceso->isEmpty())
                <div class="px-6 py-10 text-center text-sm text-gray-400">
                    @if($esCoordinador)
                        Sin casos activos en esta área.
                    @else
                        No tienes casos activos asignados.
                    @endif
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($enProceso as $exp)
                    <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center gap-4 hover:bg-gray-50 transition">
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-0.5">
                                <span class="font-mono text-xs font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded">
                                    {{ $exp->folio ?? 'Sin folio' }}
                                </span>
                                <span class="text-xs px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full font-semibold">
                                    En proceso
                                </span>
                            </div>
                            @php
                                $sol  = $exp->solicitudes->first();
                                $p    = $sol?->migrantePerfil;
                                $nm   = $p ? trim($p->nombre . ' ' . $p->primer_apellido) : '—';
                            @endphp
                            <p class="text-sm text-gray-700 mt-1">{{ $nm }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                Responsable: <strong>{{ $exp->colaborador?->name ?? 'Sin asignar' }}</strong>
                                · Abierto {{ $exp->created_at->diffForHumans() }}
                            </p>
                        </div>
                        <a href="{{ route('casos.show', $exp->id) }}"
                           class="shrink-0 inline-flex items-center gap-1.5 px-4 py-2 border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 text-xs font-semibold rounded-full transition">
                            Ver caso
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ── CASOS TERMINADOS ────────────────────────────────────── --}}
        @if($terminados->isNotEmpty())
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800 text-sm">Casos resueltos recientes</h3>
                <p class="text-xs text-gray-400 mt-0.5">Últimos {{ $terminados->count() }}</p>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($terminados as $exp)
                <div class="px-6 py-3 flex items-center gap-4 hover:bg-gray-50 transition">
                    <span class="font-mono text-xs font-bold text-gray-500 bg-gray-50 px-2 py-0.5 rounded w-32 shrink-0">
                        {{ $exp->folio ?? '—' }}
                    </span>
                    @php
                        $sol = $exp->solicitudes->first();
                        $p   = $sol?->migrantePerfil;
                        $nm  = $p ? trim($p->nombre . ' ' . $p->primer_apellido) : '—';
                    @endphp
                    <span class="text-sm text-gray-600 flex-1 min-w-0 truncate">{{ $nm }}</span>
                    <span class="text-xs text-gray-400 shrink-0">{{ $exp->resuelto_at?->format('d/m/Y') ?? $exp->updated_at->format('d/m/Y') }}</span>
                    <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs font-semibold rounded-full shrink-0">Resuelto</span>
                    <a href="{{ route('casos.show', $exp->id) }}"
                       class="text-xs text-indigo-600 hover:underline shrink-0">Ver</a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</x-app-layout>
