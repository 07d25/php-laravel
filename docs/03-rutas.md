# 03 · Rutas (Routing)

> 📖 Doc oficial: [Routing](https://laravel.com/docs/13.x/routing)

---

## 🎯 ¿Qué es una Ruta?

Una **Ruta** es la regla que conecta una **URL** con un **pedazo de código**.

"Cuando alguien pida `GET /api/v1/health`, ejecuta `HealthController`". Eso es una ruta.

Las rutas viven en **`routes/`**:

| Archivo | Para qué sirve |
|---------|----------------|
| `routes/web.php` | Rutas que devuelven **HTML** (páginas web, con sesión y CSRF) |
| `routes/api.php` | Rutas de **API** (sin sesión, devuelven JSON) |
| `routes/console.php` | Comandos de consola (Artisan) |

---

## 📦 Las rutas reales de este proyecto

### `routes/web.php`

```php
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
```

- `Route::get('/', ...)` → escucha el verbo **GET** en la URL `/`.
- `function () { ... }` → una **closure** (función anónima) que hace el trabajo.
- `return view('welcome')` → devuelve la plantilla `resources/views/welcome.blade.php`.

### `routes/api.php`

```php
use App\Http\Controllers\HealthController;
use Illuminate\Support\Facades\Route;

Route::get('/health', HealthController::class)->name('health');
```

- `HealthController::class` → en vez de una closure, apunta a un **controlador**
  (ver guía 02). Como es invokable, se ejecuta `__invoke()`.
- `->name('health')` → le pone un **nombre** al ruta, para referirnos a ella sin
  recordar la URL.

> 🔎 **¿Por qué `/health` y no `/api/v1/health`?** Porque en `bootstrap/app.php` está
> `apiPrefix: "api/v1"`. Laravel **prefija automáticamente** todas las rutas de
> `routes/api.php`. La URL final es `/api/v1/health`.

---

## 🧭 Verbos HTTP (el método de la petición)

Cada ruta indica **qué verbo** atiende:

| Método | Cuándo se usa | Ejemplo |
|--------|---------------|---------|
| `Route::get` | Leer / mostrar | Ver lista de películas |
| `Route::post` | Crear algo nuevo | Enviar formulario de alta |
| `Route::put` | Actualizar completo | Editar película |
| `Route::patch` | Actualizar parcial | Cambiar solo el título |
| `Route::delete` | Borrar | Eliminar película |

```php
Route::get('/movies',    [MovieController::class, 'index']);
Route::post('/movies',   [MovieController::class, 'store']);
Route::delete('/movies/{movie}', [MovieController::class, 'destroy']);
```

---

## 🧩 Tres formas de definir una ruta

### 1. Con una closure (simple, para cosas quick)

```php
Route::get('/ping', function () {
    return 'pong';
});
```

### 2. Apuntando a un controlador (lo habitual)

```php
Route::get('/health', HealthController::class);                    // invokable
Route::get('/movies', [MovieController::class, 'index']);          // con método
```

### 3. Con grupo (para agrupar prefijos, middleware, etc.)

```php
Route::prefix('admin')->group(function () {
    Route::get('/users', [UserController::class, 'index']);  // → /admin/users
    Route::get('/posts', [PostController::class, 'index']);  // → /admin/posts
});
```

---

## 📌 Parámetros de ruta (capturar datos de la URL)

Las llaves `{}` capturan un trozo de la URL y lo pasan a tu código:

```php
Route::get('/movies/{id}', function (string $id) {
    return 'Película número '.$id;
});
// GET /movies/42  →  "Película número 42"
```

Se puede **restringir** el formato con `where`:

```php
Route::get('/movies/{id}', function (string $id) {
    // ...
})->where('id', '[0-9]+');   // solo acepta números; si no, 404
```

Y con **Route Model Binding** (recomendado, ver guía 02) se recibe el modelo ya cargado:

```php
Route::get('/movies/{movie}', [MovieController::class, 'show']);
```

---

## 🏷️ Rutas con nombre (Named Routes)

Ponerle `->name('...')` permite **referirse a la ruta por nombre** en vez de escribir
la URL a mano. Ventaja: si la URL cambia, solo cambias la definición y todo lo demás
sigue funcionando.

```php
Route::get('/health', HealthController::class)->name('health');
```

Uso:

```php
$url = route('health');              // genera la URL: "http://.../api/v1/health"
return redirect()->route('health');  // redirige a esa URL
```

---

## 📋 Inspeccionar tus rutas

```bash
php artisan route:list
```

Salida (con este proyecto):

```
| Method | URI             | Name   | Action                      |
|--------|-----------------|--------|-----------------------------|
| GET    | /               |        | Closure                     |
| GET    | /up             |        | (health check del framework)|
| GET    | /api/v1/health  | health | HealthController            |
```

Súper útil para **verificar** a qué controlador va cada URL y detectar conflictos.

---

## ✅ Reglas para rutas como un pro

1. **Agrupa** con `prefix`, `middleware` y `name` para no repetir.
2. Usa **nombres únicos** (`->name()`), especialmente en apps grandes.
3. Para APIs, deja el prefijo en `bootstrap/app.php` (`apiPrefix`), no en cada ruta.
4. Revisa `php artisan route:list` cuando dudes de cómo está montado algo.
5. Las rutas de `api.php` devuelven **JSON**; las de `web.php`, **vistas**.

---

## 🧪 Prueba rápida

```bash
php artisan route:list --path=api
curl http://127.0.0.1:8000/api/v1/health
```

---

## 💡 Para recordar

- Una **Ruta** = URL + verbo HTTP + código que responde.
- Viven en `routes/web.php` (HTML) y `routes/api.php` (JSON).
- `apiPrefix` en `bootstrap/app.php` prefija las rutas de API automáticamente.
- Pueden usar **closure**, **controlador** o **grupo**.
- `{param}` captura datos; con **Route Model Binding** recibes el modelo.
- `->name()` da un alias; `php artisan route:list` las lista todas.

**Siguiente:** [04 · Modelos y Eloquent →](04-modelos-eloquent.md)
