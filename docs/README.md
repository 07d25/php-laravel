# 📚 Guía de Laravel (MovieAPI)

> Estas guías explican los conceptos clave de **Laravel 13** usando el código real de
> este proyecto como ejemplo. Van de lo más básico a lo más avanzado.
>
> 📖 Basadas en la [documentación oficial de Laravel 13.x](https://laravel.com/docs/13.x).
> 💻 El código está en **inglés**; la explicación, en **español**.

## 🗺️ Ruta de aprendizaje

Sigue estos archivos en orden:

| # | Archivo | Qué aprenderás |
|---|---------|----------------|
| 0 | [Estructura y ciclo de vida](00-estructura-laravel.md) | Cómo está organizado un proyecto Laravel y qué ocurre cuando llega una petición HTTP |
| 1 | [Traits](01-traits.md) | **Qué es un Trait, para qué sirve y cómo lo usa Laravel** ⭐ |
| 2 | [Controladores](02-controladores.md) | **Qué es un Controlador, qué hace y sus tipos** ⭐ |
| 3 | [Rutas](03-rutas.md) | Cómo se mapea una URL a una acción |
| 4 | [Modelos y Eloquent](04-modelos-eloquent.md) | El ORM de Laravel y cómo se habla con la base de datos |
| 5 | [Middleware](05-middleware.md) | Las "capas" que revisan cada petición antes de llegar a tu código |
| 6 | [Sección Género — CRUD API](06-genero-api.md) | 👉 **Manos a la obra:** crea tu primer recurso completo (Genre) con validación, resources y tests |
| 7 | [Repository Pattern y Testing](07-repository-pattern.md) | Refactoriza con **Repository Pattern** (interfaces, providers) y deja la API **bien testeada** con factories y Pest |

## 🚀 Ejecutar este proyecto

```bash
composer install          # instala dependencias
cp .env.example .env      # crea el archivo de entorno
php artisan key:generate  # genera la clave de la app
php artisan migrate       # crea las tablas en la base de datos
php artisan serve         # arranca el servidor en http://127.0.0.1:8000
```

Luego prueba el endpoint de salud:

```bash
curl http://127.0.0.1:8000/api/v1/health
# {"data":{"status":"OK","app":"MovieAPI"},"message":"Health check"}
```

## 🧭 Conceptos rápidos

- **Ruta (Route)** → decide *qué URL* dispara *qué código*.
- **Controlador (Controller)** → la clase que *atiende* una petición.
- **Trait** → código reutilizable que se "pega" en varias clases.
- **Modelo (Eloquent)** → representa una tabla de la base de datos.
- **Middleware** → filtro que se ejecuta *antes* de que llegues al controlador.
- **Vista (Blade)** → la plantilla HTML que se devuelve al navegador.
