# 04 · Modelos y Eloquent (la base de datos en objetos)

> 📖 Doc oficial: [Eloquent: Getting Started](https://laravel.com/docs/13.x/eloquent) ·
> [Migrations](https://laravel.com/docs/13.x/migrations)

---

## 🎯 ¿Qué es Eloquent?

**Eloquent** es el **ORM** (Object-Relational Mapping) de Laravel. Traduce entre tu
código PHP (objetos) y la base de datos (tablas y filas).

- Una **tabla** → una **clase** (el Modelo).
- Una **fila** → un **objeto** de esa clase.
- Una **columna** → una **propiedad** del objeto.

```php
$users = User::all();        // SELECT * FROM users  → colección de objetos User
$user  = User::find(1);      // SELECT * WHERE id=1  → un objeto User
echo $user->name;            // lee la columna "name"
```

Gracias a Eloquent, escribes **PHP**, no SQL a mano.

---

## 📦 El Modelo real de este proyecto: `User`

Mira `app/Models/User.php`:

```php
<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }
}
```

Desglosemoslo:

| Parte | Qué significa |
|-------|---------------|
| `extends Authenticatable` | Hereda del modelo "base" para usuarios (login, sesiones, contraseñas) |
| `use HasFactory, Notifiable` | Dos **traits** (guía 01): fábricas de datos y notificaciones |
| `#[Fillable([...])]` | Atributo PHP 8: permite esos campos al hacer `create()`/`update()` masivos |
| `#[Hidden([...])]` | Esos campos **nunca** aparecen al convertir el modelo a JSON/array |
| `casts(): array` | Convierte columnas a tipos PHP (una fecha a `DateTime`, la contraseña a hash) |

> 💡 **Novedad de Laravel 12/13:** se usan **atributos PHP** (`#[Fillable]`, `#[Hidden]`)
> en lugar de propiedades (`protected $fillable = [...]`). Es la forma moderna y es
> exactamente lo que usa este proyecto.

---

## 🔐 Mass Assignment: por qué existe `Fillable`

**Asignación masiva** = pasar un array entero a `create()` o `update()`:

```php
User::create([
    'name'  => 'Ana',
    'email' => 'ana@ejemplo.com',
]);
```

El peligro: si un usuario malicioso envía un campo extra como `is_admin = true` y tú
pasas el request entero a `create()`, podría **autoadministrarse**. Por eso Eloquent
**bloquea todo por defecto** y solo deja pasar lo que declares en `Fillable`:

```php
#[Fillable(['name', 'email', 'password'])]   // solo estos campos se pueden asignar masivamente
```

Si intentas `User::create(['name' => 'x', 'is_admin' => true])`, el `is_admin`
se **ignora**. Protección automática. 🛡️

---

## 🗄️ Las tablas: Migrations

El **Modelo** necesita una **tabla**. Laravel crea las tablas con **migraciones**
(archivos que "dibujan" la estructura de la BD de forma versionada).

En este proyecto: `database/migrations/0001_01_01_000000_create_users_table.php`

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();                              // clave primaria autoincremental
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->rememberToken();
    $table->timestamps();                      // created_at y updated_at
});
```

`$table->timestamps()` crea `created_at` y `updated_at`, que **Eloquent rellena solo**
cada vez que creas o actualizas un registro.

Comandos clave:

```bash
php artisan migrate              # aplica las migraciones pendientes
php artisan make:migration create_movies_table   # crea una nueva migración
```

### Convención de nombres

Eloquent **adivina** la tabla a partir del nombre de la clase, en snake_case y plural:

| Modelo | Tabla esperada |
|--------|----------------|
| `User` | `users` |
| `Movie` | `movies` |
| `AirTrafficController` | `air_traffic_controllers` |

Si no cumple la convención, se indica con el atributo `#[Table('otro_nombre')]`.

---

## 🔎 Consultas más comunes

```php
// Leer
$movies = Movie::all();                          // todas
$movie  = Movie::find(1);                        // por id
$movie  = Movie::where('year', '>', 2000)->first();  // con condiciones
$movie  = Movie::findOrFail(1);                  // lanza excepción 404 si no existe

// Crear
$movie = Movie::create(['title' => 'Inception', 'year' => 2010]);

// Actualizar
$movie->year = 2011;
$movie->save();                                   // o:
$movie->update(['year' => 2011]);

// Borrar
$movie->delete();
```

### Relaciones (tablas que se conectan)

```php
class Movie extends Model
{
    public function genres()   // una película tiene muchas categorías
    {
        return $this->belongsToMany(Genre::class);
    }

    public function director() // una película tiene un director
    {
        return $this->belongsTo(Director::class);
    }
}

// Uso:
$movie = Movie::with('genres')->find(1);   // carga la película Y sus categorías
echo $movie->genres->first()->name;
```

---

## ✅ Reglas para modelos como un pro

1. **Singular y PascalCase:** `Movie`, no `Movies`.
2. **Los modelos van en `app/Models`.**
3. **Usa `Fillable`** para protegerte de la asignación masiva.
4. **`Hidden`** para contraseñas y tokens sensibles.
5. **La lógica de BD vive aquí**, no en los controladores (controladores delgados).
6. **`casts()`** para convertir columnas a tipos PHP útiles.

---

## 🧪 Prueba rápida

```bash
php artisan tinker
```

```php
>>> App\Models\User::count();     // número de usuarios
>>> App\Models\User::first()?->name;
```

---

## 💡 Para recordar

- **Eloquent** = ORM: mapea tablas ↔ clases y filas ↔ objetos.
- Un **Modelo** representa una tabla y vive en `app/Models`.
- **Migraciones** crean las tablas (`php artisan migrate`).
- **`Fillable`/`Hidden`** protegen la asignación masiva y la serialización.
- **Convención:** la clase `Movie` usa la tabla `movies`.
- Las **relaciones** conectan modelos entre sí.

**Siguiente:** [05 · Middleware →](05-middleware.md)
