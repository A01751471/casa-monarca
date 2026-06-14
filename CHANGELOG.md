# Changelog

Todos los cambios relevantes de este proyecto se documentan en este archivo.

El formato se basa en [Keep a Changelog](https://keepachangelog.com/es-ES/1.1.0/)
y este proyecto se adhiere a [Versionado Semántico](https://semver.org/lang/es/).

---

## [1.1.0] — 2026-06-09

### Etapa 2 — Firma Digital y Flujo ARCO Completo

#### Añadido

- **`FirmaController`** con protocolo challenge-response RSA-2048 para firma
  digital de documentos de expediente. La llave privada del Coordinador nunca
  sale del navegador; se utiliza la Web Crypto API
  (`crypto.subtle.importKey` + `crypto.subtle.sign` con
  RSASSA-PKCS1-v1_5 / SHA-256). Endpoints nuevos:
  - `GET /firmas/{documento}/challenge` — genera nonce asociado a sesión y documento.
  - `POST /firmas/{documento}/store` — verifica firma y actualiza `firmado_at` y `visible_migrante`.
- **`DocumentoIdentidadController`** con sellado HMAC-SHA256 sobre documentos
  de identidad cargados por personas migrantes. Flujo en dos pasos
  (`store()` + `sellar()`) con confirmación explícita del migrante. La llave
  HMAC es `config('app.key')`.
- **`RectificacionController`** con máquina de estados completa del flujo
  ARCO de Rectificación (`pendiente_correccion` → `en_proceso` →
  `esperando_aprobacion` → `aprobado`). El Coordinador aprueba firmando con
  el mismo protocolo challenge-response. Ambas versiones del documento (la
  eliminada y la nueva) quedan registradas en `actividad_log`.
- **`ArchivosMigrantesController`** con vista administrativa de auditoría:
  - `GET /admin/archivos` — galería de migrantes con conteo y estado de sellado.
  - `GET /admin/archivos/{migrante}` — detalle por migrante.
  - `GET /admin/archivos/doc/{doc}/verificar` — verificación AJAX de integridad recalculando hash desde disco.
- **Rol Voluntario (nivel 4)** con permisos exclusivos de creación. Resuelve
  el vacío de personas que apoyan puntualmente sin necesitar acceso amplio.
- **Visibilidad en dos fases** para documentos de expediente: nuevo flag
  `visible_migrante` (boolean, default false). Los documentos quedan
  resaltados con fondo ámbar para el Coordinador hasta que firma; sólo
  entonces se vuelven visibles para el migrante.
- **Creación directa de casos por Coordinador** (`/coordinador/nuevo-caso`)
  para flujos de emergencia y trabajo de campo, sin necesidad de solicitud
  previa del migrante.
- **Logging de descargas de staff** en `actividad_log` con prefijo del hash
  del documento descargado, para trazabilidad adicional.
- **Componente Alpine.js de firma** con drag-and-drop de archivo `.pem`,
  feedback visual de éxito/error, y manejo de challenge expirado
  (HTTP 419).
- **Casos de prueba TC-16 a TC-22** cubriendo todos los flujos nuevos
  (firma válida/inválida, sellado, verificación, ARCO completo, visibilidad
  en dos fases, creación directa de casos).

#### Cambiado

- **Verificación criptográfica** elevada de comparación de fingerprints
  server-side (v1.0.0) a firma digital genuina sobre un challenge fresco
  asociado al documento. La comparación de fingerprints se mantiene como
  segunda línea de defensa.
- **Base de datos de desarrollo:** migrada de MySQL 8 a SQLite
  (`database/database.sqlite`) para reducir fricción de onboarding. La base
  de producción sigue siendo MySQL 8, ahora orquestada con Docker
  (`compose.yaml`).
- **Vite:** actualizado de 7.x a 8.x (sin cambios en configuración ni assets
  compilados).
- **Pest PHP:** actualizado de 4.1 a 4.4.

#### Migraciones

- `2026_06_04_000001_add_sello_integridad_to_documentos.php` — añade
  columnas `sello_integridad` (TEXT) y `sellado_at` (TIMESTAMP) a la tabla
  `documentos`.
- `2026_06_04_000002_add_visible_migrante_to_documentos.php` — añade flag
  `visible_migrante` (boolean, default false).
- `2026_06_04_000003_add_firmado_at_to_documentos.php` — añade timestamp
  `firmado_at`.
- `2026_06_04_000004_create_solicitudes_rectificacion_table.php` — tabla
  para el flujo ARCO de Rectificación.
- `2026_06_04_000005_add_voluntario_role.php` — semilla del rol nivel 4.

#### Endpoints nuevos (referencia rápida)

| Método | Ruta | Controlador@acción |
| --- | --- | --- |
| GET  | `/firmas/{documento}/challenge`            | `FirmaController@challenge` |
| POST | `/firmas/{documento}/store`                | `FirmaController@store` |
| POST | `/documentos-identidad`                    | `DocumentoIdentidadController@store` |
| POST | `/documentos-identidad/{doc}/sellar`       | `DocumentoIdentidadController@sellar` |
| GET  | `/admin/archivos`                          | `ArchivosMigrantesController@index` |
| GET  | `/admin/archivos/{migrante}`               | `ArchivosMigrantesController@show` |
| GET  | `/admin/archivos/doc/{doc}/verificar`      | `ArchivosMigrantesController@verificar` |
| POST | `/rectificaciones`                         | `RectificacionController@store` |
| POST | `/rectificaciones/{r}/tomar`               | `RectificacionController@tomar` |
| POST | `/rectificaciones/{r}/propuesta`           | `RectificacionController@subirPropuesta` |
| POST | `/rectificaciones/{r}/aprobar`             | `RectificacionController@aprobar` |
| GET  | `/coordinador/nuevo-caso`                  | `CoordinadorController@nuevoCasoForm` |
| POST | `/coordinador/nuevo-caso`                  | `CoordinadorController@nuevoCaso` |

#### Notas para mantenimiento

- La APP_KEY se usa como llave HMAC para el sellado. **No rotarla sin
  re-sellar** los documentos de identidad existentes, o todos quedarán
  marcados como inválidos.
- El challenge se asocia a la sesión y al documento específico; expira al
  cambiar de sesión. Verificar comportamiento en navegadores con
  Same-Site cookies estrictas.
- La verificación de integridad desde disco
  (`/admin/archivos/doc/{doc}/verificar`) es computacionalmente costosa.
  Para producción se recomienda paginación obligatoria y procesamiento
  asíncrono.

---

## [1.0.0] — 2026-04-30

### Etapa 1 — Gestor de Identidades (entrega inicial)

#### Añadido

- **Sistema base** Laravel 13 + PHP 8.3 con autenticación Laravel Breeze,
  ORM Eloquent, plantillas Blade, Tailwind CSS 3.1+ y Alpine.js 3.4+.
  Compilación de assets con Vite 7.x.
- **Gestión de identidades** con jerarquía de cinco niveles
  (Administrador, Coordinador, Operativo, Usuario, Migrante) sobre seis
  áreas operativas (Humanitaria, PsicoSocial, Legal, Comunicación, Almacén,
  Tecnologías de Información).
- **Ciclo de vida de identidad** con máquina de estados:
  `pendiente` → `alta` → `suspensión` / `revocación` / `baja`, con flujo de
  restauración.
- **Middleware `CheckStatus`** que bloquea globalmente el acceso de cuentas
  con estado distinto de `alta` y redirige a pantalla informativa.
- **PKI interna simplificada:** al aprobar a un Coordinador, el sistema
  invoca `openssl_pkey_new()` para generar un par RSA-2048. La llave
  pública se almacena en la tabla `certificados` con fingerprint SHA-256.
  La llave privada se renderiza una sola vez en sesión flash
  (patrón "e.firma SAT") y nunca se persiste.
- **Verificación PEM** en acciones sensibles (edición y eliminación de
  documentos del expediente): el servidor compara fingerprints con
  `hash_equals()` para evitar timing attacks.
- **Tabla `actividad_log`** como bitácora inmutable. **Decisión de diseño:
  sin llave foránea hacia `users`** — almacena snapshot textual del actor
  (nombre, matrícula, rol) para preservar utilidad de auditoría aún ante
  eliminación de identidades, en línea con el Derecho ARCO de Cancelación.
- **Gestión de expedientes** con folio único en formato `CM-AAAA-XXXX`
  generado por `FolioService`.
- **Hash SHA-256** persistido al cargar cada documento, para integridad
  documental.
- **Portal trilingüe (ES/EN/FR)** para personas migrantes con entrevista de
  ingreso al albergue en tres pasos.
- **Contraseña auto-generada de 10 caracteres** para migrantes, entregada
  una sola vez tras la aprobación.
- **Categoría Agente Externo** para proveedores, auditores y aliados con
  convenio.
- **Casos de prueba TC-01 a TC-15** cubriendo todos los flujos principales
  de gestión de identidades, certificados, expedientes y bitácora.

#### Modelos de datos (versión 1.0.0)

- `users` — identidades del personal y migrantes
- `certificados` — llaves públicas RSA-2048 con fingerprint
- `areas` — seis áreas operativas
- `solicitudes` — solicitudes de servicio de migrantes
- `postulaciones` — atención de operativos a solicitudes
- `expedientes` — folio único `CM-AAAA-XXXX`
- `documentos` — archivos con hash SHA-256
- `actividad_log` — bitácora inmutable

#### Sub-problemas del reto cubiertos

- **SP-1** (Gestor de Identidades): cobertura principal
- **SP-5** (Gestión de expedientes y solicitudes): cobertura parcial
- SP-2, SP-3, SP-4, SP-6, SP-7: fuera de alcance en esta etapa

---

## Convenciones para entradas futuras

Cada nueva versión debe incluir, cuando aplique, estas subsecciones:

- **Añadido** — funcionalidades nuevas.
- **Cambiado** — cambios en funcionalidades existentes.
- **Obsoleto** — funcionalidades que se retirarán en una versión próxima.
- **Removido** — funcionalidades retiradas.
- **Corregido** — bugs corregidos.
- **Seguridad** — vulnerabilidades atendidas.
- **Migraciones** — referencia a las migraciones de base de datos asociadas.
- **Notas para mantenimiento** — advertencias para quien continúe el código.

Formato de fecha: `YYYY-MM-DD`. Mantener orden cronológico inverso (más
reciente al inicio).
