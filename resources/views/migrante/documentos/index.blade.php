<x-migrante-portal-layout>

<div class="space-y-6">

    {{-- Encabezado --}}
    <div style="background:var(--paper);border:1px solid var(--cream-200);
                border-radius:var(--r-lg);box-shadow:var(--shadow-sm);"
         class="px-6 py-5">
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-3">
                <div style="width:40px;height:40px;border-radius:var(--r-md);
                            background:var(--brand-orange-soft);border:1px solid var(--brand-orange-line);
                            display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg style="width:20px;height:20px;color:var(--brand-orange-deep);"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="cm-display" style="font-size:1.4rem;">Mis documentos</h2>
                    <p style="font-size:13px;color:var(--ink-500);margin-top:2px;">
                        {{ $documentos->count() }} documento(s) · almacenados de forma segura
                    </p>
                </div>
            </div>
            {{-- Botón para abrir el form de subida --}}
            <button onclick="document.getElementById('form-subir').classList.toggle('hidden')"
                    style="display:inline-flex;align-items:center;gap:8px;padding:10px 20px;
                           border-radius:999px;font-size:13px;font-weight:700;cursor:pointer;
                           background:var(--ink-900);color:var(--cream-50);border:none;
                           font-family:var(--font-display);transition:opacity .15s;"
                    onmouseover="this.style.opacity='.88'" onmouseout="this.style.opacity='1'">
                <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Subir documento
            </button>
        </div>
    </div>

    @if(session('status'))
        <div style="background:var(--brand-orange-soft);border:1px solid var(--brand-orange-line);
                    border-radius:var(--r-sm);padding:10px 16px;font-size:13px;color:var(--ink-700);">
            {{ session('status') }}
        </div>
    @endif

    {{-- ── Modal: confirmar sello de integridad ────────────────── --}}
    @if(session('doc_pendiente_sello'))
    <div style="position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:50;
                display:flex;align-items:center;justify-content:center;padding:16px;">
        <div style="background:var(--paper);border-radius:var(--r-lg);box-shadow:0 20px 60px rgba(0,0,0,.25);
                    max-width:420px;width:100%;padding:32px 28px;text-align:center;">
            <div style="width:56px;height:56px;border-radius:50%;background:#d1fae5;
                        display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                <svg style="width:28px;height:28px;color:#059669;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h3 style="font-family:var(--font-display);font-weight:800;font-size:18px;color:var(--ink-900);margin-bottom:10px;">
                Documento subido
            </h3>
            <p style="font-size:14px;color:var(--ink-600);line-height:1.5;margin-bottom:6px;">
                <strong>{{ session('doc_pendiente_nombre') }}</strong>
            </p>
            <p style="font-size:13px;color:var(--ink-500);line-height:1.6;margin-bottom:24px;">
                Para garantizar la validez de tu documento, el sistema aplicará un <strong>sello digital de integridad</strong> que certifica que el archivo no ha sido modificado desde su entrega.
            </p>
            <form method="POST" action="{{ route('migrante.documentos.sellar', session('doc_pendiente_sello')) }}">
                @csrf
                <button type="submit"
                        style="width:100%;padding:14px 24px;border-radius:999px;font-size:14px;font-weight:700;
                               background:#059669;color:white;border:none;cursor:pointer;
                               font-family:var(--font-display);transition:opacity .15s;"
                        onmouseover="this.style.opacity='.88'" onmouseout="this.style.opacity='1'">
                    Confirmar y sellar documento
                </button>
            </form>
            <a href="{{ route('migrante.documentos.index') }}"
               style="display:block;margin-top:12px;font-size:12px;color:var(--ink-400);text-decoration:none;">
                Omitir por ahora
            </a>
        </div>
    </div>
    @endif

    {{-- Catálogo de documentos --}}
    @if($documentos->isNotEmpty())
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:16px;">
        @foreach($documentos as $doc)
        @php
            $ext = strtolower($doc->tipo);
            $isImage = in_array($ext, ['jpg','jpeg','png']);
            $iconColor = $isImage ? 'var(--brand-orange-deep)' : '#e53e3e';
            $solicitudActiva = $solicitudesActivas[$doc->id] ?? null;
            $sellado = $doc->selladoEsValido();
        @endphp
        <div x-data="{ openRectificar: false, openCancelar: false, showHash: false }"
             style="background:var(--paper);border:1px solid var(--cream-200);
                    border-radius:var(--r-lg);box-shadow:var(--shadow-sm);
                    overflow:hidden;display:flex;flex-direction:column;">

            {{-- Barra superior de integridad --}}
            <div style="height:3px;background:{{ $sellado ? '#10b981' : 'var(--cream-200)' }};"></div>

            {{-- Zona del ícono --}}
            <div style="background:var(--cream-50);border-bottom:1px solid var(--cream-100);
                        padding:24px 20px;display:flex;flex-direction:column;
                        align-items:center;gap:10px;flex-shrink:0;">
                <div style="width:56px;height:56px;border-radius:var(--r-md);
                            background:var(--paper);border:1px solid var(--cream-200);
                            display:flex;align-items:center;justify-content:center;
                            box-shadow:0 1px 4px rgba(0,0,0,.06);">
                    <svg style="width:28px;height:28px;color:{{ $iconColor }};"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                              d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <span style="font-family:var(--font-display);font-weight:800;font-size:11px;
                             letter-spacing:0.12em;text-transform:uppercase;
                             color:{{ $isImage ? 'var(--brand-orange-deep)' : '#c53030' }};
                             background:{{ $isImage ? 'var(--brand-orange-soft)' : '#fff5f5' }};
                             border:1px solid {{ $isImage ? 'var(--brand-orange-line)' : '#fed7d7' }};
                             padding:3px 10px;border-radius:999px;">
                    .{{ strtoupper($ext) }}
                </span>

                {{-- Badge de integridad --}}
                @if($sellado)
                <div style="text-align:center;">
                    <button @click="showHash = !showHash"
                            style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;
                                   border-radius:999px;font-size:10px;font-weight:700;letter-spacing:0.06em;
                                   text-transform:uppercase;background:#d1fae5;border:1px solid #6ee7b7;
                                   color:#065f46;cursor:pointer;transition:background .15s;"
                            onmouseover="this.style.background='#a7f3d0'"
                            onmouseout="this.style.background='#d1fae5'">
                        <svg style="width:11px;height:11px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Sellado y asegurado
                    </button>
                    <div x-show="showHash" style="display:none;margin-top:8px;">
                        <p style="font-size:10px;color:var(--ink-400);margin-bottom:3px;">Código de integridad</p>
                        <code style="font-size:9px;font-family:monospace;color:var(--ink-600);
                                     word-break:break-all;display:block;line-height:1.4;">
                            {{ $doc->hash_sha256 }}
                        </code>
                    </div>
                </div>
                @else
                <span style="font-size:10px;color:var(--ink-400);">Sin sello aplicado</span>
                @endif
            </div>

            {{-- Info --}}
            <div style="padding:14px 16px;flex:1;">
                <p style="font-size:13px;font-weight:700;color:var(--ink-900);
                           line-height:1.3;margin-bottom:6px;
                           display:-webkit-box;-webkit-line-clamp:2;
                           -webkit-box-orient:vertical;overflow:hidden;">
                    {{ $doc->nombre }}
                </p>
                <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
                    <span style="font-family:var(--font-display);font-weight:700;
                                 font-size:9px;letter-spacing:0.1em;text-transform:uppercase;
                                 color:var(--brand-orange-deep);background:var(--brand-orange-soft);
                                 border:1px solid var(--brand-orange-line);
                                 padding:2px 7px;border-radius:999px;">
                        {{ $doc->etiqueta }}
                    </span>
                    <span style="font-size:11px;color:var(--ink-400);">
                        {{ $doc->created_at->format('d/m/Y') }}
                    </span>
                </div>

                {{-- Solicitud activa badge --}}
                @if($solicitudActiva)
                @php
                    $badgeStyle = match($solicitudActiva->status) {
                        'pendiente'            => 'background:#fef3c7;color:#92400e;border-color:#fde68a;',
                        'en_proceso'           => 'background:#dbeafe;color:#1e40af;border-color:#bfdbfe;',
                        'pendiente_aprobacion' => 'background:#f3e8ff;color:#6b21a8;border-color:#e9d5ff;',
                        default                => 'background:var(--cream-100);color:var(--ink-500);border-color:var(--cream-300);',
                    };
                @endphp
                <div style="margin-top:8px;">
                    <span style="display:inline-flex;align-items:center;gap:4px;
                                 padding:4px 10px;border-radius:999px;font-size:11px;font-weight:600;
                                 border:1px solid;{{ $badgeStyle }}">
                        <svg style="width:11px;height:11px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $solicitudActiva->tipoLabel() }}: {{ $solicitudActiva->statusLabel() }}
                    </span>
                </div>
                @endif
            </div>

            {{-- Acciones --}}
            <div style="padding:12px 16px;border-top:1px solid var(--cream-100);
                        display:flex;gap:6px;flex-wrap:wrap;">
                <a href="{{ route('documentos.download', $doc->id) }}"
                   style="flex:1;display:inline-flex;align-items:center;justify-content:center;gap:5px;
                          padding:8px 10px;border-radius:var(--r-sm);font-size:12px;font-weight:600;
                          background:var(--cream-100);border:1px solid var(--cream-300);
                          color:var(--ink-700);text-decoration:none;transition:background .15s;"
                   onmouseover="this.style.background='var(--cream-200)'"
                   onmouseout="this.style.background='var(--cream-100)'">
                    <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Descargar
                </a>

                @if(!$solicitudActiva)
                <button @click="openRectificar = !openRectificar; openCancelar = false"
                        style="display:inline-flex;align-items:center;justify-content:center;
                               padding:8px 10px;border-radius:var(--r-sm);font-size:12px;font-weight:600;
                               background:transparent;border:1px solid var(--cream-300);
                               color:var(--ink-600);cursor:pointer;transition:background .15s;"
                        title="Solicitar corrección"
                        onmouseover="this.style.background='var(--cream-100)'"
                        onmouseout="this.style.background='transparent'">
                    <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </button>

                <button @click="openCancelar = !openCancelar; openRectificar = false"
                        style="display:inline-flex;align-items:center;justify-content:center;
                               padding:8px 10px;border-radius:var(--r-sm);font-size:12px;font-weight:600;
                               background:transparent;border:1px solid #fecaca;
                               color:#dc2626;cursor:pointer;transition:background .15s;"
                        title="Solicitar eliminación"
                        onmouseover="this.style.background='#fff5f5'"
                        onmouseout="this.style.background='transparent'">
                    <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
                @endif
            </div>

            {{-- Panel: solicitar corrección --}}
            <div x-show="openRectificar" style="display:none;border-top:1px solid #fde68a;
                        background:#fffbeb;padding:14px 16px;">
                <p style="font-size:12px;font-weight:700;color:#92400e;margin-bottom:8px;">
                    Solicitar corrección
                </p>
                <form method="POST" action="{{ route('migrante.rectificar', $doc->id) }}" class="space-y-2">
                    @csrf
                    <input type="hidden" name="tipo" value="rectificacion">
                    <textarea name="descripcion" rows="2" maxlength="1000"
                              placeholder="Describe el error o el cambio necesario…"
                              style="width:100%;font-size:12px;border:1px solid #fde68a;border-radius:8px;
                                     padding:8px 10px;background:white;resize:none;box-sizing:border-box;outline:none;"></textarea>
                    <div style="display:flex;gap:8px;">
                        <button type="submit" style="padding:7px 16px;border-radius:999px;font-size:12px;
                                                     font-weight:700;background:#d97706;color:white;border:none;cursor:pointer;">
                            Enviar
                        </button>
                        <button type="button" @click="openRectificar = false"
                                style="padding:7px 12px;border-radius:999px;font-size:12px;color:#6b7280;
                                       background:transparent;border:none;cursor:pointer;">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>

            {{-- Panel: solicitar eliminación --}}
            <div x-show="openCancelar" style="display:none;border-top:1px solid #fecaca;
                        background:#fff5f5;padding:14px 16px;">
                <p style="font-size:12px;font-weight:700;color:#991b1b;margin-bottom:4px;">
                    Solicitar eliminación
                </p>
                <p style="font-size:11px;color:#b91c1c;margin-bottom:8px;">
                    Un coordinador aprobará la eliminación con su firma digital.
                </p>
                <form method="POST" action="{{ route('migrante.rectificar', $doc->id) }}" class="space-y-2">
                    @csrf
                    <input type="hidden" name="tipo" value="cancelacion">
                    <textarea name="descripcion" rows="2" maxlength="1000"
                              placeholder="Motivo (opcional)…"
                              style="width:100%;font-size:12px;border:1px solid #fecaca;border-radius:8px;
                                     padding:8px 10px;background:white;resize:none;box-sizing:border-box;outline:none;"></textarea>
                    <div style="display:flex;gap:8px;">
                        <button type="submit" style="padding:7px 16px;border-radius:999px;font-size:12px;
                                                     font-weight:700;background:#dc2626;color:white;border:none;cursor:pointer;">
                            Enviar
                        </button>
                        <button type="button" @click="openCancelar = false"
                                style="padding:7px 12px;border-radius:999px;font-size:12px;color:#6b7280;
                                       background:transparent;border:none;cursor:pointer;">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>

        </div>
        @endforeach
    </div>
    @else
    <div style="background:var(--paper);border:2px dashed var(--cream-300);
                border-radius:var(--r-lg);padding:48px 24px;text-align:center;">
        <svg style="width:40px;height:40px;color:var(--cream-400);margin:0 auto 12px;"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <p style="font-size:14px;color:var(--ink-500);margin-bottom:4px;">
            Aún no has subido ningún documento.
        </p>
        <p style="font-size:13px;color:var(--ink-400);">
            Usa el botón <strong>Subir documento</strong> en la parte superior.
        </p>
    </div>
    @endif

    {{-- Form de subida (oculto por defecto si ya hay documentos) --}}
    <div id="form-subir"
         style="background:var(--paper);border:1px solid var(--cream-200);
                border-radius:var(--r-lg);box-shadow:var(--shadow-sm);"
         class="{{ $documentos->isEmpty() ? '' : 'hidden' }}">
        <div style="padding:18px 24px;border-bottom:1px solid var(--cream-100);
                    display:flex;align-items:center;justify-content:space-between;">
            <h3 style="font-family:var(--font-display);font-weight:700;font-size:15px;color:var(--ink-900);">
                Subir nuevo documento
            </h3>
            @if($documentos->isNotEmpty())
            <button onclick="document.getElementById('form-subir').classList.add('hidden')"
                    style="background:none;border:none;cursor:pointer;color:var(--ink-400);font-size:18px;
                           line-height:1;padding:0;">&times;</button>
            @endif
        </div>
        <div class="px-6 py-5">
            <form method="POST" action="{{ route('migrante.documentos.store') }}"
                  enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label style="display:block;font-size:11px;font-family:var(--font-display);
                                      font-weight:700;letter-spacing:0.12em;text-transform:uppercase;
                                      color:var(--ink-700);margin-bottom:8px;">
                            Tipo de documento
                        </label>
                        <select name="etiqueta" required
                                style="width:100%;padding:11px 14px;border-radius:var(--r-md);
                                       border:1px solid var(--cream-300);background:var(--paper);
                                       font-family:var(--font-body);font-size:14px;color:var(--ink-900);
                                       box-sizing:border-box;outline:none;">
                            <option value="">Selecciona un tipo…</option>
                            @foreach($etiquetas as $et)
                                <option value="{{ $et }}" {{ old('etiqueta') === $et ? 'selected' : '' }}>
                                    {{ $et }}
                                </option>
                            @endforeach
                        </select>
                        @error('etiqueta')
                            <p style="font-size:12px;color:var(--brand-red);margin-top:4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label style="display:block;font-size:11px;font-family:var(--font-display);
                                      font-weight:700;letter-spacing:0.12em;text-transform:uppercase;
                                      color:var(--ink-700);margin-bottom:8px;">
                            Nombre descriptivo (opcional)
                        </label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}"
                               placeholder="Ej: Acta original 2022"
                               style="width:100%;padding:11px 14px;border-radius:var(--r-md);
                                      border:1px solid var(--cream-300);background:var(--paper);
                                      font-family:var(--font-body);font-size:14px;color:var(--ink-900);
                                      box-sizing:border-box;outline:none;transition:border-color .15s;"
                               onfocus="this.style.borderColor='var(--brand-orange)'"
                               onblur="this.style.borderColor='var(--cream-300)'">
                    </div>
                </div>

                <div>
                    <label style="display:block;font-size:11px;font-family:var(--font-display);
                                  font-weight:700;letter-spacing:0.12em;text-transform:uppercase;
                                  color:var(--ink-700);margin-bottom:8px;">
                        Archivo (PDF, JPG o PNG · máx. 10 MB)
                    </label>
                    <input type="file" name="archivo" accept=".pdf,.jpg,.jpeg,.png" required
                           style="width:100%;padding:10px 14px;border-radius:var(--r-md);
                                  border:1px solid var(--cream-300);background:var(--paper);
                                  font-size:13px;color:var(--ink-700);box-sizing:border-box;">
                    @error('archivo')
                        <p style="font-size:12px;color:var(--brand-red);margin-top:4px;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button type="submit" class="cm-btn cm-btn-primary" style="padding:11px 28px;font-size:14px;">
                        Subir documento
                    </button>
                </div>
            </form>
        </div>
    </div>

    <p style="font-size:11px;color:var(--ink-400);line-height:1.6;">
        Todos los archivos se almacenan de forma segura. Solo tú y el personal autorizado de Casa Monarca pueden acceder a ellos.
    </p>

</div>

</x-migrante-portal-layout>
