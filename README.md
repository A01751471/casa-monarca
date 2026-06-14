# Instar — Gestor de Identidades, Registros y Documentos

Sistema web de gestión de identidades, expedientes y firma digital
desarrollado para **Casa Monarca — Ayuda Humanitaria al Migrante, A.B.P.**
como parte del bloque MA2006B del Tecnológico de Monterrey (Equipo 4, Grupo 602).

**Versión actual:** v1.1.0

---

## Descripción

Plataforma que centraliza el ciclo de vida de las identidades digitales del
personal de Casa Monarca, emite credenciales criptográficas a coordinadores,
permite la firma digital de documentos de expediente, sella la integridad de
los documentos de identidad de personas migrantes, y opera el flujo completo
del Derecho ARCO de Rectificación. Todas las acciones quedan registradas en
una bitácora inmutable alineada con la LFPDPPP.

---

## Características principales

- Gestión completa del ciclo de vida de identidades (alta, suspensión, revocación, baja, restauración).
- Cinco niveles operativos (Administrador, Coordinador, Operativo, Voluntario, Migrante) sobre seis áreas.
- Emisión automática de certificados RSA-2048 con entrega única de llave privada.
- Firma digital challenge-response RSA-2048 en el navegador (Web Crypto API) — la llave nunca sale del cliente.
- Sellado HMAC-SHA256 de documentos de identidad con verificación posterior desde disco.
- Flujo ARCO de Rectificación completo en cuatro estados con aprobación firmada.
- Visibilidad en dos fases: los documentos de expediente sólo son visibles al migrante tras la firma del coordinador.
- Vista administrativa `/admin/archivos` con verificación de integridad en tiempo real.
- Bitácora inmutable con snapshot textual del actor (resistente a eliminación de usuarios).
- Portal trilingüe (ES/EN/FR) para personas migrantes.

---

## Requisitos de instalación

- PHP 8.3+
- Composer 2.x
- Node.js 20.x + npm 10.x
- SQLite (desarrollo) o MySQL 8 (producción)
- Extensión OpenSSL de PHP habilitada
- Git
- (Opcional, producción) Docker + Docker Compose

---

## Instalación

```bash
# 1. Clonar el repositorio
git clone https://github.com/A01751471/casa-monarca.git
cd casa-monarca

# 2. Instalar dependencias PHP
composer install

# 3. Instalar dependencias JS
npm install

# 4. Copiar variables de entorno
cp .env.example .env

# 5. Generar APP_KEY (clave usada para sellos HMAC)
php artisan key:generate

# 6. Crear base de datos SQLite local
touch database/database.sqlite

# 7. Ejecutar migraciones y seeders
php artisan migrate --seed

# 8. Compilar assets
npm run build
```

---

## Configuración

Editar el archivo `.env` con los siguientes valores mínimos:

```env
APP_NAME="Casa Monarca"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
# DB_CONNECTION=mysql      # Para producción
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=casa_monarca
# DB_USERNAME=...
# DB_PASSWORD=...
```

La constante `APP_KEY` generada por `php artisan key:generate` se usa como
llave para el sellado HMAC-SHA256 de documentos de identidad. **No debe
rotarse sin re-sellar los documentos existentes.**

---

## Uso básico

```bash
# Servidor de desarrollo
php artisan serve

# En otra terminal, compilación de assets en watch mode
npm run dev
```

Acceder a `http://localhost:8000`. La primera cuenta debe registrarse desde la
pantalla de bienvenida y luego ser elevada manualmente a Administrador en la
base de datos para iniciar el flujo de aprobaciones:

```sql
UPDATE users SET role_id = 1, status = 'alta' WHERE email = 'tu_correo@ejemplo.com';
```

A partir de ahí, el Administrador aprueba a los demás colaboradores desde la
**Bandeja de Accesos** y, al aprobar a un Coordinador, recibe su archivo `.pem`
una sola vez para entregarlo en persona.

---

## Estructura del proyecto

```
casa-monarca/
├── app/
│   ├── Http/
│   │   ├── Controllers/        # Controladores HTTP por dominio
│   │   │   ├── FirmaController.php
│   │   │   ├── DocumentoIdentidadController.php
│   │   │   ├── RectificacionController.php
│   │   │   ├── ArchivosMigrantesController.php
│   │   │   ├── CoordinadorController.php
│   │   │   ├── UserController.php
│   │   │   └── ExpedienteController.php
│   │   ├── Middleware/
│   │   │   └── CheckStatus.php
│   │   └── Requests/
│   ├── Models/                 # Modelos Eloquent
│   │   ├── User.php
│   │   ├── Certificado.php
│   │   ├── Area.php
│   │   ├── Expediente.php
│   │   ├── Documento.php
│   │   ├── Solicitud.php
│   │   ├── Postulacion.php
│   │   ├── SolicitudRectificacion.php
│   │   └── ActividadLog.php
│   ├── Policies/               # Políticas de autorización
│   ├── Providers/
│   └── Services/               # Servicios de dominio
│       ├── CertificadoService.php
│       ├── HashService.php
│       └── FolioService.php
├── bootstrap/
├── config/
├── database/
│   ├── migrations/             # Migraciones de esquema
│   ├── factories/
│   ├── seeders/
│   └── database.sqlite         # BD local (no se versiona)
├── public/                     # Punto de entrada web
├── resources/
│   ├── views/                  # Plantillas Blade
│   │   ├── auth/
│   │   ├── admin/
│   │   ├── coordinador/
│   │   ├── operativo/
│   │   ├── migrante/
│   │   └── components/
│   ├── js/                     # Componentes Alpine.js (incl. firma)
│   └── css/
├── routes/
│   ├── web.php
│   └── auth.php
├── storage/                    # Archivos subidos (no versionados)
├── tests/
│   ├── Feature/                # Pruebas end-to-end (Pest)
│   └── Unit/
├── compose.yaml                # Orquestación Docker (producción)
├── .env.example
├── composer.json
├── package.json
├── vite.config.js
├── README.md
├── LICENSE.md
├── CHANGELOG.md
└── TODO.md
```

---

## Contribuciones

Para colaborar con el proyecto:

1. **Conocer el sistema antes de modificarlo.** Lee este README, el
   `CHANGELOG.md`, el `TODO.md` y los reportes técnicos del repositorio
   (carpeta `docs/`) para entender el alcance actual.

2. **Clonar y ejecutar el sistema localmente** siguiendo la sección de
   Instalación. Verifica que las pruebas pasen antes de empezar:

   ```bash
   ./vendor/bin/pest
   ```

3. **Crear una rama por cambio**, nunca trabajar directamente sobre `main`:

   ```bash
   git checkout -b feat/nombre-descriptivo
   ```

4. **Seguir las convenciones del código existente.** El proyecto utiliza
   Laravel Pint para formato de PHP. Ejecuta `./vendor/bin/pint` antes de
   commitear.

5. **Cubrir cambios con pruebas en Pest** cuando toques lógica de negocio.
   Los flujos criptográficos (firma, sellado, verificación) deben tener
   pruebas dedicadas en `tests/Feature/`.

6. **Abrir un Pull Request** con descripción clara del cambio, casos de uso
   afectados (referencia a TC-XX si aplica) e impacto en la bitácora de
   auditoría.

7. **Antes de tocar lógica criptográfica**, consulta al líder técnico del
   equipo. Cualquier cambio en la firma digital, el sellado o la verificación
   PEM debe validarse con casos de prueba específicos.

---

## Pruebas básicas

El sistema utiliza Pest/PHPUnit. Para correr el conjunto completo:

```bash
./vendor/bin/pest
```

Pruebas específicas por flujo:

```bash
# Flujos de identidad
./vendor/bin/pest tests/Feature/RegistroColaboradorTest.php
./vendor/bin/pest tests/Feature/AprobacionCoordinadorTest.php
./vendor/bin/pest tests/Feature/RevocacionTest.php

# Flujos criptográficos v1.1.0
./vendor/bin/pest tests/Feature/FirmaDocValidoTest.php
./vendor/bin/pest tests/Feature/SelladoIdentidadTest.php
./vendor/bin/pest tests/Feature/FlujoArcoCompletoTest.php
./vendor/bin/pest tests/Feature/VerificacionIntegridadTest.php
```

Los casos de uso validados van de **TC-01 a TC-22** en la versión actual.
Consulta el Reporte Técnico (`docs/01_Reporte_Tecnico_v1_1_0.docx`, Tabla 7)
para la matriz completa.

---

## Licencia

Distribuido bajo licencia **MIT**. Ver `LICENSE.md` para los términos completos.

---

## Contacto

**Equipo 4 — Grupo 602 — Tecnológico de Monterrey, Campus Monterrey**

- Juan David Pastrana Arango (A01751471) — Líder de desarrollo
- Yamil Elias Del Blanco Chávez (A00838610) — Líder técnico
- Luis Roberto Campos Solis (A00838686) — Líder de equipo
- Mariano Vertiz Sánchez (A00840430) — Desarrollador
- Álvaro Duhart Pérez (A01029468) — Desarrollador
- Mauro Artemio Sotelo Dávila (A01707689) — Desarrollador

**Profesores:** Alberto F. Martínez (titular), Daniel Otero Fadul, Raúl Gómez
Muñoz, Pedro Leonel Olaya Trejos.

**Socio Formador:** Casa Monarca — Ayuda Humanitaria al Migrante, A.B.P.
Monterrey, Nuevo León, México.

**Repositorio:** https://github.com/A01751471/casa-monarca
