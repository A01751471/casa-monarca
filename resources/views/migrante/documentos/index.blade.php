<x-migrante-portal-layout>

<div class="space-y-6">

    {{-- Encabezado --}}
    <div style="background:var(--paper); border:1px solid var(--cream-200);
                border-radius:var(--r-lg); box-shadow:var(--shadow-sm);"
         class="px-6 py-5">
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
                <h2 class="cm-display" style="font-size:1.4rem;">Mis documentos de identidad</h2>
                <p style="font-size:13px;color:var(--ink-500);margin-top:2px;">
                    Sube y administra tus documentos personales de forma segura.
                </p>
            </div>
        </div>
    </div>

    @if(session('status'))
        <div style="background:var(--brand-orange-soft);border:1px solid var(--brand-orange-line);
                    border-radius:var(--r-sm);padding:10px 16px;font-size:13px;color:var(--ink-700);">
            {{ session('status') }}
        </div>
    @endif

    {{-- Subir documento --}}
    <div style="background:var(--paper);border:1px solid var(--cream-200);
                border-radius:var(--r-lg);box-shadow:var(--shadow-sm);">
        <div style="padding:20px 24px;border-bottom:1px solid var(--cream-100);">
            <h3 style="font-family:var(--font-display);font-weight:700;font-size:15px;color:var(--ink-900);">
                Subir nuevo documento
            </h3>
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

    {{-- Lista de documentos --}}
    <div style="background:var(--paper);border:1px solid var(--cream-200);
                border-radius:var(--r-lg);box-shadow:var(--shadow-sm);overflow:hidden;">
        <div style="padding:20px 24px;border-bottom:1px solid var(--cream-100);
                    display:flex;align-items:center;justify-content:space-between;">
            <h3 style="font-family:var(--font-display);font-weight:700;font-size:15px;color:var(--ink-900);">
                Documentos subidos
            </h3>
            <span style="font-size:12px;color:var(--ink-400);">{{ $documentos->count() }} archivo(s)</span>
        </div>

        @if($documentos->isEmpty())
            <div style="padding:40px 24px;text-align:center;color:var(--ink-400);font-size:14px;">
                Aún no has subido ningún documento de identidad.
            </div>
        @else
            <div style="divide-y:var(--cream-100);">
                @foreach($documentos as $doc)
                @php
                    $ext = strtolower($doc->tipo);
                    $iconColor = in_array($ext, ['jpg','jpeg','png']) ? 'var(--brand-orange-deep)' : 'var(--brand-red)';
                @endphp
                <div style="display:flex;align-items:center;gap:14px;padding:14px 24px;
                            border-bottom:1px solid var(--cream-100);">
                    {{-- Icon --}}
                    <div style="width:36px;height:36px;border-radius:var(--r-sm);
                                background:var(--cream-100);display:flex;align-items:center;
                                justify-content:center;flex-shrink:0;">
                        <svg style="width:18px;height:18px;color:{{ $iconColor }};"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>

                    {{-- Info --}}
                    <div style="flex:1;min-width:0;">
                        <p style="font-size:14px;font-weight:600;color:var(--ink-900);
                                  white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $doc->nombre }}
                        </p>
                        <p style="font-size:12px;color:var(--ink-400);margin-top:2px;">
                            <span style="font-family:var(--font-display);font-weight:700;
                                         font-size:10px;letter-spacing:0.1em;text-transform:uppercase;
                                         color:var(--brand-orange-deep);
                                         background:var(--brand-orange-soft);
                                         border:1px solid var(--brand-orange-line);
                                         padding:2px 8px;border-radius:999px;margin-right:8px;">
                                {{ $doc->etiqueta }}
                            </span>
                            .{{ strtoupper($doc->tipo) }} · {{ $doc->created_at->format('d/m/Y') }}
                        </p>
                    </div>

                    {{-- Actions --}}
                    <div style="display:flex;gap:8px;flex-shrink:0;">
                        <a href="{{ route('documentos.download', $doc->id) }}"
                           style="display:inline-flex;align-items:center;gap:6px;
                                  padding:7px 14px;border-radius:999px;font-size:12px;font-weight:600;
                                  background:var(--cream-100);border:1px solid var(--cream-300);
                                  color:var(--ink-700);text-decoration:none;transition:background .15s;"
                           onmouseover="this.style.background='var(--cream-200)'"
                           onmouseout="this.style.background='var(--cream-100)'">
                            <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Descargar
                        </a>

                        <form method="POST" action="{{ route('migrante.documentos.destroy', $doc->id) }}"
                              onsubmit="return confirm('¿Eliminar este documento? No se puede deshacer.')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    style="display:inline-flex;align-items:center;gap:6px;
                                           padding:7px 14px;border-radius:999px;font-size:12px;font-weight:600;
                                           background:transparent;border:1px solid oklch(85% 0.10 25);
                                           color:var(--brand-red);cursor:pointer;transition:background .15s;"
                                    onmouseover="this.style.background='var(--brand-red-soft)'"
                                    onmouseout="this.style.background='transparent'">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <div style="font-size:11px;color:var(--ink-400);line-height:1.6;">
        Todos los archivos se almacenan de forma cifrada. Solo tú y el personal autorizado de Casa Monarca pueden acceder a ellos.
    </div>

</div>

</x-migrante-portal-layout>
