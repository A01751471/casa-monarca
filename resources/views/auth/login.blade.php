<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Iniciar sesión — Casa Monarca</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|archivo:700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="background: var(--cream-50); color: var(--ink-900); font-family: var(--font-body);"
      class="antialiased min-h-screen">

<div class="min-h-screen grid lg:grid-cols-2">

    {{-- ── Columna izquierda: formulario ── --}}
    <div style="background: var(--cream-50);" class="flex flex-col px-8 sm:px-16 lg:px-20 py-12">

        <a href="/">
            <img src="{{ asset('images/logo-casa-monarca.png') }}"
                 alt="Casa Monarca"
                 class="h-12 w-auto">
        </a>

        <div class="flex-1 flex flex-col justify-center max-w-sm w-full mt-10">

            <div class="cm-eyebrow mb-3">Portal de colaboradores</div>
            <h1 class="cm-display mb-3" style="font-size: 2.6rem;">
                Bienvenido<br>de regreso.
            </h1>
            <p style="color: var(--ink-500); font-size: 15px; line-height: 1.6;" class="mb-8">
                Ingresa con tu correo institucional o con tu llave de coordinador.
            </p>

            @if (session('status'))
                <div style="background: var(--brand-orange-soft); border: 1px solid var(--brand-orange-line);
                            border-radius: var(--r-sm); padding: 10px 14px; font-size: 13px;
                            color: var(--ink-700);" class="mb-6">
                    {{ session('status') }}
                </div>
            @endif

            {{-- ── Tabs ── --}}
            <div style="display:flex; gap:0; border-bottom: 2px solid var(--cream-200); margin-bottom:24px;">
                <button id="tab-password"
                        onclick="setTab('password')"
                        style="flex:1; padding:10px 0; font-size:13px; font-family:var(--font-display);
                               font-weight:700; letter-spacing:0.08em; background:none; border:none;
                               cursor:pointer; border-bottom:2px solid transparent; margin-bottom:-2px;
                               transition:color .15s, border-color .15s;"
                        class="tab-btn tab-active">
                    Contraseña
                </button>
                <button id="tab-pem"
                        onclick="setTab('pem')"
                        style="flex:1; padding:10px 0; font-size:13px; font-family:var(--font-display);
                               font-weight:700; letter-spacing:0.08em; background:none; border:none;
                               cursor:pointer; border-bottom:2px solid transparent; margin-bottom:-2px;
                               transition:color .15s, border-color .15s;"
                        class="tab-btn">
                    Llave .pem
                </button>
            </div>

            {{-- ── Formulario contraseña ── --}}
            <div id="form-password">
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email"
                               style="display:block; font-size:11px; font-family:var(--font-display);
                                      font-weight:700; letter-spacing:0.12em; text-transform:uppercase;
                                      color:var(--ink-700); margin-bottom:8px;">
                            Correo electrónico
                        </label>
                        <input id="email" type="email" name="email"
                               value="{{ old('email') }}"
                               placeholder="nombre@casamonarca.org.mx"
                               autocomplete="username" autofocus required
                               style="width:100%; padding:13px 16px; border-radius:var(--r-md);
                                      border:1px solid var(--cream-300); background:var(--paper);
                                      font-family:var(--font-body); font-size:14px; color:var(--ink-900);
                                      box-sizing:border-box; outline:none; transition:border-color .15s;"
                               onfocus="this.style.borderColor='var(--brand-orange)'"
                               onblur="this.style.borderColor='var(--cream-300)'">
                        @error('email')
                            <p style="font-size:12px; color:var(--brand-red); margin-top:6px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password"
                               style="display:block; font-size:11px; font-family:var(--font-display);
                                      font-weight:700; letter-spacing:0.12em; text-transform:uppercase;
                                      color:var(--ink-700); margin-bottom:8px;">
                            Contraseña
                        </label>
                        <input id="password" type="password" name="password"
                               placeholder="••••••••••"
                               autocomplete="current-password" required
                               style="width:100%; padding:13px 16px; border-radius:var(--r-md);
                                      border:1px solid var(--cream-300); background:var(--paper);
                                      font-family:var(--font-body); font-size:14px; color:var(--ink-900);
                                      box-sizing:border-box; outline:none; transition:border-color .15s;"
                               onfocus="this.style.borderColor='var(--brand-orange)'"
                               onblur="this.style.borderColor='var(--cream-300)'">
                        @error('password')
                            <p style="font-size:12px; color:var(--brand-red); margin-top:6px;">{{ $message }}</p>
                        @enderror
                        <p style="font-size:11px; color:var(--ink-400); margin-top:6px;">
                            Tu sesión expira tras 30 min de inactividad.
                        </p>
                    </div>

                    <div class="flex items-center justify-between" style="font-size:13px;">
                        <label style="display:flex; align-items:center; gap:8px; color:var(--ink-700); cursor:pointer;">
                            <input type="checkbox" name="remember"
                                   style="accent-color:var(--brand-orange-deep);">
                            Recuérdame en este equipo
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                               style="color:var(--brand-orange-deep); font-weight:600; text-decoration:none;">
                                ¿Olvidaste tu contraseña?
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="cm-btn cm-btn-primary"
                            style="width:100%; padding:15px; font-size:15px; border-radius:var(--r-md);">
                        Iniciar sesión &nbsp;→
                    </button>
                </form>
            </div>

            {{-- ── Formulario llave PEM ── --}}
            <div id="form-pem" style="display:none;">

                @if ($errors->has('pem'))
                    <div style="background:oklch(96% 0.04 25); border:1px solid oklch(85% 0.10 25);
                                border-radius:var(--r-sm); padding:10px 14px; font-size:13px;
                                color:var(--brand-red); margin-bottom:16px;">
                        {{ $errors->first('pem') }}
                    </div>
                @endif

                <div class="space-y-5">
                    <div>
                        <label for="pem-email"
                               style="display:block; font-size:11px; font-family:var(--font-display);
                                      font-weight:700; letter-spacing:0.12em; text-transform:uppercase;
                                      color:var(--ink-700); margin-bottom:8px;">
                            Correo electrónico
                        </label>
                        <input id="pem-email" type="email"
                               placeholder="nombre@casamonarca.org.mx"
                               autocomplete="username"
                               style="width:100%; padding:13px 16px; border-radius:var(--r-md);
                                      border:1px solid var(--cream-300); background:var(--paper);
                                      font-family:var(--font-body); font-size:14px; color:var(--ink-900);
                                      box-sizing:border-box; outline:none; transition:border-color .15s;"
                               onfocus="this.style.borderColor='var(--brand-orange)'"
                               onblur="this.style.borderColor='var(--cream-300)'">
                    </div>

                    <div>
                        <label style="display:block; font-size:11px; font-family:var(--font-display);
                                      font-weight:700; letter-spacing:0.12em; text-transform:uppercase;
                                      color:var(--ink-700); margin-bottom:8px;">
                            Archivo de llave privada (.pem)
                        </label>

                        <label for="pem-file"
                               id="pem-drop-zone"
                               style="display:flex; flex-direction:column; align-items:center;
                                      justify-content:center; gap:8px; width:100%;
                                      padding:24px 16px; border-radius:var(--r-md);
                                      border:2px dashed var(--cream-300); background:var(--paper);
                                      cursor:pointer; box-sizing:border-box; transition:border-color .15s;"
                               ondragover="event.preventDefault(); this.style.borderColor='var(--brand-orange)'"
                               ondragleave="this.style.borderColor='var(--cream-300)'"
                               ondrop="handleDrop(event)">
                            <svg style="width:28px;height:28px;color:var(--ink-400);"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                            <span id="pem-file-label" style="font-size:13px; color:var(--ink-500); text-align:center;">
                                Arrastra tu archivo <strong>.pem</strong> aquí o haz clic
                            </span>
                            <input id="pem-file" type="file" accept=".pem" style="display:none;"
                                   onchange="onPemFileSelected(this)">
                        </label>
                    </div>

                    <div id="pem-status"
                         style="display:none; font-size:12px; color:var(--ink-500);
                                padding:10px 14px; border-radius:var(--r-sm);
                                background:var(--cream-100); border:1px solid var(--cream-200);">
                    </div>

                    <button type="button" id="pem-submit" onclick="submitPem()"
                            class="cm-btn cm-btn-primary"
                            style="width:100%; padding:15px; font-size:15px; border-radius:var(--r-md);
                                   opacity:0.45; cursor:not-allowed;"
                            disabled>
                        Verificar llave &nbsp;→
                    </button>
                </div>

                <p style="font-size:11px; color:var(--ink-400); margin-top:14px; line-height:1.6;">
                    Tu llave privada nunca abandona este dispositivo. Solo se usa para firmar un código de verificación temporal.
                </p>
            </div>

            {{-- Acceso migrante --}}
            <div style="display:flex; align-items:center; gap:14px; margin:28px 0;
                        color:var(--ink-400); font-size:11px; font-family:var(--font-display);
                        font-weight:700; letter-spacing:0.15em;">
                <div style="flex:1; height:1px; background:var(--cream-200);"></div>
                <span>O ENTRA DE OTRA FORMA</span>
                <div style="flex:1; height:1px; background:var(--cream-200);"></div>
            </div>

            <a href="{{ route('migrante.login') }}"
               style="display:flex; align-items:center; gap:14px; padding:16px 18px;
                      background:var(--brand-orange-soft); border-radius:var(--r-md);
                      border:1px solid var(--brand-orange-line); text-decoration:none; color:var(--ink-900);">
                <div style="width:38px; height:38px; border-radius:var(--r-sm); background:var(--paper);
                            border:1px solid var(--brand-orange-line); display:flex; align-items:center;
                            justify-content:center; flex-shrink:0;">
                    <svg style="width:20px;height:20px;color:var(--brand-orange-deep);"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <div style="flex:1;">
                    <div style="font-family:var(--font-display); font-weight:700; font-size:14px;">
                        ¿Eres migrante del albergue?
                    </div>
                    <div style="font-size:12px; color:var(--ink-500); margin-top:2px;">
                        Ingresa con tu llave de acceso
                    </div>
                </div>
                <span style="color:var(--brand-orange-deep); font-weight:700;">→</span>
            </a>

        </div>

        <p style="font-size:11px; color:var(--ink-400); margin-top:32px; font-family:monospace; letter-spacing:0.03em;">
            © {{ date('Y') }} · Casa Monarca A.B.P.
        </p>
    </div>

    {{-- ── Columna derecha: panel de marca (solo desktop) ── --}}
    <div style="background: var(--ink-900); color: var(--cream-50); position:relative; overflow:hidden;
                padding: 48px 56px; display:flex; flex-direction:column; justify-content:space-between;"
         class="hidden lg:flex">

        <div style="position:absolute; right:-120px; top:-60px; width:600px; height:600px; border-radius:50%;
                    background:radial-gradient(circle at 30% 30%, oklch(72% 0.18 50) 0%, oklch(52% 0.20 30) 50%, transparent 70%);
                    opacity:0.35; filter:blur(20px); pointer-events:none;"></div>
        <div style="position:absolute; left:-40px; bottom:-40px; width:240px; height:240px;
                    border-radius:50%; background:oklch(58% 0.20 25 / 0.2);
                    filter:blur(40px); pointer-events:none;"></div>

        <div style="position:relative; display:flex; align-items:center; gap:8px;
                    font-size:12px; color:var(--brand-orange);">
            <span style="width:6px; height:6px; border-radius:999px; background:var(--brand-orange); display:inline-block;"></span>
            Operación activa · Albergue Apodaca
        </div>

        <div style="position:relative;">
            <div class="cm-eyebrow" style="color:var(--brand-orange); margin-bottom:18px;">Nuestra misión</div>
            <p style="font-family:var(--font-display); font-weight:800; font-size:2rem;
                      line-height:1.15; letter-spacing:-0.02em; margin:0; color:var(--cream-50);">
                "Acompañar a la persona migrante en su tránsito, desde el principio de la dignidad humana."
            </p>
            <div style="margin-top:24px; font-size:13px; opacity:0.6; color:var(--cream-200);">
                Casa Monarca · Ayuda Humanitaria al Migrante, A.B.P.
            </div>
        </div>

        <div style="position:relative; display:flex; gap:24px; font-size:12px; opacity:0.5; color:var(--cream-200);">
            <span>Monterrey · N.L.</span>
            <span>·</span>
            <span>Desde 2014</span>
            <span>·</span>
            <span>5 áreas operativas</span>
        </div>
    </div>

</div>

{{-- ── PEM auth JavaScript ── --}}
<style>
.tab-btn { color: var(--ink-400); }
.tab-active { color: var(--brand-orange-deep) !important; border-bottom-color: var(--brand-orange-deep) !important; }
</style>
<script>
function setTab(tab) {
    document.getElementById('form-password').style.display = tab === 'password' ? '' : 'none';
    document.getElementById('form-pem').style.display      = tab === 'pem'      ? '' : 'none';
    document.getElementById('tab-password').classList.toggle('tab-active', tab === 'password');
    document.getElementById('tab-pem').classList.toggle('tab-active', tab === 'pem');
}

let pemPrivateKey = null;

function handleDrop(e) {
    e.preventDefault();
    const file = e.dataTransfer.files[0];
    if (file) loadPemFile(file);
}

function onPemFileSelected(input) {
    const file = input.files[0];
    if (file) loadPemFile(file);
}

async function loadPemFile(file) {
    setStatus('Leyendo archivo…', 'info');
    const text = await file.text();
    try {
        pemPrivateKey = await importPrivateKey(text);
        document.getElementById('pem-file-label').innerHTML =
            '<strong style="color:var(--brand-orange-deep)">✓ ' + file.name + '</strong>';
        document.getElementById('pem-drop-zone').style.borderColor = 'var(--brand-orange)';
        const btn = document.getElementById('pem-submit');
        btn.disabled = false;
        btn.style.opacity = '1';
        btn.style.cursor = 'pointer';
        setStatus('Llave cargada correctamente. Ingresa tu correo y presiona "Verificar llave".', 'ok');
    } catch (err) {
        setStatus('No se pudo leer la llave: ' + err.message, 'error');
    }
}

async function importPrivateKey(pem) {
    const b64 = pem
        .replace(/-----BEGIN (?:RSA )?PRIVATE KEY-----/, '')
        .replace(/-----END (?:RSA )?PRIVATE KEY-----/, '')
        .replace(/\s+/g, '');
    const binary = Uint8Array.from(atob(b64), c => c.charCodeAt(0));
    return await crypto.subtle.importKey(
        'pkcs8', binary.buffer,
        { name: 'RSASSA-PKCS1-v1_5', hash: 'SHA-256' },
        false, ['sign']
    );
}

async function submitPem() {
    const email  = document.getElementById('pem-email').value.trim();
    const submit = document.getElementById('pem-submit');

    if (!pemPrivateKey) { setStatus('Primero selecciona tu archivo .pem.', 'error'); return; }
    if (!email)          { setStatus('Ingresa tu correo electrónico.', 'error'); return; }

    submit.disabled = true;
    submit.style.opacity = '0.45';
    setStatus('Solicitando código de verificación…', 'info');

    try {
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        const res  = await fetch('{{ route('login.pem.challenge') }}?email=' + encodeURIComponent(email), {
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
        });
        const data = await res.json();
        if (!res.ok) {
            setStatus(data.error ?? 'No se encontró un certificado activo para este correo.', 'error');
            submit.disabled = false; submit.style.opacity = '1';
            return;
        }

        setStatus('Firmando código de verificación…', 'info');
        const encoded   = new TextEncoder().encode(data.nonce);
        const sigBuffer = await crypto.subtle.sign('RSASSA-PKCS1-v1_5', pemPrivateKey, encoded);
        const sigB64    = btoa(String.fromCharCode(...new Uint8Array(sigBuffer)));

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('login.pem.verify') }}';
        form.innerHTML = `<input name="_token" value="${csrf}"><input name="email" value="${email}"><input name="signature" value="${sigB64}">`;
        document.body.appendChild(form);
        form.submit();

    } catch (err) {
        setStatus('Error al firmar: ' + err.message, 'error');
        submit.disabled = false; submit.style.opacity = '1';
    }
}

function setStatus(msg, type) {
    const el = document.getElementById('pem-status');
    el.style.display = '';
    el.textContent   = msg;
    el.style.color   = type === 'error' ? 'var(--brand-red)' :
                       type === 'ok'    ? 'var(--brand-orange-deep)' : 'var(--ink-500)';
    el.style.background = type === 'error' ? 'oklch(96% 0.04 25)' : 'var(--cream-100)';
    el.style.borderColor= type === 'error' ? 'oklch(85% 0.10 25)' : 'var(--cream-200)';
}

@if ($errors->has('pem'))
document.addEventListener('DOMContentLoaded', () => setTab('pem'));
@endif
</script>

</body>
</html>
