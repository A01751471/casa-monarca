<x-migrante-portal-layout>

<div class="space-y-5">

    {{-- Encabezado --}}
    <div>
        <a href="{{ route('migrante.solicitudes.index') }}"
           class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver a mis solicitudes
        </a>

        <div style="background:var(--paper);border:1px solid var(--cream-200);
                    border-radius:var(--r-lg);padding:20px 24px;">
            <div class="flex items-start justify-between gap-4 flex-wrap">
                <div>
                    <div class="flex items-center gap-2 mb-2 flex-wrap">
                        <span style="font-family:var(--font-display);font-weight:800;font-size:12px;
                                     color:var(--brand-orange-deep);background:var(--brand-orange-soft);
                                     border:1px solid var(--brand-orange-line);padding:3px 10px;border-radius:999px;">
                            {{ $expediente->folio ?? 'Sin folio' }}
                        </span>
                        @if($expediente->status === 'en_proceso')
                        <span class="px-2.5 py-0.5 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">En proceso</span>
                        @elseif($expediente->status === 'terminado')
                        <span class="px-2.5 py-0.5 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Resuelto</span>
                        @endif
                    </div>
                    <h1 style="font-family:var(--font-display);font-weight:800;font-size:1.3rem;color:var(--ink-900);">
                        Documentos del caso
                    </h1>
                    <p style="font-size:13px;color:var(--ink-500);margin-top:3px;">
                        Área {{ $expediente->area?->nombre ?? '—' }}
                        · {{ $documentosVisibles->count() }} documento(s) disponible(s)
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Documentos en revisión --}}
    @if($enRevision > 0)
    <div style="display:flex;align-items:center;gap:12px;background:#fffbeb;
                border:1px solid #fde68a;border-radius:var(--r-md);padding:12px 16px;">
        <svg style="width:18px;height:18px;color:#d97706;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p style="font-size:13px;color:#92400e;">
            <strong>{{ $enRevision }} documento{{ $enRevision > 1 ? 's' : '' }} en preparación</strong>
            — el equipo los está revisando. Estarán disponibles una vez aprobados por el coordinador.
        </p>
    </div>
    @endif

    {{-- Catálogo de documentos --}}
    @if($documentosVisibles->isEmpty())
    <div style="background:var(--paper);border:2px dashed var(--cream-300);
                border-radius:var(--r-lg);padding:48px 24px;text-align:center;">
        <svg style="width:40px;height:40px;color:var(--cream-400);margin:0 auto 12px;"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <p style="font-size:14px;color:var(--ink-500);margin-bottom:4px;">
            Aún no hay documentos disponibles en tu caso.
        </p>
        <p style="font-size:13px;color:var(--ink-400);">
            El equipo los irá agregando conforme avance tu atención.
        </p>
    </div>
    @else
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px;">
        @foreach($documentosVisibles as $doc)
        @php
            $ext     = strtolower($doc->tipo);
            $isImage = in_array($ext, ['jpg','jpeg','png']);
            $isPdf   = $ext === 'pdf';
            $iconColor = $isImage ? 'var(--brand-orange-deep)' : ($isPdf ? '#e53e3e' : '#4c6ef5');
        @endphp
        <div style="background:var(--paper);border:1px solid var(--cream-200);
                    border-radius:var(--r-lg);box-shadow:var(--shadow-sm);
                    overflow:hidden;display:flex;flex-direction:column;">

            {{-- Barra verde de aprobación --}}
            <div style="height:3px;background:#10b981;"></div>

            {{-- Zona del ícono --}}
            <div style="background:var(--cream-50);border-bottom:1px solid var(--cream-100);
                        padding:24px 16px;display:flex;flex-direction:column;
                        align-items:center;gap:10px;">
                <div style="width:52px;height:52px;border-radius:var(--r-md);
                            background:var(--paper);border:1px solid var(--cream-200);
                            display:flex;align-items:center;justify-content:center;
                            box-shadow:0 1px 4px rgba(0,0,0,.06);">
                    <svg style="width:26px;height:26px;color:{{ $iconColor }};"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                              d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <span style="font-family:var(--font-display);font-weight:800;font-size:10px;
                             letter-spacing:0.12em;text-transform:uppercase;
                             color:{{ $isImage ? 'var(--brand-orange-deep)' : '#c53030' }};
                             background:{{ $isImage ? 'var(--brand-orange-soft)' : '#fff5f5' }};
                             border:1px solid {{ $isImage ? 'var(--brand-orange-line)' : '#fed7d7' }};
                             padding:3px 9px;border-radius:999px;">
                    .{{ strtoupper($ext) }}
                </span>

                {{-- Badge aprobado --}}
                <span style="display:inline-flex;align-items:center;gap:4px;
                             padding:3px 10px;border-radius:999px;font-size:10px;font-weight:700;
                             background:#d1fae5;border:1px solid #6ee7b7;color:#065f46;">
                    <svg style="width:10px;height:10px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Aprobado
                </span>
            </div>

            {{-- Info --}}
            <div style="padding:12px 14px;flex:1;">
                <p style="font-size:13px;font-weight:700;color:var(--ink-900);
                           line-height:1.3;margin-bottom:5px;
                           display:-webkit-box;-webkit-line-clamp:2;
                           -webkit-box-orient:vertical;overflow:hidden;">
                    {{ $doc->nombre }}
                </p>
                <p style="font-size:11px;color:var(--ink-400);">
                    Por {{ $doc->autor?->name ?? 'Casa Monarca' }}
                    · {{ $doc->created_at->format('d/m/Y') }}
                </p>
            </div>

            {{-- Descarga --}}
            <div style="padding:10px 14px;border-top:1px solid var(--cream-100);">
                <a href="{{ route('documentos.download', $doc->id) }}"
                   style="display:flex;align-items:center;justify-content:center;gap:6px;
                          width:100%;padding:9px 12px;border-radius:var(--r-sm);font-size:12px;font-weight:700;
                          background:var(--ink-900);color:var(--cream-50);text-decoration:none;
                          transition:opacity .15s;font-family:var(--font-display);"
                   onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
                    <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Descargar
                </a>
            </div>

        </div>
        @endforeach
    </div>
    @endif

    {{-- Nota informativa --}}
    <div style="display:flex;align-items:flex-start;gap:10px;
                background:var(--cream-50);border:1px solid var(--cream-200);
                border-radius:var(--r-md);padding:12px 16px;">
        <svg style="width:15px;height:15px;color:var(--ink-400);flex-shrink:0;margin-top:1px;"
             fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                  d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                  clip-rule="evenodd"/>
        </svg>
        <p style="font-size:12px;color:var(--ink-500);line-height:1.6;">
            Estos documentos fueron generados por el equipo de Casa Monarca para tu caso
            y revisados por un coordinador antes de estar disponibles.
            Si tienes alguna duda sobre su contenido, por favor contáctanos directamente.
        </p>
    </div>

</div>

</x-migrante-portal-layout>
