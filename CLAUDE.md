# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

---

## Quick Summary

**Casa Monarca** is a Laravel 13 web application for the Mexican NGO shelter Casa Monarca. It manages migrant case workflows, document handling with cryptographic integrity, and digital signatures using PKI (RSA-2048). Built for Tec de Monterrey course MA2006B.

**Core focus:** Document lifecycle with cryptographic sealing, staff approval workflows, and immutable audit trails.

---

## Stack & Commands

| Layer | Tech | Notes |
|-------|------|-------|
| **Language** | PHP 8.3 | openssl_* functions for RSA/SHA-256/HMAC |
| **Framework** | Laravel 13 | Eloquent ORM, Gates, Middleware |
| **Frontend** | Tailwind CSS 3 + Alpine.js 3 | No build step required for most views |
| **Build** | Vite 8 + laravel-vite-plugin | Compiles CSS/JS for resources/css/app.css |
| **DB (dev)** | SQLite | resources/database.sqlite |
| **DB (prod)** | MySQL 8 | Via Docker compose.yaml |
| **Testing** | Pest PHP 4.4 | — |

### Common Commands

```bash
# First-time setup
composer install && npm install && npm run build
cp .env.example .env && php artisan key:generate
php artisan migrate --seed

# Development (runs server + vite + queue + logs concurrently)
composer run dev

# Build frontend assets
npm run build

# Run tests
php artisan test

# Check code style (Laravel Pint)
./vendor/bin/pint

# Interactive shell
php artisan tinker

# Docker alternative (MySQL)
docker compose up -d
docker compose exec app php artisan migrate --seed
```

---

## Architecture Overview

### User Hierarchy & Authorization

5 roles define access levels via `nivel_acceso` column in `roles` table:

```
1 = Administrador    (can delete, approve users, revoke certs)
2 = Coordinador      (can create cases, sign documents with RSA-2048)
3 = Operativo        (can create cases)
4 = Voluntario       (can create cases only)
5 = Migrante         (can upload documents, request ARCO corrections, view approved docs)
```

**Key pattern:** Gates in `AppServiceProvider::boot()` check `role_id` directly.  
**Middleware:** `CheckStatus` blocks any user with `status !== 'alta'` before each request.

---

### Three Document Flows

#### 1. **Expediente Documents** (case files, uploaded by staff)

```
Colaborador uploads PDF
  ↓
Stored in /storage/expedientes/{expediente_id}/
  ↓
Coordinator reviews and signs with their .pem
  ↓
RSA-2048 signature generated (saved to `firmas` table)
  ↓
`visible_migrante` flag set to true
  ↓
Migrante can now view/download from their case portal
```

**Key files:**
- `app/Http/Controllers/CasoController.php` — `subirDocumento()`, `editarDocumento()`, `eliminarDocumento()`
- `app/Http/Controllers/FirmaController.php` — `challenge()`, `store()` for digital signing
- `app/Models/Documento.php` — `visible_migrante` bool, relationships to `Expediente` + `Firma`

#### 2. **Identity Documents** (migrante uploads passport/ID)

```
Migrante uploads PDF in /mi-espacio/documentos
  ↓
SHA-256 hash calculated and saved
  ↓
Modal appears: "Confirmar y sellar documento"
  ↓
Migrante clicks confirm → HMAC-SHA256 seal applied (saved to `sello_integridad`)
  ↓
Document marked `sellado_at` = now()
  ↓
Admin can verify integrity later via `/admin/archivos`
```

**Key files:**
- `app/Http/Controllers/DocumentoIdentidadController.php` — `index()`, `store()`, `sellar()`, `download()`, `destroy()`
- `app/Models/Documento.php` — `selladoEsValido()` verifies HMAC locally
- `database/migrations/2026_06_04_000001_add_sello_integridad_to_documentos.php`

#### 3. **ARCO Corrections** (migrant requests doc changes)

```
Migrante clicks "edit" on a document → describes needed correction
  ↓
Status = "pendiente_correccion"
  ↓
Colaborador takes task → uploads corrected version
  ↓
Status = "esperando_aprobacion"
  ↓
Coordinator reviews + signs with .pem
  ↓
Original deleted, corrected version becomes active
  ↓
Both versions logged immutably in `actividad_log`
```

**Key files:**
- `app/Http/Controllers/RectificacionController.php` — `solicitar()`, `tomar()`, `subirPropuesta()`, `aprobar()`
- `app/Models/SolicitudRectificacion.php` — status enum + relationships

---

### Cryptographic Layer

**RSA-2048 Public Key Infrastructure:**

1. Admin approves a Coordinador → `UserController::approve()` generates key pair:
   ```php
   openssl_pkey_new(['private_key_bits' => 2048, 'private_key_type' => OPENSSL_KEYTYPE_RSA])
   // Public key stored in `certificados.public_key`
   // Private key shown ONCE on approval page, never stored
   ```

2. Coordinador uses their .pem file to sign documents:
   - Browser receives `challenge` (nonce) from server
   - JavaScript loads .pem, signs with private key (stays in browser memory)
   - Signature sent to server for verification against stored public key
   - If valid → document marked as signed, migrante can now see it

**SHA-256 + HMAC-SHA256 for document integrity:**
- Identity docs: `hash_sha256 = SHA256(file_bytes)` on upload
- Seal: `sello_integridad = HMAC_SHA256(hash_sha256, app.key)` on confirmation
- Verify: recalculate hash from disk file, compare to stored hash
- Admin can see verification result: "✓ Íntegro" or "✗ Corrupto" in `/admin/archivos`

**Key file:** `app/Http/Controllers/CasoController.php` — `verificarPem()` method validates .pem format.

---

### Admin Archives View (`/admin/archivos`)

New feature to audit all migrant documents:

```
GET /admin/archivos                    → Gallery of all migrants + doc counts
GET /admin/archivos/{migrante}         → All identity docs for one migrant
GET /admin/archivos/doc/{doc}/verificar → JSON: hash verification result
```

**Pattern:** `ArchivosMigrantesController` loads docs with seal status badges.  
Deep verification reads file from disk — expensive but guaranteed accurate.

---

## Data Model Patterns

### Immutable Audit Log (`actividad_log`)

Every meaningful action is logged:
```php
ActividadLog::registrar('firmó_documento', $documento, [
    'nombre' => $documento->nombre,
    'fingerprint' => substr($cert->fingerprint, 0, 16),
]);
```

**Key pattern:** `actor_id` + `actor_nombre` snapshot at event time (FK is NOT used).  
Reason: if user is deleted, audit trail must still show who acted.

### User Status Lifecycle

```
status = 'pendiente'  (new registration, awaiting admin approval)
status = 'alta'       (approved, can use system)
status = 'baja'       (deactivated, denied future logins)
status = 'revocacion' (cert revoked, can't sign but can still read)
```

Middleware `CheckStatus` enforces: only 'alta' users proceed past `/middleware/CheckStatus.php`.

### Expediente (Case File) States

```
sin_asignar  → created but no colaborador assigned yet
en_proceso   → colaborador actively working the case
terminado    → case closed, all docs finalized
```

---

## Recent Additions (Implementation Details)

### Document Integrity Sealing (June 2026)
- Added `sello_integridad`, `sellado_at` columns to `documentos` table
- Migrante confirms upload → separate POST to `/documentos/{doc}/sellar` applies HMAC seal
- Modal flow: `store()` returns with session vars, view shows confirmation modal
- Badge UI: green bar + "SELLADO Y ASEGURADO" badge on migrante cards

### Two-Phase Document Visibility
- Operativos upload docs with `visible_migrante = false`
- Coordinador sees docs highlighted (amber background) in expediente view
- After signing with .pem, `visible_migrante = true` → migrante can now see
- Prevents unreviewed docs from reaching beneficiaries

### Staff Download Logging
- When staff downloads identity docs, logged to `actividad_log` with: who, what, when, document hash prefix
- Migrante downloads of case docs NOT logged separately (they own the data)

### Coordinador Case Creation
- Route: `GET/POST /coordinador/nuevo-caso`
- Coordinador can create cases directly for any migrante without migrante initiating
- Case appears in migrante portal as if they created it themselves
- Useful for outreach/emergency workflows

---

## Environment & Config

**`.env` defaults:**
```
APP_KEY=base64:...           (generated by php artisan key:generate)
APP_DEBUG=true               (change to false in production)
MAIL_DRIVER=log              (no real emails sent; check storage/logs/laravel.log)
DB_CONNECTION=sqlite         (use mysql for production)
```

**SQLite (dev):** Create `database/database.sqlite` if missing:
```bash
touch database/database.sqlite
php artisan migrate
```

**Docker (production):** `compose.yaml` spins up PHP 8.3 + MySQL 8 + Redis.

---

## Testing

Pest PHP 4.4 is configured:

```bash
php artisan test                    # run all tests
php artisan test tests/Feature/...  # run one file
php artisan test --filter=methodName
```

**Pattern:** Feature tests live in `tests/Feature/`, they use the testing database and run migrations automatically.

---

## Common Workflows

### Approve a New Coordinador

1. Admin visits `/admin/aprobaciones`
2. Clicks "Aprobar" for the coordinador
3. System generates RSA-2048 pair, stores public key + fingerprint
4. Admin shown private key ONCE (in `/admin/aprobacion-exitosa`)
5. Coordinador downloads `.pem` file, keeps it safe
6. Next login: coordinador selects "Llave .pem" tab, drags file, authenticated

### Sign a Case Document

1. Coordinador opens expediente in `/casos/{expediente}`
2. Sees docs uploaded by operativo with amber label: "Pendiente de tu firma"
3. Clicks "Firmar" on a doc
4. Modal appears: drag `.pem` file into signature zone
5. JavaScript signs challenge with private key (never leaves browser)
6. Server verifies signature against stored public key
7. Doc marked `firmado_at` + `visible_migrante = true`
8. Migrante can now see and download from their portal

### Correct a Document (ARCO)

1. Migrante in `/mi-espacio/documentos` clicks pencil icon on their passport
2. Describes: "Versión antigua, tengo copia apostillada"
3. System creates `SolicitudRectificacion` with `status = pendiente_correccion`
4. Operativo in `/staff/rectificaciones` clicks "Tomar" (claims task)
5. Operativo physically receives corrected doc from migrante, scans it
6. Clicks "Subir propuesta" → uploads corrected file
7. Coordinador reviews in same view, clicks "Aprobar"
8. Coordinador drags .pem to sign → original deleted, new one active
9. `actividad_log` records both the deletion and the approval

---

## Files to Know

| File | Purpose |
|------|---------|
| `app/Http/Controllers/UserController.php` | User lifecycle: approve, revoke, credentials |
| `app/Http/Controllers/CasoController.php` | Case CRUD, document management, PEM verification |
| `app/Http/Controllers/FirmaController.php` | Challenge-response signing protocol |
| `app/Http/Controllers/DocumentoIdentidadController.php` | Migrante identity doc upload + seal |
| `app/Http/Controllers/RectificacionController.php` | ARCO request workflow |
| `app/Http/Controllers/ArchivosMigrantesController.php` | Admin archive viewer |
| `app/Models/Documento.php` | Core doc model, includes `selladoEsValido()` |
| `app/Models/User.php` | User with `migrantePerfil()`, `certificados()` |
| `app/Providers/AppServiceProvider.php` | Gate definitions for authorization |
| `routes/web.php` | Main route definitions |
| `database/migrations/2026_06_04_000001_*.php` | Integrity sealing columns |

---

## Tips for AI-Assisted Development

1. **Route model binding** is used heavily — routes like `GET /casos/{expediente}` auto-load via `Expediente` model
2. **Gates over policies** — authorization uses simple `Gate::define()` in AppServiceProvider, not Policy classes
3. **Session flash variables** — views check `session('var')` to render modals/confirmations post-upload
4. **Relationship eager loading** — use `.with()` in queries to avoid N+1 (e.g., `$docs->load(['autor', 'firmas'])`)
5. **JSON for audit payloads** — `actividad_log.payload` stores structured data about what changed
6. **No hard deletes for sensitive data** — use soft deletes or archive records; ARCO corrections keep both versions in log
7. **Frontend validation** — Alpine.js handles form state/visibility, backend validates all input
8. **Timestamps on everything** — Laravel migrations auto-add `created_at`/`updated_at`; critical for audit trail
