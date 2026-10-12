# 02 · Controladores (¿qué es y qué hace?)

> 📖 Doc oficial: [Controllers](https://laravel.com/docs/13.x/controllers)
> ⭐ Otro concepto clave: el controlador es el "corazón" que atiende cada petición.

---

## 🎯 Respuesta corta

Un **Controlador** es una clase cuyo trabajo es **atender una petición HTTP** y
devolver una **respuesta**.

Si la **Ruta** (guía 03) es el "cartel que dice a qué URL va cada cosa", el
**Controlador** es **quien hace el trabajo** cuando alguien llega a esa URL.

```
Petición:  GET /api/v1/health
              ↓
Ruta:      "esa URL la atiende HealthController"
              ↓
Controlador:  ejecuta su código y devuelve la respuesta
```

---

## 🧱 Partes de un controlador

Los controladores viven en **`app/Http/Controllers/`** y normalmente **heredan** de la
clase base `Controller`. Cada **método público** es una **acción** (algo que puede hacer
una petición).

Estructura mínima:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MovieController extends Controller
{
    // Cada método público = una acción atendible desde una ruta
    public function index(Request $request) { /* ... */ }
    public function show(int $id)          { /* ... */ }
}
```

### La clase base `Controller`

En este proyecto, `app/Http/Controllers/Controller.php` es casi vacía:

```php
abstract class Controller
{
    //
}
```

Es **`abstract`**: significa que **no se puede instanciar directamente** (`new Controller()`
daría error). Solo existe para que todos tus controladores la extiendan y compartan
comportamiento común. En Laravel 11+ viene así de mínima; si necesitas helpers
compartidos, los añades ahí.

---

## 📦 El Controlador real de este proyecto: `HealthController`

Mira `app/Http/Controllers/HealthController.php`:

```php
<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class HealthController extends Controller
{
    use ApiResponse;   // ← usa el trait de la guía 01

    public function __invoke(): JsonResponse
    {
        return $this->successResponse([
            'status' => 'OK',
            'app'    => 'MovieAPI',
        ], 'Health check');
    }
}
```

Fíjate en dos cosas importantes:

1. **`use ApiResponse;`** → aquí se juntan las dos guías: el controlador **usa el trait**
   para no repetir código de respuestas.
2. **`public function __invoke()`** → este es un **controlador de acción única**
   (single action / invokable). Lo explico abajo.

---

## 🎬 Tipos de controladores

### 1. Controlador básico (varios métodos = varias acciones)

El más común. Un controlador con varios métodos públicos, y la ruta decide cuál se llama:

```php
class MovieController extends Controller
{
    public function index()  { /* lista todas las películas */ }
    public function show($id){ /* muestra una película */ }
    public function store()  { /* crea una película */ }
}
```

```php
Route::get('/movies',     [MovieController::class, 'index']);  // llama a index()
Route::get('/movies/{id}',[MovieController::class, 'show']);   // llama a show()
```

### 2. Controlador de acción única (invokable) — el de este proyecto

Cuando **toda** la lógica vive en **un solo método** llamado `__invoke`, puedes usarlo
directamente como si fuera una función:

```php
// En la ruta NO pones método, solo la clase:
Route::get('/health', HealthController::class);
```

Laravel ve que la ruta apunta a una clase sin `[Método]` y ejecuta `__invoke()`.
Es perfecto para cosas pequeñas: un health check, un webhook, un ping.

> 💡 ¿Cuándo usarlo? Si la acción es **una sola cosa** y no necesita varios métodos,
> un invokable es más limpio. Si tiene varias acciones (index, store, show...),
> usa un controlador básico.

### 3. Controlador de recursos (resource) — para CRUD

Si vas a hacer las típicas operaciones de **CRUD** (Crear, Leer, Actualizar, Borrar),
Laravel puede generarte **todo el esqueleto** con un solo comando:

```bash
php artisan make:controller MovieController --resource
```

Esto crea los 7 métodos RESTful estándar:

| Método | Ruta típica | Acción | Verbo HTTP |
|--------|-------------|--------|------------|
| `index` | `/movies` | Listar todos | GET |
| `create` | `/movies/create` | Formulario de creación | GET |
| `store` | `/movies` | Guardar el nuevo | POST |
| `show` | `/movies/{movie}` | Ver uno | GET |
| `edit` | `/movies/{movie}/edit` | Formulario de edición | GET |
| `update` | `/movies/{movie}` | Actualizar | PUT/PATCH |
| `destroy` | `/movies/{movie}` | Borrar | DELETE |

Y se registran con una línea:

```php
Route::resource('movies', MovieController::class);
```

Es la forma "a la Laravel" de montar una API o CRUD completo muy rápido.

---

## 🔑 Datos que el controlador recibe

Un controlador obtiene los datos de la petición de varias formas:

### Inyección de dependencias (la forma limpia)

Simplemente **type-hint** (declara el tipo) de lo que necesitas y Laravel te lo entrega:

```php
use Illuminate\Http\Request;

public function store(Request $request)
{
    $titulo = $request->input('titulo');   // campo del formulario o JSON
}
```

### Parámetros de ruta

```php
public function show(int $id)
{
    return Movie::findOrFail($id);   // el {id} de la URL llega aquí
}
```

### Route Model Binding (automático y elegante)

En vez de recibir un `id` a mano, pide el **modelo completo** y Laravel lo busca solo:

```php
use App\Models\Movie;

public function show(Movie $movie)   // Laravel busca la película por el {movie} de la URL
{
    return $movie;                    // si no existe → error 404 automático
}
```

```php
Route::get('/movies/{movie}', [MovieController::class, 'show']);
// GET /movies/5  →  inyecta la película con id 5
// GET /movies/99999  →  404 automáticamente
```

Es una de las joyas de Laravel: menos código, menos errores.

---

## 🔄 Qué puede devolver un controlador

Un controlador no siempre devuelve HTML. Puede devolver:

```php
public function show(Movie $movie)
{
    return view('movies.show', ['movie' => $movie]);  // una VISTA (HTML con Blade)
}

public function apiShow(Movie $movie)
{
    return response()->json($movie);                  // JSON para una API
}

public function store()
{
    return redirect('/movies');                       // un REDIRECT
}
```

En **este** proyecto (que es una API), los controladores devuelven **JSON**, y usan el
trait `ApiResponse` para que ese JSON siempre tenga la misma forma:

```php
return $this->successResponse($data, 'Mensaje', 200);   // 200 OK
return $this->errorResponse('No encontrado', 404);      // 404
```

---

## 🛠️ Cómo crear un controlador

```bash
# Básico
php artisan make:controller MovieController

# Con acción única (invokable) — como HealthController
php artisan make:controller HealthController --invokable

# Con los 7 métodos CRUD — el más usado
php artisan make:controller MovieController --resource
```

> 💡 **Regla del nombre:** siempre en **singular y PascalCase**: `MovieController`,
> no `MoviesController`. Laravel lo espera así por convención.

---

## 🧩 Cómo se conecta con lo anterior

```
                 ┌─────────────┐
  URL ──────────►│   RUTA      │  "GET /api/v1/health → HealthController"
                 └──────┬──────┘
                        ▼
                 ┌─────────────┐
                 │ CONTROLADOR │  HealthController::__invoke()
                 │  use Trait  │───► usa ApiResponse (guía 01)
                 └──────┬──────┘
                        ▼
                  Respuesta JSON
```

1. La **Ruta** decide qué controlador atiende la petición.
2. El **Controlador** ejecuta su acción.
3. El **Trait** le presta métodos reutilizables (como `successResponse`).

---

## ✅ Reglas para controladores como un pro

1. **Un controlador, un recurso.** `MovieController` solo para películas.
2. **Delgados (Thin Controllers):** la lógica pesada va en **Modelos** o **Servicios**,
   no en el controlador. El controlador solo orquesta: recibe, delega, responde.
3. **Devuelve siempre el mismo formato** (usa un trait como `ApiResponse`).
4. **Usa Route Model Binding** en vez de buscar modelos a mano.
5. **Nómbralos en singular:** `MovieController`, `UserController`.

---

## 🧪 Prueba rápida

Con el servidor arrancado (`php artisan serve`):

```bash
curl http://127.0.0.1:8000/api/v1/health
```

Salida:

```json
{"data":{"status":"OK","app":"MovieAPI"},"message":"Health check"}
```

Ese JSON lo fabrica `HealthController::__invoke()` con la ayuda del trait `ApiResponse`.

---

## 💡 Para recordar

- Un **Controlador** atiende una petición y devuelve una respuesta.
- Vive en `app/Http/Controllers` y normalmente **hereda** de `Controller` (abstract).
- Cada **método público** es una **acción**.
- Tipos: **básico** (varias acciones), **invokable** (una sola, `__invoke`) y **resource** (CRUD).
- Los datos llegan por **inyección de dependencias**, **parámetros de ruta** o **Route Model Binding**.
- Mantenlos **delgados**: la lógica va en modelos/servicios.

**Siguiente:** [03 · Rutas →](03-rutas.md)
