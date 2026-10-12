# 07 · Repository Pattern y Testing (sección "Género" v2)

> 🎯 **Objetivo de esta sección:** refactorizar el recurso **Género** aplicando el
> **Repository Pattern**, y dejarlo **bien testeado** con Pest y factories.
>
> Partimos del CRUD que ya teníamos (guía 06) y lo llevamos al siguiente nivel:
> controlador delgado, datos desacoplados y pruebas automatizadas.
>
> 📖 Basado en: [Testing](https://laravel.com/docs/13.x/testing) ·
> [Factories](https://laravel.com/docs/13.x/eloquent-factories) ·
> [Container / Dependency Injection](https://laravel.com/docs/13.x/container)

---

## 🧠 ¿Qué es el Repository Pattern (y por qué molestar)?

**Repository** = "repositorio". Es una capa extra entre tu controlador y Eloquent
que **esconde el acceso a los datos** detrás de una interfaz.

### El problema sin repositories

En la guía 06, el controlador llamaba a Eloquent directamente:

```php
$genres = Genre::all();          // el controlador depende de Eloquent
Genre::create([...]);            // si cambio la BD, toco el controlador
```

Esto **acopla** el controlador a Eloquent. Si mañana lees los géneros de una API
externa, de caché o de un archivo, tendrías que **reescribir el controlador**. Y
para testearlo, necesitas una base de datos real.

### La solución con repositories

```
 ANTES:  Controlador ──────────► Eloquent (Genre::all())

 AHORA:  Controlador ──► Interfaz (GenreRepositoryInterface)
                              │
                              └─► Implementación (GenreRepository → Eloquent)
```

El controlador pide la **interfaz** (el "qué"), no la implementación (el "cómo").
Ventajas:

- **Desacoplo:** cambio la implementación sin tocar el controlador.
- **Tests:** puedo "falsear" (mock) el repositorio y probar el controlador sin BD.
- **Claridad:** el controlador queda delgado; la lógica de datos vive en un solo lugar.

---

## 🗺️ El mapa de archivos de esta sección

```
app/
├── Http/Controllers/GenreController.php        ← ahora pide la INTERFAZ (delgado)
├── Providers/RepositoryServiceProvider.php     ← "pegamento": hace el bind
└── Repositories/
    ├── Contracts/
    │   ├── BaseRepositoryInterface.php         ← contrato CRUD genérico
    │   └── GenreRepositoryInterface.php        ← contrato específico de Genre
    └── Eloquent/
        ├── BaseRepository.php                  ← implementación abstracta (CRUD)
        └── GenreRepository.php                 ← implementación concreta (Genre)

database/factories/GenreFactory.php             ← fábrica de datos de prueba
tests/Feature/GenreTest.php                     ← las pruebas (9 tests)
docs/api-requests.http                          ← requests de prueba para la API
```

Vamos pieza por pieza.

---

## Paso 1 — Los contratos (Interfaces)

En `app/Repositories/Contracts/` hay dos interfaces. Una **interfaz** solo declara
*métodos sin cuerpo*: dice **qué** se puede hacer, no **cómo**.

### `BaseRepositoryInterface` — el CRUD genérico

Define las 5 operaciones que cualquier recurso necesita:

```php
interface BaseRepositoryInterface
{
    public function all(): Collection;                    // listar todos
    public function findOrFail(int $id): Model;           // buscar por id (o 404)
    public function create(array $data): Model;           // crear
    public function update(Model $model, array $data): Model;  // actualizar
    public function delete(Model $model): void;           // borrar
}
```

> 💡 Este contrato se puede **reutilizar** para Movie, User, etc. No hay que
> reescribirlo por cada recurso.

### `GenreRepositoryInterface` — el contrato de Genre

Hereda todo lo de arriba y deja espacio para métodos **propios de Genre**:

```php
interface GenreRepositoryInterface extends BaseRepositoryInterface
{
    // Ej: public function findBySlug(string $slug): ?Genre;
}
```

Así separas "lo que todos comparten" (CRUD base) de "lo que solo hace Genre".

---

## Paso 2 — La implementación concreta

### `BaseRepository` (abstracto) — el CRUD reutilizable

Implementa el CRUD una sola vez, de forma **genérica**:

```php
abstract class BaseRepository implements BaseRepositoryInterface
{
    // El constructor recibe CUALQUIER modelo por inyección de dependencias.
    public function __construct(protected Model $model) {}

    public function all(): Collection {
        return $this->model->all();        // delega en Eloquent
    }

    public function create(array $data): Model {
        return $this->model->create($data);
    }

    public function findOrFail(int $id): Model {
        return $this->model->findOrFail($id);   // si no existe → 404
    }

    public function update(Model $model, array $data): Model {
        $model->update($data);
        return $model->refresh();          // devuelve el modelo actualizado
    }

    public function delete(Model $model): void {
        $model->delete();                  // con SoftDeletes, solo marca deleted_at
    }
}
```

Como es `abstract`, **no se instancia directamente**; solo sirve de base.

### `GenreRepository` (concreto) — el de Genre

Su único trabajo es decirle al padre **con qué modelo trabajar**:

```php
class GenreRepository extends BaseRepository implements GenreRepositoryInterface
{
    public function __construct(Genre $model)
    {
        parent::__construct($model);   // "trabaja con el modelo Genre"
    }
}
```

Así `GenreRepository` hereda todo el CRUD sin reescribir nada. Cuando quieras un
recurso nuevo (Movie), creas su `MovieRepository` de 10 líneas.

---

## Paso 3 — El "pegamento": RepositoryServiceProvider

En `app/Providers/RepositoryServiceProvider.php` se hace el **bind** (enlace):

```php
public function register(): void
{
    // "Cuando alguien pida la INTERFAZ, entregá la IMPLEMENTACIÓN concreta."
    $this->app->bind(GenreRepositoryInterface::class, GenreRepository::class);
}
```

Y **muy importante**: hay que registrarlo en `bootstrap/providers.php`, si no, no
se ejecuta:

```php
use App\Providers\RepositoryServiceProvider;

return [
    AppServiceProvider::class,
    RepositoryServiceProvider::class,   // ← sin esta línea, todo falla
];
```

> ⚠️ **Trampa clásica:** si agregás el provider a la lista pero olvidás el `use`
> arriba, PHP no encuentra la clase y el `bind` nunca se aplica. Te va a tirar:
> `Target [...] is not instantiable`.

> ⚠️ **Otra trampa:** si ya tenías el servidor o tests corriendo y modificás
> `bootstrap/providers.php` o `config/*.php`, corré `php artisan config:clear`.
> Si no, Laravel usa la versión cacheada y da errores raros.

---

## Paso 4 — El controlador delgado

Ahora `GenreController` **no llama a Eloquent**: pide la interfaz por su constructor
(inyección de dependencias) y delega:

```php
class GenreController extends Controller
{
    use ApiResponse;

    // El contenedor entrega un GenreRepository automáticamente (por el bind).
    public function __construct(private GenreRepositoryInterface $genres) {}

    public function index()
    {
        $genres = $this->genres->all();   // delega en el repositorio

        return $this->successResponse(GenreResource::collection($genres));
    }

    public function store(StoreGenreRequest $request)
    {
        $genre = $this->genres->create($request->validated());

        return $this->successResponse(new GenreResource($genre), 'Género creado exitosamente.', 201);
    }

    // ... show, update, destroy iguales: piden datos vía $this->genres
}
```

Fíjate que `show` y `update`/`destroy` reciben `Genre $genre` por **Route Model
Binding** (Eloquent sigue cargando el modelo), pero la **escritura** pasa por el
repositoritorio.

---

## Paso 5 — Datos de prueba: el GenreFactory

Para testear necesitamos datos. En vez de crearlos a mano, usamos una **Factory**
(`database/factories/GenreFactory.php`), que inventa datos falsos y realistas:

```php
public function definition(): array
{
    $name = $this->faker->unique()->words(3, true);   // nombre único de mentira

    return [
        'name' => $name,
        'description' => $this->faker->sentence(),
        'slug' => Str::slug($name),
        'is_active' => true,
    ];
}

// Estado "variante": crea un género inactivo
public function inactive(): static
{
    return $this->state(['is_active' => false]);
}
```

Uso:

```php
Genre::factory()->count(3)->create();          // 3 géneros activos
Genre::factory()->inactive()->create();        // 1 género inactivo
```

Se activa gracias al trait `HasFactory` del modelo `Genre` (guía 01).

---

## Paso 6 — Las pruebas (Pest)

Las pruebas viven en `tests/Feature/GenreTest.php`. Usan **Pest** (el framework de
tests del proyecto) y `RefreshDatabase` (cada test corre sobre una BD limpia).

```bash
php artisan test --filter=GenreTest
```

### Las 9 pruebas y qué comprueban

| Test | Qué verifica |
|------|--------------|
| `lists all genres` | 200, cuántos vienen y la **estructura** del JSON |
| `shows a genre by id` | 200 y que devuelva el género correcto |
| `returns 404 when the genre does not exist` | id inexistente → **404** |
| `creates a genre` | **201**, slug automático, mensaje, y que quedó en la BD |
| `validates the name is required when creating` | sin `name` → **422** |
| `validates the name is unique when creating` | nombre repetido → **422** |
| `updates a genre` | 200, slug regenerado, cambios en la BD |
| `allows a genre to keep its own name when updating` | editar sin renombrar **no** falla por duplicado |
| `deletes a genre` | 200 y `assertSoftDeleted` (borrado suave) |

### Ejemplo anotado

```php
it('creates a genre', function () {
    $this->postJson('/api/v1/genres', [
        'name' => 'Acción',
        'description' => 'Películas de acción.',
        'is_active' => true,
    ])
        ->assertCreated()                          // HTTP 201
        ->assertJsonPath('data.slug', 'accion')    // el slug se generó solo
        ->assertJsonPath('message', 'Género creado exitosamente.');

    $this->assertDatabaseHas('genres', [           // quedó guardado en la BD
        'name' => 'Acción',
        'slug' => 'accion',
    ]);
});
```

### Aserciones útiles (mini guía de bolsillo)

| Aserción | Significado |
|----------|-------------|
| `assertOk()` | HTTP 200 |
| `assertCreated()` | HTTP 201 |
| `assertNotFound()` | HTTP 404 |
| `assertUnprocessable()` | HTTP 422 (validación) |
| `assertJsonPath('data.name', 'X')` | el JSON tiene ese valor en esa ruta |
| `assertJsonStructure([...])` | el JSON tiene esa "forma" |
| `assertJsonValidationErrors('name')` | la validación falló en ese campo |
| `assertDatabaseHas('genres', [...])` | existe esa fila en la BD |
| `assertSoftDeleted('genres', [...])` | la fila está marcada como borrada |

---

## 🧪 Probar la API a mano

Además de los tests, `docs/api-requests.http` trae requests listos para probar la
API en vivo (con la extensión REST Client de VS Code):

```bash
php artisan serve                                  # arrancar en http://localhost:8000
# y abrir docs/api-requests.http y pulsar "Send Request" en cada bloque
```

Incluye el health check, las 5 operaciones CRUD, y un request **inválido** para
ver el 422 de validación en acción.

---

## 🎓 Resumen: por qué esto es mejor

```
 ANTES (guía 06)                     AHORA (guía 07)
 ─────────────────                   ─────────────────
 Controlador                          Controlador (delgado)
    │                                   │ pide interfaz
    ▼                                   ▼
 Eloquent (Genre::...)               GenreRepositoryInterface
                                       │ (bind en el Provider)
                                       ▼
                                    GenreRepository (Eloquent)
```

| Concepto | Qué te aporta |
|----------|---------------|
| **Interfaz (Contract)** | Define el "qué", sin atar a ningún "cómo" |
| **BaseRepository** | CRUD genérico reutilizable (abstract) |
| **GenreRepository** | Implementación concreta para Genre |
| **ServiceProvider + bind** | El pegamento que le dice al contenedor qué entregar |
| **Inyección de dependencias** | El controlador pide la interfaz y no sabe de la BD |
| **Factory** | Datos de prueba falsos y rápidos |
| **Tests (Pest)** | Comprueban que todo sigue funcionando tras cada cambio |

---

## ✅ Lo que aprendiste en esta sección

- ✅ Qué es el **Repository Pattern** y por qué desacopla el acceso a datos.
- ✅ Crear **interfaces (contracts)** y **implementaciones** concretas.
- ✅ La clase **abstracta** `BaseRepository` para reutilizar el CRUD.
- ✅ El **bind** en un `ServiceProvider` y registrarla en `bootstrap/providers.php`.
- ✅ **Inyectar** el repositorio en el controlador (controlador delgado).
- ✅ Crear **datos de prueba** con `GenreFactory` y estados (`inactive()`).
- ✅ Escribir **tests de feature** con Pest: happy path, validación (422) y 404.
- ✅ Aserciones clave: `assertCreated`, `assertJsonPath`, `assertDatabaseHas`, `assertSoftDeleted`.

---

## 🚀 Siguiente sección

Con el patrón aprendido y Genre bien testeado, el siguiente paso es hacer la API de
Géneros más completa: **búsqueda, filtros y restauración** (deshacer el soft delete).

*(Próxima guía: "08 · Géneros: buscar, filtrar y restaurar", en la siguiente rama.)*
