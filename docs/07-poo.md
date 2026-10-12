# 07 · Programación orientada a objetos (POO)

> Carpeta de práctica: [`classes/`](../classes/) — 15 archivos + [`main.php`](../classes/main.php) (la demo).
> También existe un índice corto en [`classes/README.md`](../classes/README.md).

```bash
php classes/main.php
```

Ejecuta **todos** los ejemplos de una vez. Ese archivo es tu "modo demo".

---

## 0. La idea en 30 segundos

- **Clase (class):** el **plano**. Define qué datos y qué comportamientos tendrán las cosas.
- **Objeto (object/instance):** la **cosa construida** a partir del plano.
- **Propiedad:** un dato del objeto (`$name`).
- **Método:** una función que pertenece al objeto (`introduce()`).

```php
class User { ... }          // plano

$user = new User(...);      // objeto construido desde el plano
```

**Analogía:** la clase es el plano de un coche; cada objeto es un coche concreto con su color y su kilometraje.

---

## 1. Clase básica → `User.php`

```php
namespace App;

class User
{
    public string $name;      // propiedad
    public int $age;

    public function __construct(string $name, int $age)   // constructor
    {
        $this->name = $name;   // $this = la instancia actual
        $this->age = $age;
    }

    public function introduce(): string
    {
        return "Hello, my name is {$this->name} and I am {$this->age} years old.";
    }
}
```

Conceptos:

| Elemento | Qué es |
|----------|--------|
| `namespace App;` | "dirección" del archivo dentro del proyecto (evita choques de nombres) |
| `public` | accesible desde fuera |
| `__construct()` | se ejecuta **automáticamente** al hacer `new User(...)` |
| `$this` | referencia al objeto sobre el que estás trabajando ahora |

Uso:

```php
$user1 = new User('Philipos', 25);
echo $user1->introduce();
// Hello, my name is Philipos and I am 25 years old.
```

`->` significa **"el miembro de ese objeto"**.

---

## 2. Herencia → `Employee.php`

```php
class Employee extends User
{
    public string $position;

    public function __construct(string $name, int $age, string $position)
    {
        parent::__construct($name, $age);   // reutiliza el constructor del padre
        $this->position = $position;
    }

    public function introduce(): string
    {
        return parent::introduce() . " I work as a {$this->position}.";
        //          ↑ método del padre + texto extra
    }
}
```

```php
$employee = new Employee('Brandon', 30, 'Developer');
echo $employee->introduce();
// Hello, my name is Brandon and I am 30 years old. I work as a Developer.
```

- `extends` = **hereda** propiedades y métodos del padre.
- `parent::` llama a lo del padre.
- **Regla:** hereda cuando *es un tipo de...* (`Employee` **es un** `User`), no solo para reutilizar código.
  Para reutilizar código "suelto" existe el **trait** (punto 8).

---

## 3. Visibilidad → `BankAccount.php` + `PremiumBankAccount.php`

```php
class BankAccount
{
    private float $balance = 0.0;      // ❗ solo dentro de ESTA clase
    protected string $owner;           // esta clase + sus hijas

    public function __construct(string $owner) { $this->owner = $owner; }

    public function getOwner(): string { return $this->owner; }
    public function getBalance(): float { return $this->balance; }

    public function deposit(float $amount): void
    {
        if ($amount > 0) { $this->balance += $amount; }   // validación interna
    }

    public function withdraw(float $amount): bool
    {
        if ($amount > 0 && $amount <= $this->balance) {
            $this->balance -= $amount;
            return true;
        }
        return false;
    }
}
```

| Visibilidad | ¿Dónde se puede usar? |
|-------------|------------------------|
| `private` | solo en la clase donde se declara |
| `protected` | en la clase y en las que hereden de ella |
| `public` | en cualquier parte |

### ¿Por qué `private` si luego hay getters?

Porque **protege el estado**. `$balance` solo cambia pasando por `deposit()`/`withdraw()`, que validan. Nadie puede hacer `$account->balance = -9999`.

> 💡 Esta idea se llama **encapsulación**: expón lo mínimo necesario y guarda las reglas dentro.

### Clase hija usando `protected` → `PremiumBankAccount.php`

```php
class PremiumBankAccount extends BankAccount
{
    public function getPremiumLabel(): string
    {
        return "Premium account owner: {$this->owner}";   // ✅ puede leer $owner (es protected)
    }

    public function applyMonthlyBonus(float $bonus): void
    {
        if ($bonus > 0) {
            $this->deposit($bonus);   // reutiliza el método heredado
        }
    }
}
```

Si `$owner` fuera `private`, **esta línea daría error**.

Salida del demo:

```
Owner: Maria
Balance: 1000
Premium account owner: Lucas
Premium balance: 550
```

---

## 4. Miembros estáticos → `MathUtils.php`

```php
class MathUtils
{
    public const PI = 3.14159;                      // constante (no cambia nunca)

    public static function square(float $number): float
    {
        return $number * $number;
    }

    public static function circleArea(float $radius): float
    {
        return self::PI * self::square($radius);    // self = esta misma clase
    }
}
```

```php
echo MathUtils::square(5);          // 25  → se llama con :: , sin new
echo MathUtils::circleArea(3);      // 28.27431
```

| Normal (de instancia) | Estático (`static`) |
|---|---|
| `new Objeto()` y luego `$obj->metodo()` | `Clase::metodo()` sin crear nada |
| tiene `$this` y estado propio | no tiene `$this` |
| sirve para modelar entidades | sirve para utilidades puras (`MathUtils`, `Str`, `Hash`...) |

---

## 5. Interfaces → `PaymentMethod.php`

```php
interface PaymentMethod
{
    public function getProviderName(): string;

    public function pay(float $amount): PaymentStatus;
}
```

Una interfaz es **un contrato**: *"quien diga que es un `PaymentMethod`, está obligado a tener estos métodos"*.

- No contiene lógica, **solo firmas**.
- Si falta un método → **fatal error** al cargar la clase.
- En PHP una clase **solo puede extender de una clase**, pero **implementa tantas interfaces como quiera**.

---

## 6. Clase abstracta → `AbstractPaymentProcessor.php`

```php
abstract class AbstractPaymentProcessor implements PaymentMethod
{
    abstract public function getProviderName(): string;

    final public function pay(float $amount): PaymentStatus
    {
        // Lógica COMÚN a todos los procesadores (validación)
        if ($amount <= 0) {
            return PaymentStatus::Declined;
        }

        return $this->charge($amount) ? PaymentStatus::Approved : PaymentStatus::Declined;
    }

    abstract protected function charge(float $amount): bool;   // la parte VARIABLE
}
```

| Palabra clave | Significado |
|---------------|-------------|
| `abstract class` | no se puede crear con `new`; es una base |
| `abstract function` | las hijas **deben** implementarla |
| `final function` | nadie puede sobrescribir `pay()` |

**La idea clave:** el padre decide **el "qué"** (el flujo: validar → cobrar → aprobar) y las hijas deciden **el "cómo"** (`charge()`).

---

## 7. Implementaciones concretas

`StripePaymentProcessor.php`:

```php
class StripePaymentProcessor extends AbstractPaymentProcessor
{
    public function getProviderName(): string { return 'Stripe'; }

    protected function charge(float $amount): bool
    {
        return $amount <= 5000;      // regla propia de Stripe (ejemplo didáctico)
    }
}
```

`PaypalPaymentProcessor.php`:

```php
class PaypalPaymentProcessor extends AbstractPaymentProcessor
{
    public function getProviderName(): string { return 'PayPal'; }

    protected function charge(float $amount): bool
    {
        return $amount <= 2500;      // límite distinto
    }
}
```

```php
$stripe = new StripePaymentProcessor();
$paypal = new PaypalPaymentProcessor();

echo $stripe->pay(1200)->value;   // approved (1200 ≤ 5000)
echo $paypal->pay(3000)->value;   // declined  (3000 > 2500)
```

> 🔑 **Este es el patrón más importante de la POO**: cambiar de proveedor **sin tocar** el código que llama a `pay()`. Eso es **polimorfismo**.

---

## 8. Enums → `PaymentStatus.php`

```php
enum PaymentStatus: string
{
    case Pending  = 'pending';
    case Approved = 'approved';
    case Declined = 'declined';
}
```

```php
echo PaymentStatus::Approved->value;   // "approved"
```

**Problema que resuelve:** evitar `"aprobado"`, `"Aprobado"`, `"aprobado "` (con espacio) por todo el código.
El enum define un **conjunto cerrado de valores válidos** y el editor te autocompleta.

Ejemplo de uso en `Order.php`:

```php
public function markAsApproved(): void
{
    $this->status = PaymentStatus::Approved;   // imposible escribir un valor inválido
    $this->touch();
}
```

También se puede usar en `match`:

```php
$message = match ($order->status) {
    PaymentStatus::Pending  => "Esperando pago",
    PaymentStatus::Approved => "Pagado",
    PaymentStatus::Declined => "Rechazado",
};
```

---

## 9. Traits → `Timestampable.php`

```php
trait Timestampable
{
    protected DateTimeImmutable $createdAt;
    protected ?DateTimeImmutable $updatedAt = null;   // ? = puede ser null

    public function markCreated(): void { $this->createdAt = new DateTimeImmutable(); }
    public function touch(): void        { $this->updatedAt = new DateTimeImmutable(); }
    public function getCreatedAt(): DateTimeImmutable { return $this->createdAt; }
    public function getUpdatedAt(): ?DateTimeImmutable { return $this->updatedAt; }
}
```

**Trait = "pegar este trozo de comportamiento aquí".**

```php
class Order
{
    use Timestampable;    // ← como si copiaras las 4 funciones dentro de Order
    ...
}
```

| Herencia (`extends`) | Trait (`use`) |
|---|---|
| crea una **relación padre/hijo** | **copia** código suelto |
| una sola por clase | **tantos como quieras** |
| modela "es un..." | modela "puede hacer..." |

Los traits resuelven el problema de *"necesito esta funcionalidad en 5 clases que no comparten padre"*.

---

## 10. Clase `final` e `readonly`

`InvoicePrinter.php`:

```php
final class InvoicePrinter        // final = NO puede ser extendida
{
    public function format(Invoice $invoice): string
    {
        return "Invoice {$invoice->number} | Amount: {$invoice->amount} | Status: {$invoice->status->value}";
    }
}
```

`Invoice.php`:

```php
readonly class Invoice            // readonly = propiedades inmutables
{
    public function __construct(
        public string $number,
        public float $amount,
        public PaymentStatus $status
    ) {
    }
}
```

> **Nota de versión:** en PHP **8.1** `readonly` se aplicaba a **propiedades** (`public readonly string $number;`); desde **PHP 8.2** también se puede aplicar a la **clase entera**, como aquí.

```php
$invoice = new Invoice('INV-9001', 499.95, PaymentStatus::Approved);
$invoice->amount = 0;    // ❌ Error: no se puede modificar después de crearlo
```

| `final class` | `readonly` |
|---|---|
| impide **heredar** | impide **modificar** |
| "esto está terminado, no lo extiendas" | "esto es un dato inmutable" |

---

## 11. Todo junto en `Order.php`

```php
class Order
{
    use Timestampable;                                   // trait

    public function __construct(
        public string $number,
        public float $total,
        public PaymentStatus $status = PaymentStatus::Pending   // enum + valor por defecto
    ) {
        $this->markCreated();
    }

    public function markAsApproved(): void
    {
        $this->status = PaymentStatus::Approved;
        $this->touch();
    }
}
```

```php
$order = new Order('ORD-1001', 199.99);
echo $order->getCreatedAt()->format('Y-m-d H:i:s');
$order->markAsApproved();
echo $order->status->value;   // approved
```

**Una clase real suele combinar varias herramientas**, como aquí: trait + enum + constructor con propiedades promocionadas.

---

## 12. Guía rápida: ¿qué concepto uso?

| Necesidad | Concepto |
|-----------|----------|
| Agrupar datos y acciones | **clase** |
| Reutilizar "es un tipo de..." | **herencia** |
| Ocultar el interior y validar | **`private` / `protected`** |
| Utilidad sin crear objetos | **`static`** |
| Obligar a tener ciertos métodos | **interface** |
| Compartir lógica base y dejar partes libres | **abstract class** |
| Compartir código suelto entre clases | **trait** |
| Valores fijos y seguros | **enum** |
| Impedir herencia | **final** |
| Datos inmutables | **readonly** |

---

## 13. Estructura de `main.php`

```php
require_once __DIR__ . '/User.php';     // carga cada clase
...
use App\User;                           // alias para no escribir App\ siempre

$user1 = new User('Philipos', 25);
echo $user1->introduce();
```

| Elemento | Para qué |
|----------|----------|
| `require_once __DIR__ . '/...'` | incluye el archivo **una sola vez** (`__DIR__` = carpeta del script) |
| `use App\User;` | importa la clase del namespace |
| `echo` | muestra el resultado |

> En proyectos reales **no** usas `require_once`: lo hace **Composer** con el *autoloader* (PSR-4). Verás eso en [08 · Laravel](08-laravel.md).

---

## ✅ Ejercicios

1. Crea la clase `Product` con `name`, `price` y un método `applyDiscount(10)`.
2. Crea `SavingsAccount extends BankAccount` que aplique un 2% de interés.
3. Convierte una lista de strings `'pending','approved'` en un `enum`.
4. Crea un trait `Loggable` con `log(string $msg)` y úsalo en dos clases.
5. Dime de `final`, `readonly`, `abstract` y `interface`: **cuál impediría qué** y por qué.

---

**Siguiente:** [08 · Tu primera app con Laravel](08-laravel.md)
