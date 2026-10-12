# 08 · Tu primera app con Laravel (`task-list/`)

> Carpeta de práctica: [`task-list/`](../task-list/)
> Requisitos: PHP 8.3+, Composer y Node.js (para los assets).

Después de los fundamentos, toca ver **cómo se usa PHP en un proyecto real**.
**Laravel** es el framework PHP más usado del mundo: te da estructura, seguridad y atajos para no reinventar la rueda.

---

## 1. ¿Qué es Laravel (y por qué)?

| Sin framework | Con Laravel |
|---|---|
| tú conectas a la BD a mano | tú escribes `Task::all()` |
| tú validas cada formulario | tú validas con una línea |
| tú te preocupas por XSS/CSRF | Laravel lo hace por ti |
| estructura inventada por ti | estructura estándar que conocen todos |

**Idea mental:** ya sabes escribir funciones, clases y arrays. Laravel es simplemente **mucho código ya escrito y organizado**, con reglas claras, para que tú te centres en tu app.

---

## 2. Cómo arrancarlo

```bash
cd task-list

composer install          # instala dependencias PHP (en vendor/)
cp .env.example .env      # archivo de configuración local
php artisan key:generate  # genera la clave secreta de la app
php artisan serve         # arranca el servidor → http://127.0.0.1:8000
```

Abre <http://127.0.0.1:8000> en el navegador: verás la página de bienvenida.

> `artisan` es la **terminal de Laravel**. Escribirás `php artisan ...` decenas de veces.

Comandos que más usarás:

| Comando | Para qué |
|---------|----------|
| `php artisan serve` | servidor de desarrollo |
| `php artisan migrate` | ejecuta las migraciones (crea tablas) |
| `php artisan make:model Task` | crea un modelo |
| `php artisan make:controller TaskController` | crea un controlador |
| `php artisan test` | ejecuta los tests |
| `php artisan tinker` | consola interactiva para probar código |

---

## 3. Ciclo de vida de una petición (lo más importante)

Este es el mapa mental de **cualquier** app web:

```
1. Navegador      →  GET http://localhost:8000/tasks
2. public/index.php  (puerta de entrada única)
3. routes/web.php    (¿quién responde a /tasks?)
4. Controlador       (hace la lógica: consultar BD, validar...)
5. Modelo (Eloquent) (habla con la base de datos)
6. Vista (Blade)     (pinta el HTML)
7. Respuesta         →  el navegador recibe HTML/JSON
```

### 3.1 Ruta → `routes/web.php`

```php
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');     // devuelve resources/views/welcome.blade.php
});
```

| Parte | Significado |
|-------|-------------|
| `Route::get('/')` | para peticiones **GET** a la raíz |
| `function () { ... }` | qué hacer (aquí, devolver una vista) |
| `view('welcome')` | plantilla `resources/views/welcome.blade.php` |

Ejemplos de rutas que irás escribiendo:

```php
Route::get('/tasks',        [TaskController::class, 'index']);   // listar
Route::get('/tasks/create', [TaskController::class, 'create']);  // formulario
Route::post('/tasks',       [TaskController::class, 'store']);   // guardar
Route::delete('/tasks/{id}', [TaskController::class, 'destroy']); // borrar
```

### 3.2 Punto de entrada → `public/index.php`

**Todas** las peticiones entran por aquí. Este archivo:

```php
require __DIR__.'/../vendor/autoload.php';   // carga Composer (todas las clases)
$app = require_once __DIR__.'/../bootstrap/app.php';   // arranca Laravel
$app->handleRequest(Request::capture());     // procesa la petición
```

> 📌 Nunca edites este archivo. Solo debes apuntar tu servidor web a la carpeta `public/`.

### 3.3 Configuración → `bootstrap/app.php`

```php
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',    // rutas web (sesiones, CSRF)
        commands: __DIR__.'/../routes/console.php',
        health: '/up',                        // endpoint de salud
    )
    ->withMiddleware(...)                     // middlewares (filtros de peticiones)
    ->withExceptions(...)                     // cómo mostrar errores
    ->create();
```

Fíjate en los **nombres en inglés** de los parámetros (`basePath:`, `web:`): eso es **argumentos con nombre**, exactamente lo de [`functions/named_arguments.php`](../functions/named_arguments.php). ✅

---

## 4. Estructura de carpetas

```
task-list/
├── app/                    ← TU CÓDIGO (lo que escribes)
│   ├── Http/Controllers/   ← controladores (reciben rutas → devuelven vistas)
│   ├── Models/             ← modelos Eloquent (cada uno = una tabla)
│   └── Providers/          ← configuración de servicios
│
├── bootstrap/app.php       ← arranque y configuración de Laravel
├── routes/
│   ├── web.php             ← rutas del navegador (GET/POST)
│   └── console.php         ← comandos de artisan
│
├── resources/
│   ├── views/              ← plantillas Blade (HTML con PHP)
│   ├── css/ · js/          ← estilos y scripts (Vite + Tailwind)
│
├── database/
│   ├── migrations/         ← "planos" de las tablas
│   └── seeders/ · factories/ ← datos de prueba
│
├── config/                 ← archivos de configuración
├── public/                 ← lo único expuesto al navegador (index.php, css, js)
├── storage/                ← logs, caché, archivos subidos
├── tests/                  ← tests (Feature/ y Unit/)
├── vendor/                 ← código de Composer (NO se toca, NO se sube a git)
├── .env                    ← configuración secreta (NO se sube a git)
├── composer.json           ← dependencias PHP + scripts
└── artisan                 ← interfaz de comandos
```

Regla de oro: **`app/` es tuyo, `vendor/` es de otros.**

---

## 5. Composer: el gestor de dependencias

`composer.json` (abreviado):

```json
{
    "require": {
        "php": "^8.3",
        "laravel/framework": "^13.8"
    },
    "autoload": {
        "psr-4": {
            "App\\": "app/",
            "Database\\Factories\\": "database/factories/"
        }
    },
    "scripts": {
        "dev": ["php artisan serve", "npm run dev", ...]
    }
}
```

| Sección | Qué hace |
|---------|----------|
| `require` | paquetes necesarios (Laravel, libs) |
| `require-dev` | paquetes solo para desarrollar (tests, formateo) |
| `autoload.psr-4` | **autoloader**: si escribes `App\Models\Task`, PHP carga `app/Models/Task.php` **solito** |
| `scripts` | atajos: `composer setup`, `composer dev`, `composer test` |

> 🔑 El **autoloader** es lo que reemplaza a los `require_once` de [`classes/main.php`](../classes/main.php).
> Por eso en Laravel **nunca** escribes `require`.

---

## 6. Modelo y base de datos (Eloquent)

### Migraciones = planos de las tablas

```bash
php artisan make:model Task --migration
```

Crea un archivo en `database/migrations/`:

```php
Schema::create('tasks', function (Blueprint $table) {
    $table->id();                       // id autoincremental
    $table->string('title');            // VARCHAR
    $table->boolean('completed')->default(false);
    $table->timestamps();               // created_at y updated_at
});
```

```bash
php artisan migrate     # crea la tabla en la base de datos
```

### Modelo = la clase que representa una fila

`app/Models/Task.php`:

```php
class Task extends Model
{
    protected $fillable = ['title', 'completed'];   // campos que se pueden rellenar
}
```

Uso (¡sin escribir una sola SQL!):

```php
Task::create(['title' => 'Aprender PHP']);   // INSERT
Task::all();                                 // SELECT *
Task::where('completed', false)->get();      // SELECT ... WHERE completed = 0
$task->update(['completed' => true]);        // UPDATE
$task->delete();                             // DELETE
```

> Esto es **Eloquent**, un ORM: tratas las filas de la BD como **objetos PHP**.
> Fíjate: todo lo de [07 · POO](07-poo.md) tiene sentido aquí.

---

## 7. Vistas (Blade)

`resources/views/tasks/index.blade.php`:

```blade
<h1>Mis tareas</h1>

<ul>
    @foreach ($tasks as $task)
        <li>
            {{ $task->title }}   {{-- imprime y ESCAPA solo (anti-XSS) --}}
        </li>
    @endforeach
</ul>
```

| Directiva Blade | Equivalente PHP |
|-----------------|-----------------|
| `{{ $var }}` | `echo htmlspecialchars($var)` |
| `{!! $var !!}` | `echo $var` ⚠️ **sin escapar** |
| `@if / @else / @endif` | `if / else` |
| `@foreach / @endforeach` | `foreach` |
| `@extends('layout')` | herencia de plantillas |
| `@csrf` | token de seguridad del formulario |

> 🛡️ `{{ }}` hace **exactamente** lo de [`advanced_strings_encoding.php`](../advanced_strings_encoding.php) con `htmlspecialchars()`. Laravel te protege del XSS por defecto.

---

## 8. Seguridad: lo que Laravel hace por ti

| Amenaza | Cómo la cubre Laravel |
|---------|----------------------|
| **XSS** (inyección de JS) | `{{ }}` escapa el HTML automáticamente |
| **CSRF** (falsas peticiones) | token `@csrf` obligatorio en formularios |
| **SQL Injection** | Eloquent usa *prepared statements* |
| **Contraseñas** | `Hash::make()` (nunca guardas texto plano) |
| **Archivos subidos** | validación de tipo/tamaño |

Recuerda: en el apartado 4 de seguridad vimos `htmlspecialchars()`; Laravel simplemente **lo aplica siempre**.

---

## 9. Tests: comprobar que no has roto nada

`tests/Feature/ExampleTest.php`:

```php
class ExampleTest extends TestCase
{
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');       // simula una petición

        $response->assertStatus(200);      // comprueba que responde 200 OK
    }
}
```

```bash
composer test          # o: php artisan test
```

> 💡 **Hábito senior:** cambia algo → ejecuta los tests → si están en verde, vas bien.
> Los tests son tu red de seguridad.

---

## 10. Scripts útiles de `composer.json`

```bash
composer setup    # install + .env + clave + migrate + npm install + build
composer dev      # arranca TODO a la vez: servidor, cola, logs y Vite
composer test     # limpia caché y ejecuta los tests
```

`composer dev` usa `concurrently` para lanzar varios procesos:

```
server (php artisan serve) · queue (cola de trabajos) · logs (pail) · vite (assets)
```

---

## 11. ¿Por dónde seguir con Laravel?

1. **Haz un CRUD de tareas** (crear, leer, actualizar, borrar):
   - `php artisan make:model Task --migration -c`
   - rutas en `routes/web.php`
   - controlador con `index / create / store / edit / update / destroy`
   - vistas Blade
2. Aprende **validación**: `validate($request, ['title' => 'required|min:3'])`.
3. Añade **autenticación**: `php artisan make:auth` (o Breeze/Jetstream).
4. Lee la documentación oficial: <https://laravel.com/docs>

---

## ✅ Checklist final del repo

- [ ] [00 · Cómo ejecutar](00-como-ejecutar.md) — PHP en la terminal
- [ ] [01 · Sintaxis y tipos](01-sintaxis-y-tipos.md)
- [ ] [02 · Strings](02-strings.md)
- [ ] [03 · Números](03-numeros.md)
- [ ] [04 · Condicionales y bucles](04-control-de-flujo.md)
- [ ] [05 · Arrays](05-arrays.md)
- [ ] [06 · Funciones](06-funciones.md)
- [ ] [07 · POO](07-poo.md)
- [x] [08 · Laravel](08-laravel.md) ← estás aquí

**¡Ruta completa! 🎉** Vuelve a cualquier capítulo cuando lo necesites.
