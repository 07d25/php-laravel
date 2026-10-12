# 04 · Condicionales y bucles (control de flujo)

> Archivos de práctica:
> [`conditionals.php`](../conditionals.php) ·
> [`switch.php`](../switch.php) ·
> [`match-expression.php`](../match-expression.php) ·
> [`while-loop.php`](../while-loop.php) ·
> [`foreach-loop.php`](../foreach-loop.php) ·
> [`guess-secret-game.php`](../guess-secret-game.php)

```bash
php conditionals.php
php while-loop.php
php guess-secret-game.php     # ← interactivo: escribe por teclado
```

---

## 1. `if` / `else` — el básico

```php
$edad = 20;

if ($edad >= 18) {
    echo "Eres mayor de edad\n";
} else {
    echo "Eres menor de edad\n";
}
```

### Varias condiciones con `&&` (Y) y `||` (O)

```php
$tienePermiso = true;

if ($edad >= 18 && $tienePermiso) {
    echo "Puedes entrar\n";
} else {
    echo "No puedes entrar\n";
}
```

### Comprobaciones encadenadas

```php
if ($username === "admin" && $password === "admin123") {
    echo "Bienvenido admin\n";
} else {
    echo "Credenciales incorrectas\n";
}
```

> 🔐 Fíjate en `===`: compara **valor y tipo**. Con `==`, `0 == "admin"` sería `true`... y eso es una puerta trasera.

### Varios niveles: `if` / `elseif` / `else`

```php
$nota = 85;

if ($nota >= 90) {
    echo "Excelente\n";
} elseif ($nota >= 70) {          // se evalúa en orden, de arriba a abajo
    echo "Aprobado\n";
} else {
    echo "Reprobado\n";
}
```

> El **orden importa**: si pones `>= 70` primero, nadie llegaría a `>= 90`.

---

## 2. Operadores modernos de PHP

### 2.1 Operador ternario `? :`

```php
$estado = ($edad >= 18) ? "Adulto" : "Menor";
//        condición      sí       no
echo $estado;
```

Equivalentes a:

```php
if ($edad >= 18) {
    $estado = "Adulto";
} else {
    $estado = "Menor";
}
```

> Usa el ternario solo para expresiones **cortas y legibles**. Anidar dos ternarios es ilegible.

### 2.2 Null coalescing `??` → "usa esto si lo otro no existe"

```php
$nombre = null;

$nombreFinal = $nombre ?? "Invitado";
echo "Usuario: " . $nombreFinal;   // Usuario: Invitado
```

Comprueba si la variable **no existe o es `null`**; si tiene valor, lo usa.

> Muy usado con datos de formularios/URL: `$page = $_GET['page'] ?? 1;`

### 2.3 Null coalescing assignment `??=`

```php
$nombre ??= "Usuario por defecto";
// Si $nombre es null (o no existe), lo rellena. Si ya tiene valor, no lo toca.
echo $nombre;
```

---

## 3. `switch` — el clásico

```php
$size = "M";

switch ($size) {
    case "XS":
        echo "Talla extra pequeña (XS)\n";
        break;          // ← SIN break seguiría ejecutando los siguientes cases
    case "S":
        echo "Talla pequeña (S)\n";
        break;
    case "M":
        echo "Talla mediana (M)\n";
        break;
    default:            // ← "si no coincide con nada de lo anterior"
        echo "Talla no reconocida\n";
        break;
}
```

Puntos clave:

- `break` **evita el *fall-through*** (que se ejecuten los cases de abajo).
- `default` es el caso por defecto.
- Sin `break` a propósito se puede usar para agrupar casos (`case "S": case "M": ...`).

---

## 4. `match` — el `switch` moderno (PHP 8)

```php
$httpCode = 201;

$message = match ($httpCode) {
    200 => "OK - La petición fue exitosa",
    201 => "Created - Recurso creado correctamente",
    400 => "Bad Request - Error en la solicitud",
    401 => "Unauthorized - No autenticado",
    404 => "Not Found - Recurso no encontrado",
    500 => "Internal Server Error - Error del servidor",
    default => "Código HTTP desconocido"
};

echo $message;
```

**Diferencias con `switch`:**

| `switch` | `match` |
|----------|---------|
| es una *sentencia* (no devuelve valor) | es una *expresión* → **devuelve un valor** |
| usa `break` | no hace falta `break` (no cae al siguiente) |
| compara con `==` (no estricto) | compara con `===` |
| puede pasar por accidente | **lanza `UnhandledMatchError`** si no hay caso |

> 💡 Regla práctica: **`match` para valores que comparas, `if/elseif` para condiciones complejas.**

---

## 5. Bucles

### 5.1 `while` → "mientras se cumpla esto"

```php
$i = 1;

while ($i <= 5) {
    echo "Iteración número: $i\n";
    $i++;      // ⚠️ IMPORTANTE: si olvidas esto, bucle infinito
}
```

### 5.2 `while` recorriendo un array con índice

```php
$usuarios = ["Ana", "Luis", "Carlos"];
$index = 0;

while ($index < count($usuarios)) {
    echo "Usuario: " . $usuarios[$index] . "\n";
    $index++;
}
```

### 5.3 `while (true)` + `break` → bucle con salida manual

```php
$contador = 1;

while (true) {
    echo "Contador: $contador\n";
    $contador++;

    if ($contador > 3) {
        break;      // rompe el bucle
    }
}
```

### 5.4 `do { } while` → se ejecuta **al menos una vez**

```php
$j = 1;

do {
    echo "Do While número: $j\n";
    $j++;
} while ($j <= 3);
```

**Cuándo usarlo:** cuando necesitas pedir datos *antes* de comprobar la condición (por ejemplo, un login que siempre pide usuario al menos una vez).

### 5.5 `continue` → salta a la siguiente vuelta

```php
$k = 0;

while ($k < 5) {
    $k++;

    if ($k == 3) {
        continue;   // no imprime el 3, pasa a la siguiente iteración
    }

    echo "Número: $k\n";
}
// imprime 1, 2, 4, 5
```

### Resumen `break` vs `continue`

| Palabra | Efecto |
|---------|--------|
| `break` | **sale** del bucle entero |
| `continue` | **salta** esta iteración y sigue con la siguiente |

---

## 6. `foreach` → la forma correcta de recorrer arrays

Ejemplo real: un carrito de compras.

```php
$basket = [
    "apple" => 3,
    "banana" => 5,
    "orange" => 2,
    "milk" => 1
];

$totalItems = 0;

foreach ($basket as $product => $quantity) {
    echo "Producto: $product | Cantidad: $quantity\n";
    $totalItems += $quantity;      // acumulamos
}

echo "Total de items: $totalItems\n";   // 11
```

Tres formas de usarlo:

```php
foreach ($basket as $quantity) { ... }             // solo valores
foreach ($basket as $product => $quantity) { ... } // clave y valor (asociativo)
foreach ($basket as $key => $value) { ... }        // también sirve en arrays indexados
```

> 📌 **Cuando puedas elegir, usa `foreach`.** Es más legible y más seguro que manejar índices a mano.

---

## 7. Mini juego completo: número secreto

Fichero [`guess-secret-game.php`](../guess-secret-game.php) — combina bucles, condicionales y **entrada del usuario**.

```php
$secretNumber = 7;
$attempts = 0;
$maxAttempts = 5;
$guess = null;

// Mientras no acierte Y le queden intentos
while ($guess !== $secretNumber && $attempts < $maxAttempts) {

    echo "Encuentra el número (1-10): ";

    // Leer lo que escribe el usuario en la terminal
    $guess = (int) trim(fgets(STDIN));

    $attempts++;

    if ($guess === $secretNumber) {
        echo "🎉 ¡Correcto!\n";
        break;
    }

    if ($attempts >= $maxAttempts) {
        echo "❌ Se acabaron los intentos. El número era $secretNumber\n";
        break;
    }

    // Pistas
    if ($guess < $secretNumber) {
        echo "📉 El número es mayor\n";
    } else {
        echo "📈 El número es menor\n";
    }

    echo "Intentos restantes: " . ($maxAttempts - $attempts) . "\n\n";
}
```

Conceptos nuevos:

| Código | Qué hace |
|--------|----------|
| `fgets(STDIN)` | lee una línea escrita en la terminal |
| `trim()` | quita el salto de línea final |
| `(int)` | convierte el texto a número entero |
| `&&` en el `while` | ambas condiciones deben cumplirse |
| `break` | termina el juego en cuanto hay resultado |

Ejecútalo:

```bash
php guess-secret-game.php
```

---

## 8. ¿Cuándo usar cada estructura?

| Situación | Usa |
|-----------|-----|
| 2 o 3 opciones con condiciones distintas | `if / elseif / else` |
| Valor corto y sí/no | ternario `? :` |
| Valor por defecto si falta algo | `??` y `??=` |
| Comparar un valor contra una lista fija | `match` (PHP 8) o `switch` |
| Repetir mientras haya condición | `while` |
| Necesitas ejecutar aunque sea una vez | `do / while` |
| Recorrer un array | **`foreach`** |
| Repetir un número exacto de veces | `for` (no usado en el repo, pero existe) |

---

## ✅ Ejercicios

1. Pide por teclado un número del 1 al 10 y di si es par o impar (usa `%`).
2. Reescribe el `switch.php` de tallas usando `match`.
3. Imprime los números del 1 al 10 **saltando los múltiplos de 3** (`continue`).
4. Suma los valores del array `[4, 8, 15, 16, 23, 42]` con un `foreach`.
5. Mejora el juego: que el número secreto sea aleatorio con `random_int(1, 10)`.

---

**Siguiente:** [05 · Arrays](05-arrays.md)
