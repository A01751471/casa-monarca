<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            Mi área
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto px-4 py-8 space-y-6">

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

        {{-- Estado actual --}}
        @if($user->area)
            {{-- Ya tiene área asignada --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16M3 21h18M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-green-600 uppercase tracking-wide">Área asignada</p>
                        <p class="text-xl font-bold text-gray-800 mt-0.5">{{ $user->area->nombre }}</p>
                        <p class="text-sm text-gray-500 mt-0.5">{{ $user->role?->name }}</p>
                    </div>
                </div>
            </div>

            {{-- Solicitar cambio de área --}}
            @if(!$solicitudPendiente)
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800 text-sm">Solicitar cambio de área</h3>
                    <p class="text-xs text-gray-400 mt-0.5">El coordinador de destino deberá aprobar tu traslado.</p>
                </div>
                <div class="p-6">
                    <form action="{{ route('mi-area.solicitar') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Área de destino</label>
                            <select name="area_id" required
                                    class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                                <option value="">— Selecciona un área —</option>
                                @foreach($areas as $area)
                                    @if($area->id !== $user->area_id)
                                    <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Motivo (opcional)</label>
                            <textarea name="nota" rows="3" maxlength="500"
                                      placeholder="¿Por qué deseas cambiar de área?"
                                      class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none resize-none"></textarea>
                        </div>
                        <button type="submit"
                                class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm rounded-xl transition">
                            Solicitar traslado
                        </button>
                    </form>
                </div>
            </div>
            @endif

        @else
            {{-- Sin área: mostrar CTA prominente --}}
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-amber-800 text-sm">No estás asignado a ningún área</p>
                    <p class="text-xs text-amber-700 mt-1 leading-relaxed">
                        Para ver solicitudes, ofrecerte para casos y trabajar en el sistema debes pertenecer a un área.
                        Solicita unirte a la que corresponda a tu rol en Casa Monarca.
                    </p>
                </div>
            </div>
        @endif

        {{-- Estado de solicitud pendiente --}}
        @if($solicitudPendiente)
        <div class="bg-white rounded-2xl border border-amber-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-amber-100 bg-amber-50">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-amber-800 text-sm">Solicitud en revisión</h3>
                    <span class="text-xs px-2.5 py-1 bg-amber-100 text-amber-700 rounded-full font-semibold">Pendiente</span>
                </div>
            </div>
            <div class="p-6 space-y-3">
                <div class="flex items-center gap-3">
                    <p class="text-xs text-gray-500 w-24 shrink-0">Área solicitada</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $solicitudPendiente->area?->nombre }}</p>
                </div>
                @if($solicitudPendiente->nota)
                <div class="flex items-start gap-3">
                    <p class="text-xs text-gray-500 w-24 shrink-0 pt-0.5">Nota</p>
                    <p class="text-sm text-gray-600">{{ $solicitudPendiente->nota }}</p>
                </div>
                @endif
                <div class="flex items-center gap-3">
                    <p class="text-xs text-gray-500 w-24 shrink-0">Enviada</p>
                    <p class="text-sm text-gray-600">{{ $solicitudPendiente->created_at->format('d/m/Y H:i') }}</p>
                </div>

                <form action="{{ route('mi-area.cancelar') }}" method="POST" class="pt-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            onclick="return confirm('¿Cancelar tu solicitud de área?')"
                            class="text-xs text-red-500 hover:text-red-700 hover:underline transition">
                        Cancelar solicitud
                    </button>
                </form>
            </div>
        </div>
        @endif

        {{-- Formulario para pedir área (si no tiene y no tiene solicitud pendiente) --}}
        @if(!$user->area && !$solicitudPendiente)
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800 text-sm">Solicitar unirme a un área</h3>
                <p class="text-xs text-gray-400 mt-0.5">El coordinador del área recibirá tu solicitud y decidirá si incorporarte.</p>
            </div>
            <div class="p-6">
                <form action="{{ route('mi-area.solicitar') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                            Área a la que deseas unirte <span class="text-red-500">*</span>
                        </label>
                        <select name="area_id" required
                                class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                            <option value="">— Selecciona un área —</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                            ¿Por qué deseas unirte? (opcional)
                        </label>
                        <textarea name="nota" rows="3" maxlength="500"
                                  placeholder="Experiencia relevante, motivación, disponibilidad..."
                                  class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none resize-none"></textarea>
                    </div>
                    <button type="submit"
                            class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm rounded-xl transition shadow-sm">
                        Enviar solicitud
                    </button>
                </form>
            </div>
        </div>
        @endif

    </div>
</x-app-layout>
