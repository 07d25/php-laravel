# 01 · Traits (¿qué es y para qué sirve?)

> 📖 Doc oficial: [Eloquent — Traits](https://laravel.com/docs/13.x/eloquent) ·
> [PHP Traits](https://www.php.net/manual/en/language.oop5.traits.php)
> ⭐ Concepto clave en Laravel: los verás TODO el rato.

---

## 🎯 Respuesta corta

Un **Trait** es un fragmento de código con métodos que puedes **"pegar" dentro de
cualquier clase** para reutilizarlo, **sin heredar** de otra clase.

Es como un **copia-pega con superpoderes**: defines los métodos una sola vez y
varias clases los usan como si fueran suyos.

---

## 🤔 ¿Por qué existe? El problema que resuelve

En PHP (y en la POO) existe la **herencia**: una clase hija hereda los métodos de una
clase madre. Pero la herencia solo funciona **en línea recta** (una sola clase padre).

Imagina que tienes varias clases sin relación entre sí y todas necesitan lo mismo,
por ejemplo `successResponse()` y `errorResponse()`:

- ❌ **Herencia:** no puedes, porque cada clase ya tiene su propio padre.
- ❌ **Copiar y pegar:** si cambias algo, tienes que cambiarlo en 10 sitios.
- ✅ **Trait:** defines los métodos una vez y los "mezclas" en cada clase que los necesite.

```
   Herencia (árbol)              Trait (mezcla)

   Modelo                       Trait ApiResponse
     ↑                                ↓  ↓  ↓
   User                       UserController  AuthController  HealthController
   (hereda de Model)          (lo usa)         (lo usa)         (lo usa)
```

La herencia es **vertical** (de arriba abajo). El trait es **horizontal** (compartido
entre clases que no son parientes).

---

## 🧱 Sintaxis básica de PHP

Se declara con la palabra clave `trait` y se usa con `use` **dentro** de la clase:

```php
// 1. Definir el trait
trait Saludo
{
    public function saludar(string $nombre): string
    {
        return "¡Hola, {$nombre}!";
    }
}

// 2. Usarlo en una clase (mezclar sus métodos)
class Persona
{
    use Saludo;   // ahora Persona TIENE el método saludar()
}

$persona = new Persona();
echo $persona->saludar('Ana');  // ¡Hola, Ana!
```

La clase `Persona` **no hereda de nada**, pero tiene el método `saludar()` como si lo
hubiera escrito ella misma. Eso es todo un trait.

---

## 📦 El Trait real de este proyecto: `ApiResponse`

Mira `app/Traits/ApiResponse.php`:

```php
<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function successResponse(mixed $data, string $message = '', int $status = 200): JsonResponse
    {
        $payload = ['data' => $data];
        if ($message != '') {
            $payload['message'] = $message;
        }

        return response()->json($payload, $status);
    }

    protected function errorResponse(string $message, int $status, mixed $errors = null): JsonResponse
    {
        $payload = ['message' => $message];
        if ($errors != null) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $status);
    }
}
```

**Qué hace:** garantiza que TODA respuesta de la API tenga el **mismo formato**:

```json
// Éxito (successResponse)
{ "data": { ... }, "message": "Health check" }

// Error (errorResponse)
{ "message": "No autorizado", "errors": { ... } }
```

Ese es el gran motivo para usar traits: **consistencia**. En vez de construir el JSON
a mano en cada controlador, lo haces una vez aquí.

---

## 🔧 Cómo se usa en una clase

En `app/Http/Controllers/HealthController.php`:

```php
<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class HealthController extends Controller
{
    use ApiResponse;   // ← "pego" aquí los métodos del trait

    public function __invoke(): JsonResponse
    {
        // successResponse() viene del trait ApiResponse
        return $this->successResponse([
            'status' => 'OK',
            'app'    => 'MovieAPI',
        ], 'Health check');
    }
}
```

Línea por línea:
1. `use App\Traits\ApiResponse;` (arriba) → importa el trait para poder referirme a él.
2. `use ApiResponse;` (dentro de la clase) → **lo mezcla** en la clase.
3. `$this->successResponse(...)` → llama al método que el trait le prestó.

Si mañana creas `MovieController` o `AuthController`, solo añades `use ApiResponse;`
y ya tendrán `successResponse()` y `errorResponse()` sin repetir código.

---

## 🧠 Traits famosos de Laravel (los vas a ver todo el tiempo)

Laravel está **lleno** de traits. Cuando abras un modelo verás cosas así:

```php
class User extends Authenticatable
{
    use HasFactory, Notifiable;   // ← ¡dos traits de Laravel!
    // ...
}
```

| Trait | De dónde viene | Qué aporta |
|-------|----------------|------------|
| `HasFactory` | `Illuminate\Database\Eloquent\Factories` | Crea el método `User::factory()` para generar datos de prueba |
| `Notifiable` | `Illuminate\Notifications\Notifiable` | Permite enviar notificaciones (mail, SMS...) al modelo |
| `SoftDeletes` | `Illuminate\Database\Eloquent\SoftDeletes` | "Borra" filas marcándolas en vez de eliminarlas |
| `HasUuids` | `Illuminate\Database\Eloquent\Concerns\HasUuids` | Usa UUIDs como clave primaria en vez de números |
| `AuthorizesRequests` | `Illuminate\Foundation\Auth\Access\AuthorizesRequests` | Añade el método `authorize()` para permisos |

Los traits de Laravel para modelos viven en `Illuminate\Database\Eloquent\Concerns\`
(en la doc oficial se les llama "Concerns").

> 💡 **Truco para explorar:** abre un trait de Laravel y léelo. Son clases pequeñas y
> muy comentadas. Por ejemplo `vendor/laravel/framework/src/Illuminate/Database/
> Eloquent/Concerns/HasUuids.php`. Es la mejor forma de aprender "a la Laravel".

---

## ⚖️ Herencia vs Trait vs Composición

| | Herencia (`extends`) | Trait (`use`) | Composición (tener un objeto) |
|---|---|---|---|
| Relación | "es un tipo de" | "tiene estas capacidades" | "tiene un ayudante" |
| Cuándo usarlo | Modelo → User, Animal → Perro | Compartir utilidades sin herencia | Delegar trabajo a otra clase |
| Ejemplo Laravel | `User extends Authenticatable` | `use HasFactory` | Inyectar un servicio |

No hay uno "mejor" que otro: se usan para cosas distintas. Laravel usa los tres.

---

## ⚠️ Cuidado: ¿y si dos traits traen el mismo método?

PHP no sabe cuál elegir y lanza un **error fatal**. Se resuelve con `insteadof`:

```php
class MiClase
{
    use TraitA, TraitB {
        TraitA::saludar insteadof TraitB;  // usa la versión de TraitA
        TraitB::saludar as saludarB;       // y guarda la de TraitB con otro nombre
    }
}
```

Es raro, pero algún día lo verás en código ajeno. Ya sabes qué significa.

---

## ✅ Reglas para usar traits como un pro

1. **Un trait, una responsabilidad.** `ApiResponse` solo maneja respuestas. No metas lógica de BD ahí.
2. **Nómbralos en plural o como capacidades:** `ApiResponse`, `HasFactory`, `Notifiable`.
3. **Los métodos `protected` son comunes:** si solo lo usan las clases que lo mezclan, no expongas `public`.
4. **Documenta con `@method`** si el trait aporta métodos que las clases usan mucho.
5. **Si solo una clase lo usa,** quizás sea mejor meterlo directamente en esa clase.

---

## 🧪 Prueba rápida

Abre `tinker` (un REPL de Laravel) y comprueba que el trait funciona:

```bash
php artisan tinker
```

```php
>>> $c = new App\Http\Controllers\HealthController();
>>> method_exists($c, 'successResponse');   // true  → el trait pegó el método
>>> $c->__invoke();
// => JsonResponse {"data":{"status":"OK","app":"MovieAPI"},"message":"Health check"}
```

---

## 💡 Para recordar

- Un **Trait** = método reutilizable que se **mezcla** en clases con `use`.
- Resuelve el problema de **compartir código entre clases sin herencia**.
- Laravel los usa muchísimo (`HasFactory`, `Notifiable`, `SoftDeletes`...).
- En este proyecto, `ApiResponse` unifica el formato JSON de la API.
- Cuidado con nombres duplicados entre traits (se resuelve con `insteadof`).

**Siguiente:** [02 · Controladores →](02-controladores.md)
