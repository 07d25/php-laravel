# 06 · Sección "Género" — crea tu primer endpoint CRUD de API

> 🎯 **Objetivo de esta sección:** construir el recurso **Género (Genre)** completo de
> la MovieAPI, paso a paso. Al terminar tendrás una API REST funcionando con las
> 5 operaciones básicas: **Listar, Crear, Ver, Actualizar y Borrar**.
>
> 📖 Basado en: [Form Requests](https://laravel.com/docs/13.x/validation) ·
> [API Resources](https://laravel.com/docs/13.x/eloquent-resources) ·
> [Routing — Resource Routes](https://laravel.com/docs/13.x/routing)

---

## 🧠 Antes de empezar: ¿qué vamos a construir?

Un **CRUD** son las 4 operaciones típicas sobre un recurso (más "ver uno"):

| Letra | Significado | En nuestra API |
|-------|-------------|----------------|
| **C** | Create → Crear | `POST /api/v1/genres` |
| **R** | Read → Leer | `GET /api/v1/genres` y `GET /api/v1/genres/{id}` |
| **U** | Update → Actualizar | `PUT/PATCH /api/v1/genres/{id}` |
| **D** | Delete → Borrar | `DELETE /api/v1/genres/{id}` |

Esto es una **API REST**: devuelve **JSON** (no HTML) y usa los **verbos HTTP** para
indicar la intención. Laravel puede generar todo el esqueleto con comandos, y nosotros
lo personalizamos. Ese es exactamente el camino de esta guía.

> 💡 **Convención de esta guía:** los comandos que ejecutas van en bloques `bash`,
> y el código que escribes va en bloques `php`. Síguelos en orden.

---

## 🗺️ El mapa de archivos (lo que crearás al final)

Al terminar, el recurso Genre tocará estos archivos:

```
app/
├── Http/
│   ├── Controllers/GenreController.php     ← atiende las peticiones
│   ├── Requests/
│   │   ├── StoreGenreRequest.php           ← valida al CREAR
│   │   └── UpdateGenreRequest.php          ← valida al ACTUALIZAR
│   └── Resources/GenreResource.php         ← forma del JSON de salida
└── Models/Genre.php                        ← el modelo (tabla genres)

database/
├── migrations/..._create_genres_table.php  ← plan de la tabla
└── seeders/GenreSeeder.php                 ← datos de ejemplo

routes/api.php                              ← las rutas del recurso
tests/Feature/GenreTest.php                 ← pruebas automáticas
```

Vamos por partes.

---

## Paso 1 — El Modelo y su migración

Genera el modelo **y** su migración de base de datos en un solo paso con `-m`:

```bash
php artisan make:model Genre -m
```

Esto crea dos archivos:
- `app/Models/Genre.php` (el modelo)
- `database/migrations/xxxx_create_genres_table.php` (el plan de la tabla)

### 1.1 define la tabla (migración)

Abre el archivo de migración y deja el método `up()` así:

```php
public function up(): void
{
    Schema::create('genres', function (Blueprint $table) {
        $table->id();                            // clave primaria numérica (id)
        $table->string('name')->unique();        // nombre único
        $table->string('slug')->unique();        // "url-friendly", único
        $table->text('description')->nullable(); // opcional
        $table->boolean('is_active')->default(true); // activo por defecto
        $table->timestamps();                    // created_at y updated_at
        $table->softDeletes();                   // columna deleted_at (borrado suave)
    });
}
```

**Nota para principiantes:** las migraciones son "planos" versionados de tu base de datos.
Como el proyecto usa **PostgreSQL** (según el `.env`), ejecuta:

```bash
php artisan migrate
```

> 🧠 ¿Por qué `softDeletes()`? Añade una columna `deleted_at`. Cuando "borras" un género,
> Eloquent **no elimina la fila**: solo marca esa fecha. Así puedes recuperar registros
> por error. Lo veremos en el controlador.

### 1.2 El modelo (`app/Models/Genre.php`)

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Genre extends Model
{
    // Trait HasFactory para usar datos de prueba (tests)
    use HasFactory;

    // Trait para SoftDelete (borrado suave)
    use SoftDeletes;

    // Lo que se permite asignar masivamente (protección - guía 04)
    protected $fillable = ['name', 'slug', 'description', 'is_active'];

    // Conversión automática de tipos
    protected $casts = ['is_active' => 'boolean'];

    // Genera el "slug" automáticamente desde el nombre
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Genre $genre) {
            if (empty($genre->slug)) {
                $genre->slug = Str::slug($genre->name);   // "Ciencia Ficción" → "ciencia-ficcion"
            }
        });

        static::updating(function (Genre $genre) {
            if ($genre->isDirty('name')) {   // ¿cambió el nombre?
                $genre->slug = Str::slug($genre->name);
            }
        });
    }
}
```

**Lo importante aquí:**
- **`use HasFactory, SoftDeletes;`** → traits que "peguan" métodos al modelo (guía 01).
- **`$fillable`** → qué campos se pueden enviar masivamente (seguridad).
- **`$casts`** → `is_active` llega como `true/false` de verdad, no como "1"/"0".
- **`boot()` + eventos** → mágico: tú solo envías `name` y el `slug` se crea solo.
  `Str::slug('Ciencia Ficción')` devuelve `'ciencia-ficcion'`.

---

## Paso 2 — La validación (Form Requests)

En vez de validar dentro del controlador (y ensuciarlo), Laravel usa **Form Requests**:
clases que validan **antes** de que llegues al controlador. Genera las dos:

```bash
php artisan make:request StoreGenreRequest
php artisan make:request UpdateGenreRequest
```

### 2.1 `app/Http/Requests/StoreGenreRequest.php` (para CREAR)

```php
public function authorize(): bool
{
    return true;   // true = cualquiera puede hacerlo (luego se añadirá auth)
}

public function rules(): array
{
    return [
        'name' => ['required', 'string', 'max:100', 'unique:genres,name'],
        'description' => ['nullable', 'string', 'max:500'],
        'is_active' => ['boolean'],
    ];
}
```

| Regla | Significado |
|-------|-------------|
| `required` | Obligatorio |
| `string` | Debe ser texto |
| `max:100` | Máximo 100 caracteres |
| `unique:genres,name` | El nombre **no puede repetirse** en la tabla `genres` |
| `nullable` | Puede venir vacío |

### 2.2 `app/Http/Requests/UpdateGenreRequest.php` (para ACTUALIZAR)

Casi igual, pero el nombre único debe **ignorar el registro actual** (si no, al editar
un género sin cambiarle el nombre, daría error de "duplicado"):

```php
use Illuminate\Validation\Rule;

public function rules(): array
{
    $genre = $this->route('genre');   // el género que estamos editando

    return [
        'name' => [
            'required', 'string', 'max:100',
            Rule::unique('genres', 'name')->ignore($genre),   // ignora el actual
        ],
        'description' => ['nullable', 'string', 'max:500'],
        'is_active' => ['boolean'],
    ];
}
```

> 💡 Si la validación falla, Laravel devuelve solo un **422** con los errores en JSON,
> automáticamente. No tienes que escribir ese código.

---

## Paso 3 — El formato de salida (API Resource)

**Resource** = traductor. Decide **qué campos** del modelo se devuelven al cliente y en
qué forma. Así nunca filtras datos por accidente (como contraseñas). Genera uno:

```bash
php artisan make:resource GenreResource
```

`app/Http/Resources/GenreResource.php`:

```php
public function toArray(Request $request): array
{
    return [
        'id' => $this->id,
        'name' => $this->name,
        'slug' => $this->slug,
        'description' => $this->description,
        'is_active' => $this->is_active,
        'created_at' => $this->created_at?->toISOString(),
        'updated_at' => $this->updated_at?->toISOString(),
    ];
}
```

El `?->` es **null safe**: si `created_at` es `null`, en vez de explotar devuelve `null`.

---

## Paso 4 — El Controlador

Genera el controlador de recursos para API (sin `create`/`edit`, que son para HTML):

```bash
php artisan make:controller GenreController --api --resource
```

Luego haz que **use el trait `ApiResponse`** (el de la guía 01) y el `GenreResource`.
Así queda `app/Http/Controllers/GenreController.php`:

```php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGenreRequest;
use App\Http\Requests\UpdateGenreRequest;
use App\Http\Resources\GenreResource;
use App\Models\Genre;
use App\Traits\ApiResponse;

class GenreController extends Controller
{
    use ApiResponse;   // ← successResponse() y errorResponse() (guía 01)

    // GET /api/v1/genres  →  listar todos
    public function index()
    {
        $genres = Genre::all();

        return $this->successResponse(GenreResource::collection($genres));
    }

    // POST /api/v1/genres  →  crear uno nuevo
    public function store(StoreGenreRequest $request)
    {
        $genre = Genre::create($request->validated());   // solo campos validados

        return $this->successResponse(
            new GenreResource($genre), 'Género creado exitosamente.', 201
        );   // 201 = Created
    }

    // GET /api/v1/genres/{genre}  →  ver uno
    public function show(Genre $genre)
    {
        return $this->successResponse(new GenreResource($genre));
    }

    // PUT/PATCH /api/v1/genres/{genre}  →  actualizar
    public function update(UpdateGenreRequest $request, Genre $genre)
    {
        $genre->update($request->validated());

        return $this->successResponse(new GenreResource($genre), 'Genero actualizado exitosamente.');
    }

    // DELETE /api/v1/genres/{genre}  →  borrar (soft delete)
    public function destroy(Genre $genre)
    {
        $genre->delete();   // por el trait SoftDeletes, NO borra de verdad

        return $this->successResponse(null, 'Género eliminado exitosamente.');
    }
}
```

**Fíjate en las magias de Laravel:**
- **`Genre $genre`** en `show`, `update`, `destroy` → **Route Model Binding**: Laravel
  busca el género por el `{genre}` de la URL. Si no existe, devuelve **404** solo.
- **`$request->validated()`** → devuelve **solo** los campos que pasaron la validación.
  Nunca guardas basura del request.
- **`$genre->delete()`** → como hay `SoftDeletes`, solo marca `deleted_at`.

---

## Paso 5 — Las rutas

Abre `routes/api.php` y añade la línea mágica que registra **las 5 rutas** del CRUD:

```php
<?php

use App\Http\Controllers\GenreController;
use App\Http\Controllers\HealthController;
use Illuminate\Support\Facades\Route;

Route::get('/health', HealthController::class)->name('health');

// Las 5 rutas del CRUD de géneros, de una sola vez:
Route::apiResource('genres', GenreController::class);
```

`apiResource` es un atajo que genera estas rutas automáticamente:

| Método | URL | Acción del controlador |
|--------|-----|------------------------|
| GET | `/api/v1/genres` | `index` |
| POST | `/api/v1/genres` | `store` |
| GET | `/api/v1/genres/{genre}` | `show` |
| PUT/PATCH | `/api/v1/genres/{genre}` | `update` |
| DELETE | `/api/v1/genres/{genre}` | `destroy` |

> 📌 Recuerda: `apiPrefix: "api/v1"` en `bootstrap/app.php` añade `/api/v1` solo.

Compruébalo:

```bash
php artisan route:list --path=genres
```

---

## Paso 6 — Datos de ejemplo (Seeder)

Para no empezar con la tabla vacía, crea datos de prueba:

```bash
php artisan make:seeder GenreSeeder
```

`database/seeders/GenreSeeder.php`:

```php
public function run(): void
{
    $genres = [
        ['name' => 'Acción',         'description' => 'Películas con secuencias de acción.'],
        ['name' => 'Comedia',        'description' => 'Películas diseñadas para provocar risa.'],
        ['name' => 'Drama',          'description' => 'Historias serias sobre la condición humana.'],
        ['name' => 'Ciencia Ficción','description' => 'Futuros posibles y tecnología avanzada.'],
        // ... (en el archivo real hay 10 géneros)
    ];

    foreach ($genres as $genre) {
        Genre::firstOrCreate(                      // crea solo si no existe
            ['slug' => Str::slug($genre['name'])],
            array_merge($genre, ['is_active' => true])
        );
    }
}
```

`firstOrCreate` hace que el seeder sea **repetible**: si ya existe, no lo duplica.

Y regístralo en `database/seeders/DatabaseSeeder.php` para que se ejecute con `migrate --seed`:

```php
public function run(): void
{
    // ... (el usuario de ejemplo que ya venía)

    $this->call(GenreSeeder::class);
}
```

---

## Paso 7 — ¡Ponerlo en marcha!

```bash
# 1. Crear la tabla (si aún no migraste)
php artisan migrate

# 2. Cargar los 10 géneros de ejemplo
php artisan db:seed --class=GenreSeeder

# 3. Arrancar el servidor
php artisan serve
```

### Pruébalo con `curl`

En una segunda terminal:

```bash
# LISTAR todos
curl http://127.0.0.1:8000/api/v1/genres

# CREAR uno
curl -X POST http://127.0.0.1:8000/api/v1/genres \
     -H "Content-Type: application/json" \
     -H "Accept: application/json" \
     -d '{"name":"Terror","description":"Películas de miedo."}'

# VER uno (reemplaza 1 por un id real)
curl http://127.0.0.1:8000/api/v1/genres/1

# ACTUALIZAR uno
curl -X PUT http://127.0.0.1:8000/api/v1/genres/1 \
     -H "Content-Type: application/json" \
     -H "Accept: application/json" \
     -d '{"name":"Acción Aventura"}'

# BORRAR uno (soft delete)
curl -X DELETE http://127.0.0.1:8000/api/v1/genres/2 \
     -H "Accept: application/json"
```

### Ejemplo de respuesta real (gracias al trait `ApiResponse`)

```json
{
    "data": {
        "id": 1,
        "name": "Acción",
        "slug": "accion",
        "description": "Películas con secuencias de acción.",
        "is_active": true,
        "created_at": "2026-10-12T19:29:34.000000Z",
        "updated_at": "2026-10-12T19:29:34.000000Z"
    }
}
```

Y al crear:

```json
{
    "data": { "id": 11, "name": "Terror", "slug": "terror", ... },
    "message": "Género creado exitosamente."
}
```

> 🔎 Nota la **forma consistente**: siempre `{"data": ...}` y a veces `"message"`.
> Eso lo garantiza el trait `ApiResponse` de la guía 01. Sin él, cada endpoint
> tendría una forma distinta.

---

## Paso 8 — Las pruebas (automatizadas)

Una API seria se prueba. `tests/Feature/GenreTest.php` prueba los 5 endpoints con
**Pest** (el framework de tests que usa el proyecto):

```bash
php artisan test --filter=GenreTest
```

Ejemplo de un test:

```php
it('creates a genre', function () {
    $this->postJson('/api/v1/genres', [
        'name' => 'Acción',
        'description' => 'Películas de acción.',
        'is_active' => true,
    ])
        ->assertCreated()                            // comprueba el 201
        ->assertJsonPath('data.name', 'Acción')      // comprueba el JSON
        ->assertJsonPath('data.slug', 'accion');     // ¡el slug automático!

    $this->assertDatabaseHas('genres', [             // comprueba la BD
        'name' => 'Acción',
        'slug' => 'accion',
    ]);
});
```

`RefreshDatabase` (arriba del archivo) hace que cada test use una **base de datos limpia**,
sin ensuciar tu información real.

**Resultado en este proyecto: ✅ 5 tests pasando, 17 aserciones.**

---

## 🎓 Resumen: el flujo completo de una petición

Cuando haces `POST /api/v1/genres`, esto ocurre:

```
1. routes/api.php      → "eso es un POST /genres → GenreController@store"
2. Validate (middleware del grupo api)
3. StoreGenreRequest   → valida; si falla → 422 y se corta
4. GenreController::store
   ├─ usa el trait ApiResponse
   ├─ Genre::create($request->validated())
   │     └─ boot(): genera el slug solo
   └─ successResponse(new GenreResource($genre), '...', 201)
5. GenreResource       → decide qué campos van en el JSON
6. Respuesta 201 en JSON
```

Cada pieza tiene **una sola responsabilidad**:

| Pieza | Responsabilidad |
|-------|-----------------|
| **Ruta** | Mapear URL → acción |
| **FormRequest** | Validar datos de entrada |
| **Modelo** | Reglas de negocio + acceso a BD (+ slug automático) |
| **Resource** | Forma del JSON de salida |
| **Controller** | Orquestar todo y responder |
| **Trait `ApiResponse`** | Forma consistente de responder |

---

## ✅ Lo que aprendiste en esta sección

- ✅ Crear **modelo + migración** con `make:model -m`.
- ✅ Definir una **tabla** y sus columnas (`unique`, `nullable`, `softDeletes`).
- ✅ Configurar el **modelo**: `$fillable`, `$casts`, traits y **eventos de `boot()`**.
- ✅ Validar con **Form Requests** (`StoreGenreRequest`, `UpdateGenreRequest`).
- ✅ Dar forma al JSON con un **API Resource**.
- ✅ Escribir un **controlador CRUD** que usa el trait `ApiResponse`.
- ✅ Registrar **5 rutas** de una vez con `Route::apiResource`.
- ✅ Sembrar datos con un **Seeder** reutilizable.
- ✅ Probar todo con **Pest** (`php artisan test`).

---

## 🚀 Siguiente sección

Con Genre ya tienes un recurso completo. El siguiente paso lógico es **Movie (Película)**
y conectarla con Genre mediante una **relación** (muchas películas ↔ muchos géneros).
Allí aprenderás `belongsToMany` y las tablas pivote.

*(Próxima guía: "07 · Películas y relaciones", en la siguiente rama.)*
