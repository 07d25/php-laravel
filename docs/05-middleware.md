# 05 · Middleware (los filtros entre la petición y tu código)

> 📖 Doc oficial: [Middleware](https://laravel.com/docs/13.x/middleware)

---

## 🎯 ¿Qué es un Middleware?

**Middleware** (en español "código intermedio") es una **capa que revisa cada petición
HTTP ANTES de que llegue a tu controlador**, y puede dejarla pasar, modificarla o
rechazarla.

Imagina un edificio con varios **porteros**. Cada petición debe pasar por ellos uno a
uno. Si un portero dice "no", la petición no llega al piso de arriba (tu controlador).

```
Petición ──► [Middleware 1] ──► [Middleware 2] ──► [Middleware 3] ──► Controlador
                  │                   │                   │
              ¿pasa?             ¿pasa?             ¿pasa?
```

En Laravel viven en **`app/Http/Middleware/`**.

---

## 🤔 ¿Para qué sirven?

Tareas típicas que **no querrías repetir** en cada controlador:

- **Autenticación:** ¿hay usuario logueado? Si no, al login.
- **CSRF:** ¿este formulario es legítimo?
- **Rate limiting:** ¿no estás haciendo demasiadas peticiones? (429)
- **Logging:** apuntar cada petición en un archivo.
- **Cabeceras:** añadir cabeceras de seguridad a la respuesta.

La ventaja: **se escribe una vez y se aplica a muchas rutas**, sin ensuciar los
controladores.

---

## 🧱 Anatomía de un Middleware

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenIsValid
{
    public function handle(Request $request, Closure $next): Response
    {
        // ANTES de pasar al siguiente nivel:
        if ($request->input('token') !== 'mi-token-secreto') {
            return redirect('/home');   // ← rechazo: no llega al controlador
        }

        // Pasar la petición al siguiente (middleware o controlador):
        return $next($request);
    }
}
```

Lo importante es **`$next($request)`**:

- **Antes de `$next`** → código que corre **antes** del controlador.
- **Después de `$next`** → código que corre **después**, con la respuesta ya creada.

```php
public function handle(Request $request, Closure $next): Response
{
    // ... acción ANTES

    $response = $next($request);   // aquí se ejecuta el resto (controlador)

    // ... acción DESPUÉS

    return $response;
}
```

---

## 📦 Los Middleware por defecto de Laravel

Este proyecto **no define middleware propios** (la carpeta `app/Http/Middleware/` no
existe todavía), pero Laravel ya trae grupos listos que se aplican automáticamente:

### Grupo `web` (para `routes/web.php`)

| Middleware | Qué hace |
|------------|----------|
| `EncryptCookies` | Cifra las cookies |
| `StartSession` | Arranca la sesión |
| `ShareErrorsFromSession` | Comparte errores de validación con las vistas |
| `PreventRequestForgery` | Protección **CSRF** |
| `SubstituteBindings` | Activa el **Route Model Binding** |

### Grupo `api` (para `routes/api.php`)

| Middleware | Qué hace |
|------------|----------|
| `SubstituteBindings` | **Route Model Binding** (las rutas de API no usan sesión ni CSRF) |

> 💡 Esa es la diferencia clave: `web` arranca sesión y protege CSRF (necesario para
> formularios HTML). `api` es **stateless** (sin estado): pensado para consumidores
> externos como apps móviles o un front JavaScript.

---

## 🔧 Cómo registrarlos

### 1. En una ruta concreta

```php
Route::get('/perfil', function () {
    // ...
})->middleware(EnsureTokenIsValid::class);
```

### 2. En un grupo de rutas

```php
Route::middleware([EnsureTokenIsValid::class])->group(function () {
    Route::get('/panel',    ...);
    Route::get('/ajustes',  ...);   // ambos protegidos
});
```

### 3. Globalmente (en `bootstrap/app.php`)

```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->append(EnsureTokenIsValid::class);   // en TODAS las peticiones
})
```

En este proyecto, `bootstrap/app.php` tiene el bloque `withMiddleware` **vacío**:

```php
->withMiddleware(function (Middleware $middleware): void {
    //
})
```

O sea: solo se usan los middleware por defecto. Cuando necesites añadir filtros
propios, los escribirás aquí o en las rutas.

### 4. Con alias (nombre corto)

```php
// bootstrap/app.php
$middleware->alias(['subscribed' => EnsureUserIsSubscribed::class]);

// ruta
Route::get('/premium', ...)->middleware('subscribed');
```

---

## 🏷️ Alias por defecto (muy útiles)

Laravel trae nombres cortos ya configurados:

| Alias | Middleware | Uso típico |
|-------|-----------|------------|
| `auth` | `Authenticate` | Exige usuario logueado |
| `guest` | `RedirectIfAuthenticated` | Solo para NO logueados (login/registro) |
| `can` | `Authorize` | Verifica permisos (policies) |
| `throttle` | `ThrottleRequests` | Rate limiting (limita peticiones) |
| `verified` | `EnsureEmailIsVerified` | Exige email verificado |
| `signed` | `ValidateSignature` | Valida firma de URL |

Ejemplo real de uso (como lo harías en una API):

```php
Route::get('/movies', [MovieController::class, 'index'])
    ->middleware('auth:sanctum');   // solo usuarios autenticados vía token
```

---

## ✅ Reglas para middleware como un pro

1. **Una responsabilidad por middleware** (`EnsureTokenIsValid`, no `DoEverything`).
2. **No repitas lógica** que pueda ir en el controlador o modelo.
3. **Usa los alias** (`auth`, `throttle`) en vez de escribir la clase completa.
4. **Recuerda el orden:** los middleware se ejecutan en el orden en que se registran.
5. Si necesitas correr algo **después** de la respuesta, usa el método `terminate()`.

---

## 🧪 Prueba rápida

Crea un middleware y míralo en acción:

```bash
php artisan make:middleware EnsureTokenIsValid
php artisan route:list -v      # muestra los middleware asignados a cada ruta
```

El flag `-v` revela qué middleware (y grupos) usa cada ruta. Ideal para depurar.

---

## 💡 Para recordar

- **Middleware** = capa intermedia que **filtra/revisa** cada petición.
- Se registran en **rutas**, **grupos** o **globalmente** (`bootstrap/app.php`).
- **`$next($request)`** deja pasar la petición; devolver antes la **bloquea**.
- Laravel ya trae los grupos **`web`** (sesión + CSRF) y **`api`** (stateless).
- Alias útiles: **`auth`, `throttle`, `guest`, `can`**.
- Este proyecto aún no tiene middleware propios: solo usa los por defecto.

---

## 🎓 Fin de la ruta

Con estas 6 guías ya dominas los cimientos de Laravel:

1. ✅ **Estructura y ciclo de vida** — dónde vive cada cosa y el viaje de una petición.
2. ✅ **Traits** — código reutilizable (`ApiResponse`).
3. ✅ **Controladores** — quién atiende cada petición (`HealthController`).
4. ✅ **Rutas** — qué URL dispara qué código.
5. ✅ **Modelos/Eloquent** — cómo se habla con la base de datos (`User`).
6. ✅ **Middleware** — los filtros que protegen y controlan el acceso.

**Siguiente paso sugerido:** crea un **recurso completo** con lo aprendido:

```bash
php artisan make:model Movie -m          # modelo + migración
php artisan make:controller MovieController --resource
php artisan migrate
```

Y únelo todo: el controlador usa el trait `ApiResponse` para responder JSON, y las
rutas se registran con `Route::resource`. ¡Eso es Laravel! 🚀
