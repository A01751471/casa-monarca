<x-app-layout>
<div class="max-w-6xl mx-auto px-4 py-8 space-y-8">

    {{-- ── Banner de bienvenida ──────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 flex flex-col sm:flex-row sm:items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5.121 17.804A9 9 0 1118.88 6.196M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div class="flex-1">
            <h2 class="text-lg font-semibold text-gray-800">Panel — Administrador</h2>
            <p class="text-sm text-gray-500">Gestión global de usuarios, certificados y áreas · Casa Monarca</p>
        </div>
        {{-- Estadísticas compactas --}}
        <div class="flex gap-4 flex-wrap">
            <div class="text-center px-4 py-2 bg-gray-50 rounded-xl border border-gray-100">
                <p class="text-2xl font-bold text-gray-800">{{ $totalActivos }}</p>
                <p class="text-xs text-gray-400 mt-0.5">Colaboradores activos</p>
            </div>
            <div class="text-center px-4 py-2 bg-amber-50 rounded-xl border border-amber-100">
                <p class="text-2xl font-bold text-amber-600">{{ $totalPendientes }}</p>
                <p class="text-xs text-amber-400 mt-0.5">Pendientes de aprobar</p>
            </div>
            <div class="text-center px-4 py-2 bg-green-50 rounded-xl border border-green-100">
                <p class="text-2xl font-bold text-green-600">{{ $totalCerts }}</p>
                <p class="text-xs text-green-400 mt-0.5">Certificados activos</p>
            </div>
        </div>
    </div>

    {{-- ── Accesos rápidos (pills) ───────────────────────────────── --}}
    <div>
        <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-3">Accesos rápidos</p>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.users.approvals') }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full border border-amber-300 bg-amber-50 text-amber-700 text-sm font-medium hover:bg-amber-100 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Aprobar pendientes
                @if($totalPendientes > 0)
                    <span class="ml-1 bg-amber-500 text-white text-xs font-bold rounded-full px-1.5 py-0.5">
                        {{ $totalPendientes }}
                    </span>
                @endif
            </a>
            <a href="{{ route('admin.users.index') }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full border border-gray-300 bg-white text-gray-700 text-sm font-medium hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2h5M12 11a4 4 0 100-8 4 4 0 000 8z"/>
                </svg>
                Gestión de usuarios
            </a>
            <a href="{{ route('areas.index') }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full border border-gray-300 bg-white text-gray-700 text-sm font-medium hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H5m7-10h.01M9 7h6"/>
                </svg>
                Gestión de áreas
            </a>
            <a href="{{ route('admin.diagnostico') }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full border border-gray-300 bg-white text-gray-700 text-sm font-medium hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Diagnóstico
            </a>
        </div>
    </div>

    {{-- ── Tres secciones de gestión ─────────────────────────────── --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

        {{-- Gestión de usuarios --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-800 text-sm">Gestión de usuarios</h3>
            </div>
            <ul class="space-y-2.5 text-sm">
                <li>
                    <a href="{{ route('admin.users.approvals') }}"
                       class="flex items-center gap-2 text-indigo-600 hover:text-indigo-800 transition">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 shrink-0"></span>
                        Aprobar / rechazar accesos
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.users.index') }}"
                       class="flex items-center gap-2 text-indigo-600 hover:text-indigo-800 transition">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 shrink-0"></span>
                        Directorio global de usuarios
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.users.index') }}"
                       class="flex items-center gap-2 text-indigo-600 hover:text-indigo-800 transition">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 shrink-0"></span>
                        Suspender / restaurar acceso
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.users.index') }}"
                       class="flex items-center gap-2 text-indigo-600 hover:text-indigo-800 transition">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 shrink-0"></span>
                        Cambiar rol y área
                    </a>
                </li>
            </ul>
        </div>

        {{-- Certificados digitales --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-800 text-sm">Certificados digitales</h3>
            </div>
            <ul class="space-y-2.5 text-sm">
                <li>
                    <a href="{{ route('admin.certificados.index') }}"
                       class="flex items-center gap-2 text-indigo-600 hover:text-indigo-800 transition">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 shrink-0"></span>
                        Ver certificados activos
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.certificados.index') }}"
                       class="flex items-center gap-2 text-indigo-600 hover:text-indigo-800 transition">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 shrink-0"></span>
                        Revocar certificado
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.certificados.index') }}"
                       class="flex items-center gap-2 text-indigo-600 hover:text-indigo-800 transition">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 shrink-0"></span>
                        Ver autor de firma
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.certificados.index') }}"
                       class="flex items-center gap-2 text-indigo-600 hover:text-indigo-800 transition">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 shrink-0"></span>
                        Historial de certificados
                    </a>
                </li>
            </ul>
            <p class="mt-4 text-xs text-gray-400 italic">
                Las llaves privadas nunca se almacenan en el sistema.
            </p>
        </div>

        {{-- Distribución por áreas --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16M3 21h18M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-800 text-sm">Distribución por áreas</h3>
            </div>
            <ul class="space-y-2">
                @foreach($areas as $area)
                <li class="flex items-center justify-between">
                    <a href="{{ route('admin.areas.show', $area) }}"
                       class="text-sm text-indigo-600 hover:text-indigo-800 transition truncate">
                        {{ $area->nombre }}
                    </a>
                    <span class="ml-2 shrink-0 text-xs font-semibold bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">
                        {{ $area->colaboradores_activos }}
                    </span>
                </li>
                @endforeach
            </ul>
        </div>

    </div>

    {{-- ── Nota de mínimo privilegio ────────────────────────────── --}}
    <div class="flex items-start gap-3 bg-indigo-50 border border-indigo-200 rounded-xl px-5 py-4">
        <svg class="w-4 h-4 text-indigo-400 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                  d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                  clip-rule="evenodd"/>
        </svg>
        <p class="text-xs text-indigo-700 leading-relaxed">
            <strong>Administrador:</strong> Tiene acceso completo a la gestión de usuarios y certificados de todas las áreas.
            Todas las acciones quedan registradas en el log de actividad para auditoría.
        </p>
    </div>

</div>
</x-app-layout>
