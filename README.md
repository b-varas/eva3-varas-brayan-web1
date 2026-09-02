# Sistema de Gestión de Proyectos — Tech Solutions Group

Evaluación sumativa Unidad 3 — Framework Web (Laravel 11 / PHP 8.3)
*(construida sobre la base de la Unidad 2 — CRUD de proyectos con MVC y autenticación)*

**Integrantes:** Eduardo Palma - Luis Muñoz - Brayan Varas

Aplicación web para la gestión de proyectos de Tech Solutions. En esta unidad se incorpora una **API REST** para el CRUD de proyectos, además del CRUD web ya construido en unidades anteriores, cumpliendo con los métodos HTTP y códigos de estado estándar.

## Contenido
- [Descripción](#descripción)
- [Tecnologías](#tecnologías)
- [Instalación](#instalación)
- [Configuración de Base de Datos](#configuración-de-base-de-datos)
- [Endpoints de la API REST](#endpoints-de-la-api-rest)
- [Rutas web disponibles](#rutas-web-disponibles)
- [Arquitectura (MVC)](#arquitectura-mvc)
- [Validaciones y manejo de errores](#validaciones-y-manejo-de-errores)
- [Cómo probar la API](#cómo-probar-la-api)

## Descripción

La aplicación permite administrar proyectos (listar, ver, crear, actualizar y eliminar) tanto por interfaz web (con vistas y sesión) como mediante una **API REST** que devuelve JSON, cumpliendo los siguientes requerimientos:

- Inserción de nuevos proyectos vía método HTTP `POST`, con validación de campos requeridos y código de respuesta `201`.
- Recuperación de todos los proyectos vía método HTTP `GET`, con código `200` y arreglo vacío si no hay registros.
- Recuperación de un proyecto por su ID vía `GET`, con código `200` si existe o `404` si no.
- Actualización de un proyecto por su ID vía `PUT`/`PATCH`, con código `200` y devolviendo los campos actualizados.
- Eliminación de un proyecto por su ID vía `DELETE`, de forma segura (verificando existencia previa) y eficiente, con código `204` y respuesta vacía.

## Tecnologías

- PHP 8.3
- Laravel 11
- Eloquent ORM
- MySQL 8
- Laravel Sanctum (scaffolding de API instalado, sin autenticación por token implementada en esta entrega)
- Postman (para pruebas de los endpoints)

## Instalación

```bash
git clone https://github.com/b-varas/eva3-varas-brayan-web1.git
cd eva3-varas-brayan-web1
composer install
cp .env.example .env
php artisan key:generate
```

Configura tu base de datos en `.env` (ver sección siguiente), crea la base de datos en MySQL si aún no existe, y luego:

```bash
php artisan migrate
php artisan serve
```

## Configuración de Base de Datos

El proyecto usa MySQL como motor de base de datos, mediante el ORM Eloquent. En el archivo `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=desarrollo_software_web_1
DB_USERNAME=root
DB_PASSWORD=
```

Antes de ejecutar `php artisan migrate`, la base de datos debe existir en el servidor MySQL:

```sql
CREATE DATABASE desarrollo_software_web_1;
```

## Endpoints de la API REST

Todas las rutas de la API viven bajo el prefijo `/api`, definido en `routes/api.php`, y son gestionadas por `app/Http/Controllers/Api/ProjectController.php`.

| Acción | Verbo | Ruta | Código éxito | Código si no existe |
|---|---|---|---|---|
| Crear proyecto | `POST` | `/api/proyectos` | `201` | — |
| Listar todos los proyectos | `GET` | `/api/proyectos` | `200` | — |
| Buscar proyecto por ID | `GET` | `/api/proyectos/{id}` | `200` | `404` |
| Actualizar proyecto | `PUT` / `PATCH` | `/api/proyectos/{id}` | `200` | `404` |
| Eliminar proyecto | `DELETE` | `/api/proyectos/{id}` | `204` | `404` |

### Campos del proyecto

| Campo | Tipo | Requerido |
|---|---|---|
| `nombre` | string | Sí |
| `fecha_inicio` | date | Sí |
| `estado` | string | Sí |
| `responsable` | string | Sí |
| `monto` | numeric | Sí |
| `created_by` | integer (ID de usuario existente) | Sí, solo en creación |

## Rutas web disponibles

Además de la API, se mantiene el CRUD web con sesión (heredado de la Unidad 2), protegido con el middleware `auth`. Ver `routes/web.php` para el detalle completo (login, registro, y CRUD de proyectos vía formularios).

## Arquitectura (MVC)

- **Modelos** (`app/Models/`):
  - `Project.php` — modelo Eloquent con `$fillable` definido para prevenir mass assignment, y relación `belongsTo` hacia `User` mediante `created_by`.
- **Controladores** (`app/Http/Controllers/`):
  - `ProjectController.php` — CRUD web con vistas Blade.
  - `Api/ProjectController.php` — CRUD vía API, responde JSON con los códigos de estado HTTP definidos en los requerimientos.
- **Rutas**:
  - `routes/web.php` — rutas del CRUD web.
  - `routes/api.php` — rutas de la API REST (prefijo `/api` automático).

## Validaciones y manejo de errores

- Todos los campos son validados con `$request->validate()` antes de insertar o actualizar, devolviendo automáticamente código `422` con el detalle del error si algún campo requerido falta o tiene un tipo inválido.
- El campo `created_by` se valida además con la regla `exists:users,id`, asegurando que solo se pueda asociar un proyecto a un usuario que realmente existe en la base de datos.
- En `show`, `update` y `destroy`, se verifica primero la existencia del proyecto (`Project::find($id)`) antes de continuar, devolviendo `404` con un mensaje descriptivo si no se encuentra.

## Cómo probar la API

Se recomienda usar Postman. Para cada endpoint:

1. Configura el método HTTP correspondiente (POST, GET, PUT/PATCH, DELETE).
2. En **Headers**, agrega `Content-Type: application/json` y `Accept: application/json`.
3. En **Body → raw → JSON**, incluye los campos requeridos según la tabla de campos.

Ejemplo de creación de un proyecto (`POST /api/proyectos`):

```json
{
    "nombre": "Sistema de Inventario",
    "fecha_inicio": "2026-01-15",
    "estado": "activo",
    "responsable": "Juan Perez",
    "monto": 5000,
    "created_by": 2
}
```

---
Desarrollado por Eduardo Palma - Luis Muñoz - Brayan Varas — Evaluación Sumativa Unidad 3, Desarrollo Web con Framework.