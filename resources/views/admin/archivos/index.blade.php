<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">Archivos de migrantes</h2>
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 py-8 space-y-6">

        {{-- Header --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm px-6 py-5">
            <p class="text-sm text-gray-500">
                Documentos de identidad subidos por los migrantes. Haz clic en un perfil para ver sus archivos y verificar su integridad.
            </p>
        </div>

        @if($migrantes->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm px-6 py-16 text-center text-sm text-gray-400">
            No hay migrantes registrados con documentos aún.
        </div>
        @else
        {{-- Grid de migrantes --}}
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px;">
            @foreach($migrantes as $m)
            @php
                $sellados   = $m->docs_sellados ?? 0;
                $total      = $m->total_docs ?? 0;
                $todoSellado = $total > 0 && $sellados === $total;
                $parcial     = $total > 0 && $sellados > 0 && $sellados < $total;
            @endphp
            <a href="{{ route('admin.archivos.show', $m->id) }}"
               class="block bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all duration-150 text-decoration-none"
               style="text-decoration:none;">
                {{-- Top color bar --}}
                <div style="height:4px;background:{{ $todoSellado ? '#10b981' : ($parcial ? '#f59e0b' : '#e5e7eb') }};"></div>

                <div class="p-5 flex flex-col gap-3">
                    {{-- Avatar --}}
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-700 font-bold text-base flex items-center justify-center shrink-0">
                            {{ strtoupper(substr($m->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ $m->name }}</p>
                            <p class="text-xs text-gray-400 truncate">
                                {{ $m->migrantePerfil?->pais_origen ?? '—' }}
                            </p>
                        </div>
                    </div>

                    {{-- Doc count + seal status --}}
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-gray-500">
                            <strong class="text-gray-800">{{ $total }}</strong> doc{{ $total !== 1 ? 's' : '' }}
                        </span>
                        @if($total === 0)
                        <span class="text-gray-400">Sin archivos</span>
                        @elseif($todoSellado)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-green-100 text-green-700 rounded-full font-semibold">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4"/></svg>
                            Sellados
                        </span>
                        @elseif($parcial)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-amber-100 text-amber-700 rounded-full font-semibold">
                            {{ $sellados }}/{{ $total }}
                        </span>
                        @else
                        <span class="px-2 py-0.5 bg-gray-100 text-gray-500 rounded-full font-semibold">Sin sello</span>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @endif

    </div>
</x-app-layout>
