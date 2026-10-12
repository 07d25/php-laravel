# PHP Classes Examples

Este directorio contiene ejemplos prácticos de programación orientada a objetos en PHP.
La idea es que cada archivo muestre un concepto concreto con nombres de clases y métodos en inglés,
pero con comentarios explicativos en español.

> 📖 **¿Nuevo en POO?** Empieza por la guía explicada paso a paso: [docs/07-poo.md](../docs/07-poo.md).

## Cómo ejecutar la demo

El archivo [`main.php`](./main.php) carga todas las clases y muestra ejemplos en consola.

```bash
php classes/main.php
```

> ⚠️ **Este es el único archivo que se ejecuta.** Los demás son *clases*: solo definen cosas y
> esperan a que alguien las use. Si ejecutas `php classes/Employee.php` por separado verás un error
> como `Class "App\User" not found`, porque `Employee` hereda de `User` y nadie la ha cargado.
> Es normal: elige el concepto → léelo → pruébalo desde [`main.php`](./main.php).

## Archivos y conceptos

### `User.php`

Ejemplo de una clase básica.

- Define propiedades como `name` y `age`
- Tiene un constructor para inicializar el objeto
- Tiene un método de instancia (`introduce`) que usa el estado del objeto

### `Employee.php`

Ejemplo de herencia.

- `Employee` extiende de `User`
- Reutiliza propiedades y comportamiento de la clase padre
- Sobrescribe `introduce()` para añadir información extra

### `BankAccount.php`

Ejemplo de visibilidad de miembros.

- `private $balance` solo puede usarse dentro de la clase
- `protected $owner` puede usarse en la clase y en sus hijas
- `public` expone métodos seguros para interactuar con el objeto

### `PremiumBankAccount.php`

Clase hija que demuestra el uso de `protected`.

- Accede a la propiedad heredada `$owner`
- Añade comportamiento extra con `applyMonthlyBonus()`

### `MathUtils.php`

Ejemplo de miembros estáticos.

- No hace falta crear una instancia para usar la clase
- `MathUtils::square()` se llama directamente sobre la clase
- `MathUtils::circleArea()` reutiliza otra función estática

### `PaymentMethod.php`

Ejemplo de interfaz.

- Define un contrato
- Obliga a cualquier clase que la implemente a definir:
  - `getProviderName()`
  - `pay()`

### `AbstractPaymentProcessor.php`

Ejemplo de clase abstracta.

- No se instancia directamente
- Contiene lógica compartida para todos los procesadores
- Obliga a las clases hijas a implementar `getProviderName()` y `charge()`
- Centraliza la validación común dentro de `pay()`

### `StripePaymentProcessor.php`

Implementación concreta de un procesador de pagos.

- Cumple el contrato de `PaymentMethod`
- Hereda la lógica común de `AbstractPaymentProcessor`
- Define reglas propias dentro de `charge()`

### `PaypalPaymentProcessor.php`

Otra implementación concreta del mismo contrato.

- Permite comparar dos proveedores distintos
- Reutiliza la base abstracta
- Tiene reglas de aprobación diferentes a Stripe

### `PaymentStatus.php`

Ejemplo de `enum`.

- Define un conjunto cerrado de estados válidos
- Evita usar strings sueltos por todo el código
- En este ejemplo se usan:
  - `Pending`
  - `Approved`
  - `Declined`

### `Timestampable.php`

Ejemplo de trait.

- Reutiliza comportamiento entre clases sin usar herencia
- Añade timestamps de creación y actualización
- Puede incluirse con `use Timestampable;`

### `Order.php`

Clase de ejemplo que usa trait y enum.

- Usa `Timestampable` para manejar fechas
- Usa `PaymentStatus` para representar su estado
- Muestra cómo combinar varias herramientas de PHP en una misma clase

### `Invoice.php`

Ejemplo de clase `readonly`.

- Sus propiedades solo se pueden asignar una vez
- Útil para objetos inmutables o casi inmutables
- En este ejemplo representa una factura simple

### `InvoicePrinter.php`

Ejemplo de clase `final`.

- No puede heredarse
- Se usa cuando no quieres permitir extensiones de la clase
- Aquí solo formatea la información de una `Invoice`

## Resumen rápido

- **Clase**: agrupa datos y comportamiento
- **Herencia**: una clase reutiliza otra
- **Visibilidad**: controla quién puede ver o modificar miembros
- **Static**: se usa sin crear objetos
- **Interface**: define un contrato
- **Abstract class**: comparte lógica y obliga a completar partes
- **Trait**: reutiliza código entre clases
- **Final**: evita herencia o sobrescritura según el caso
- **Readonly**: crea objetos inmutables
- **Enum**: define un conjunto fijo de valores posibles

## Archivos relacionados

- [`main.php`](./main.php)
- [`User.php`](./User.php)
- [`Employee.php`](./Employee.php)
- [`BankAccount.php`](./BankAccount.php)
- [`PremiumBankAccount.php`](./PremiumBankAccount.php)
- [`MathUtils.php`](./MathUtils.php)
- [`PaymentMethod.php`](./PaymentMethod.php)
- [`AbstractPaymentProcessor.php`](./AbstractPaymentProcessor.php)
- [`StripePaymentProcessor.php`](./StripePaymentProcessor.php)
- [`PaypalPaymentProcessor.php`](./PaypalPaymentProcessor.php)
- [`PaymentStatus.php`](./PaymentStatus.php)
- [`Timestampable.php`](./Timestampable.php)
- [`Order.php`](./Order.php)
- [`Invoice.php`](./Invoice.php)
- [`InvoicePrinter.php`](./InvoicePrinter.php)
