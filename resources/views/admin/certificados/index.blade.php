<x-app-layout>
<div class="max-w-6xl mx-auto px-4 py-8 space-y-6">

    {{-- Encabezado --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Certificados digitales</h1>
            <p class="text-sm text-gray-500 mt-0.5">Gestión de llaves públicas asociadas a colaboradores</p>
        </div>
        <a href="{{ route('dashboard') }}"
           class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver al panel
        </a>
    </div>

    {{-- Mensajes flash --}}
    @if(session('status'))
        <div class="flex items-center gap-2 bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl px-4 py-3">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('status') }}
        </div>
    @endif
    @if(session('error'))
        <div class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Stats clicables --}}
    <div class="grid grid-cols-3 gap-4">
        <a href="{{ route('admin.certificados.index', ['status' => 'activo']) }}"
           class="bg-white rounded-2xl border shadow-sm p-5 hover:border-green-300 transition group
                  {{ request('status') === 'activo' ? 'border-green-400 ring-1 ring-green-300' : 'border-gray-200' }}">
            <p class="text-3xl font-bold text-green-600">{{ $stats['activos'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Activos</p>
        </a>
        <a href="{{ route('admin.certificados.index', ['status' => 'revocado']) }}"
           class="bg-white rounded-2xl border shadow-sm p-5 hover:border-amber-300 transition group
                  {{ request('status') === 'revocado' ? 'border-amber-400 ring-1 ring-amber-300' : 'border-gray-200' }}">
            <p class="text-3xl font-bold text-amber-500">{{ $stats['revocados'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Revocados</p>
        </a>
        <a href="{{ route('admin.certificados.index', ['status' => 'vencido']) }}"
           class="bg-white rounded-2xl border shadow-sm p-5 hover:border-red-300 transition group
                  {{ request('status') === 'vencido' ? 'border-red-400 ring-1 ring-red-300' : 'border-gray-200' }}">
            <p class="text-3xl font-bold text-red-500">{{ $stats['vencidos'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Vencidos</p>
        </a>
    </div>

    {{-- Filtros --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm px-5 py-3 flex items-center gap-2 flex-wrap">
        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide mr-1">Filtrar:</span>
        <a href="{{ route('admin.certificados.index') }}"
           class="px-3 py-1 rounded-full text-xs font-medium transition
                  {{ !request('status') ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
            Todos
        </a>
        @foreach(['activo' => 'Activos', 'revocado' => 'Revocados', 'vencido' => 'Vencidos'] as $val => $label)
        <a href="{{ route('admin.certificados.index', ['status' => $val]) }}"
           class="px-3 py-1 rounded-full text-xs font-medium transition
                  {{ request('status') === $val ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    {{-- Tabla --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        @if($certificados->isEmpty())
            <div class="py-16 text-center text-gray-400 text-sm">
                No hay certificados con este filtro.
            </div>
        @else
        <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">
                    <th class="px-5 py-3">Colaborador</th>
                    <th class="px-5 py-3">Área / Rol</th>
                    <th class="px-5 py-3">Algoritmo</th>
                    <th class="px-5 py-3">Fingerprint</th>
                    <th class="px-5 py-3">Emitido</th>
                    <th class="px-5 py-3">Vence</th>
                    <th class="px-5 py-3">Estado</th>
                    <th class="px-5 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($certificados as $cert)
                <tr class="hover:bg-gray-50 transition">

                    <td class="px-5 py-4">
                        @if($cert->user)
                            <p class="font-medium text-gray-800">{{ $cert->user->name }}</p>
                            <p class="text-xs text-gray-400 truncate max-w-[180px]">{{ $cert->user->email }}</p>
                        @else
                            <p class="text-gray-400 italic text-xs">Usuario eliminado</p>
                        @endif
                    </td>

                    <td class="px-5 py-4">
                        <p class="text-gray-700">{{ $cert->user?->area?->nombre ?? '—' }}</p>
                        <p class="text-xs text-gray-400">{{ $cert->user?->role?->nombre ?? '—' }}</p>
                    </td>

                    <td class="px-5 py-4">
                        <span class="font-mono text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded-md">
                            {{ $cert->algoritmo }}
                        </span>
                    </td>

                    <td class="px-5 py-4">
                        <span class="font-mono text-xs text-gray-500" title="{{ $cert->fingerprint }}">
                            {{ substr($cert->fingerprint, 0, 8) }}:{{ substr($cert->fingerprint, 8, 8) }}…
                        </span>
                    </td>

                    <td class="px-5 py-4 text-xs whitespace-nowrap">
                        <p class="text-gray-700">{{ $cert->emitido_at->format('d/m/Y') }}</p>
                        @if($cert->emisor)
                            <p class="text-gray-400">por {{ $cert->emisor->name }}</p>
                        @endif
                    </td>

                    <td class="px-5 py-4 text-xs whitespace-nowrap">
                        <p class="{{ $cert->vence_at->isPast() ? 'text-red-500 font-medium' : ($cert->vence_at->diffInDays() < 60 ? 'text-amber-500' : 'text-gray-700') }}">
                            {{ $cert->vence_at->format('d/m/Y') }}
                        </p>
                        @if($cert->vence_at->isFuture())
                            <p class="text-gray-400">{{ $cert->vence_at->diffForHumans() }}</p>
                        @else
                            <p class="text-red-400">Vencido</p>
                        @endif
                    </td>

                    <td class="px-5 py-4">
                        @php
                            $badge = match($cert->status) {
                                'activo'   => ['bg-green-100 text-green-700', 'bg-green-500'],
                                'revocado' => ['bg-amber-100 text-amber-700', 'bg-amber-400'],
                                default    => ['bg-red-100 text-red-600', 'bg-red-400'],
                            };
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $badge[0] }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $badge[1] }}"></span>
                            {{ ucfirst($cert->status) }}
                        </span>
                        @if($cert->status === 'revocado' && $cert->revocado_at)
                            <p class="text-xs text-gray-400 mt-0.5">{{ $cert->revocado_at->format('d/m/Y') }}</p>
                        @endif
                    </td>

                    <td class="px-5 py-4">
                        <form method="POST" action="{{ route('admin.certificados.destroy', $cert) }}"
                              onsubmit="return confirm('¿Eliminar este certificado de forma permanente? Esta acción no se puede deshacer.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 border border-red-200 rounded-full hover:bg-red-100 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0H7m2-3h6a1 1 0 011 1v1H8V5a1 1 0 011-1z"/>
                                </svg>
                                Eliminar
                            </button>
                        </form>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
        </div>

        @if($certificados->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $certificados->links() }}
        </div>
        @endif
        @endif
    </div>

    {{-- Nota de seguridad --}}
    <div class="flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-xl px-5 py-4">
        <svg class="w-4 h-4 text-blue-400 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                  d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                  clip-rule="evenodd"/>
        </svg>
        <p class="text-xs text-blue-700 leading-relaxed">
            <strong>Nota de seguridad:</strong>
            Esta vista muestra únicamente la llave pública (fingerprint) de cada certificado.
            Las llaves privadas <strong>nunca se almacenan</strong> en el sistema.
            Un certificado vencido no puede reactivarse — debe generarse uno nuevo aprobando nuevamente al colaborador.
        </p>
    </div>

</div>
</x-app-layout>
