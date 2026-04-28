<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            Log de acciones sobre documentos
        </h2>
    </x-slot>

    <div class="max-w-5xl mx-auto px-4 py-8 space-y-6">

        {{-- Info banner --}}
        <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-xl px-5 py-4">
            <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p class="text-xs font-semibold text-amber-800">Registro de auditoría — solo visible para administradores</p>
                <p class="text-xs text-amber-700 mt-0.5">
                    Todas las ediciones y eliminaciones de documentos realizadas por coordinadores con llave PEM quedan registradas aquí de forma permanente.
                </p>
            </div>
        </div>

        {{-- Tabla de log --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-gray-800 text-sm">Acciones registradas</h3>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $logs->total() }} entrada(s) totales</p>
                </div>
            </div>

            @if($logs->isEmpty())
                <div class="px-6 py-14 text-center text-sm text-gray-400">
                    No hay acciones registradas aún.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Coordinador</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Acción</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Caso</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Detalle</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($logs as $log)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-5 py-3 text-xs text-gray-500 whitespace-nowrap">
                                    {{ $log->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold flex items-center justify-center shrink-0">
                                            {{ strtoupper(substr($log->usuario?->name ?? '?', 0, 1)) }}
                                        </div>
                                        <span class="text-xs font-medium text-gray-800">{{ $log->usuario?->name ?? '—' }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3">
                                    @if($log->accion === 'eliminado')
                                        <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 bg-red-100 text-red-700 rounded-full">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Eliminado
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 bg-amber-100 text-amber-700 rounded-full">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Editado
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    @if($log->expediente)
                                        <a href="{{ route('casos.show', $log->expediente->id) }}"
                                           class="font-mono text-xs font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded hover:bg-indigo-100 transition">
                                            {{ $log->expediente->folio ?? 'Sin folio' }}
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-xs text-gray-600 max-w-xs">
                                    @if($log->detalle)
                                        @if($log->accion === 'editado')
                                            <span class="text-gray-400">Antes:</span>
                                            <span class="font-medium">{{ $log->detalle['nombre_anterior'] ?? '—' }}</span>
                                            <span class="text-gray-400 mx-1">→</span>
                                            <span class="font-medium">{{ $log->detalle['nombre_nuevo'] ?? '—' }}</span>
                                        @elseif($log->accion === 'eliminado')
                                            <span class="font-medium">{{ $log->detalle['nombre'] ?? '—' }}</span>
                                            <span class="text-gray-400 ml-1">({{ strtoupper($log->detalle['tipo'] ?? '') }})</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($logs->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $logs->links() }}
                </div>
                @endif
            @endif
        </div>

    </div>
</x-app-layout>
