# 00 · Estructura de Laravel y ciclo de vida de una petición

> 📖 Doc oficial: [Request Lifecycle](https://laravel.com/docs/13.x/lifecycle) ·
> [Directory Structure](https://laravel.com/docs/13.x/structure)

Antes de hablar de Traits y Controladores, conviene entender **dónde vive cada cosa**
y **qué ocurre desde que escribes una URL** hasta que ves la respuesta.

---

## 🗂️ Estructura de carpetas (las que importan al empezar)

```
MovieAPI/
├── app/                    ← TODO tu código aquí (lo más importante)
│   ├── Http/
│   │   ├── Controllers/    ← Los controladores (aten peticiones)
│   │   └── Middleware/      ← Los filtros entrepetición
│   ├── Models/             ← Los modelos Eloquent (base de datos)
│   ├── Traits/             ← Traits reutilizables
│   └── Providers/          ← Arranque de servicios
├── bootstrap/app.php       ← Configuración central (rutas, middleware, excepciones)
├── config/                 ← Archivos de configuración (.env los alimenta)
├── database/
│   └── migrations/         ← "Planos" de las tablas de la BD
├── public/                 ← Carpeta pública (index.php es la puerta de entrada)
├── routes/                 ← Tus rutas: web.php, api.php, console.php
├── resources/views/        ← Plantillas Blade (HTML)
└── .env                    ← Secretos y configuración del entorno (¡no subir a git!)
```

**Regla de oro:** en Laravel, *cada cosa tiene su carpeta*. Si creas un controlador,
va en `app/Http/Controllers`. Si creas un modelo, va en `app/Models`. El framework
usa **convenciones** para encontrar tus archivos automáticamente.

---

## 🚪 Los dos archivos de arranque

### `bootstrap/app.php` — el centro de mando

Este archivo configura cómo se comporta toda la aplicación. El de este proyecto:

```php
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',   // carga las rutas de API
        web: __DIR__.'/../routes/web.php',   // carga las rutas web
        commands: __DIR__.'/../routes/console.php',
        apiPrefix: "api/v1",                 // ¡todas las rutas de API empiezan con /api/v1!
        health: '/up',                       // endpoint de salud automático
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // aquí se registran filtros (ver guía 05)
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // si la URL es de API, los errores se devuelven en JSON (no HTML)
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
```

Fíjate en `apiPrefix: "api/v1"`. Por eso la ruta `/health` definida en `routes/api.php`
se sirve realmente en **`/api/v1/health`**.

### `public/index.php` — la puerta de entrada web

Cuando llega una petición HTTP, el servidor web (Apache/Nginx) ejecuta siempre
`public/index.php`. Ese archivo arranca Laravel y le pasa la petición. No lo tocas casi nunca.

---

## 🔄 ¿Qué ocurre cuando pides `/api/v1/health`?

Imagina que ejecutas `curl http://127.0.0.1:8000/api/v1/health`. Este es el viaje:

```
1. Navegador / curl  →  envía GET /api/v1/health
        ↓
2. public/index.php  →  arranca Laravel (bootstrap/app.php)
        ↓
3. Middleware        →  pasan los filtros del grupo "api"
        ↓
4. Router            →  busca en routes/api.php qué coincide con GET /api/v1/health
        ↓                     ↓ no coincide
        ↓ coincide           └→ error 404 (página no encontrada)
        ↓
5. HealthController  →  se ejecuta su método __invoke()
        ↓
6. Respuesta JSON    →  {"data":{"status":"OK","app":"MovieAPI"},...}
        ↓
7. De vuelta al navegador
```

El punto clave: **el Router (paso 4) es el que conecta una URL con un Controlador (paso 5)**.
Ese es el tema de las guías 02 y 03.

---

## 🧪 Prueba rápida

Abre una terminal en la raíz del proyecto y arranca el servidor:

```bash
php artisan serve
```

En **otra** terminal:

```bash
curl http://127.0.0.1:8000/up        # endpoint de salud automático (health: '/up')
curl http://127.0.0.1:8000/api/v1/health   # NUESTRO controlador (guía 02)
```

La primera URL la responde el framework solo. La segunda la resuelve **tu** código.

---

## 💡 Para recordar

- `app/` es donde vive tu lógica; el resto son carpetas de soporte.
- `bootstrap/app.php` configura rutas, middleware y excepciones.
- `routes/api.php` y `routes/web.php` definen qué URL hace qué.
- Una petición recorre: ** Middleware → Router → Controlador → Respuesta**.
- `apiPrefix` cambia la URL final de tus rutas de API.

**Siguiente:** [01 · Traits →](01-traits.md)
