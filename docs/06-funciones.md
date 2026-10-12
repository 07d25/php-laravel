# 06 · Funciones

> Carpeta de práctica: [`functions/`](../functions/) — 20 archivos, uno por concepto.

```bash
php functions/function.php
php functions/recursion.php
```

**¿Qué es una función?** Un trozo de código con nombre que puedes ejecutar tantas veces como quieras.
Permite **no repetir código**: lo escribes una vez y lo llamas muchas.

```php
function greet($name) {
    return "Hello, $name!";
}

echo greet("Alice");   // Hello, Alice!
```

Anatomía:

```
function  nombre(parametros)  { cuerpo }
   ↑           ↑                 ↑
palabra   datos de entrada   qué hace
reservada
```

- `return` **devuelve** un valor y **termina** la función.
- Si una función no tiene `return`, devuelve `null`.

---

## 1. Tipos en funciones (parámetros y retorno)

[`types-func.php`](../functions/types-func.php)

```php
function add(int $a, int $b): int {     // recibe 2 enteros, devuelve 1 entero
    return $a + $b;
}

function greet(string $name): string {
    return "Hello, $name!";
}

echo add(5, 10);
echo greet("Alice");
```

```
function      add   ( int $a , int $b ) : int
              ↑         ↑               ↑
           nombre    parámetros      tipo de retorno
```

> ✅ **Siempre tipa.** Con `declare(strict_types=1);` al principio del archivo, PHP lanza error si le pasas un tipo equivocado en lugar de convertirlo a la fuerza.

### Union types (PHP 8) → un parámetro acepta varios tipos

[`union_types.php`](../functions/union_types.php)

```php
function process(string|int $value) {    // acepta string O int
    return "Recibido: " . $value;
}

echo process("Hola");
echo process(123);
```

También existen `?string` (string o null), `float|int`, `array|iterable`...

---

## 2. Argumentos opcionales

[`optional-func-args.php`](../functions/optional-func-args.php)

```php
function greet(string $name = "Enciso"): string {
    return "Hello, $name!";
}

echo greet("Alice");   // Hello, Alice!   → usa el valor pasado
echo greet();          // Hello, Enciso!  → usa el valor por defecto
```

> 📌 Los parámetros con valor por defecto **van siempre al final** de la lista.

---

## 3. Argumentos con nombre (PHP 8)

[`named_arguments.php`](../functions/named_arguments.php)

```php
function createUser(string $name, int $age, string $role) {
    return "$name - $age - $role";
}

// El orden deja de importar
echo createUser(
    role: "admin",
    age: 25,
    name: "Ana"
);
// Ana - 25 - admin
```

**Ventaja:** cuando una función tiene muchos parámetros (o varios `null`), el código queda autoexplicativo.

---

## 4. Argumentos variadicos (cantidad ilimitada)

[`variadic-func-args.php`](../functions/variadic-func-args.php)

```php
function sumAll(int ...$numbers): int {   // "..." recoge todos los sobrantes en un array
    $total = 0;

    foreach ($numbers as $num) {
        $total += $num;
    }

    return $total;
}

echo sumAll(1, 2, 3, 4, 5);   // 15
```

Dentro de la función, `$numbers` es un **array normal**.

---

## 5. Paso de valor vs paso de referencia

Este es el concepto que más confunde al principio. **Léelo dos veces.**

### 5.1 Por valor (por defecto) → recibe una **copia**

[`pass_by_value.php`](../functions/pass_by_value.php)

```php
function changeValue($num) {
    $num = 100;      // solo cambia la copia local
}

$x = 10;
changeValue($x);
echo $x;             // 10  → ¡NO cambió!
```

### 5.2 Por referencia (con `&`) → toca el **original**

[`pass_by_reference.php`](../functions/pass_by_reference.php)

```php
function changeValue(&$num) {   // el & dice "dame la referencia"
    $num = 100;                 // modifica la variable original
}

$x = 10;
changeValue($x);
echo $x;             // 100  → ¡sí cambió!
```

| | Por valor (`$x`) | Por referencia (`&$x`) |
|---|---|---|
| La función recibe | una copia | la variable original |
| ¿Modifica el exterior? | no | **sí** |
| Coste | copia los datos | sin copia |
| Uso típico | la mayoría de casos | cuando la función debe **devolver varios resultados** o mutar el estado |

### 5.3 Rendimiento: copias grandes sí importan

[`performance_value_vs_reference.php`](../functions/performance_value_vs_reference.php)

```php
$bigArray = range(1, 100000);   // 100.000 elementos

function processByValue($data) {        // ❌ PHP copia la estructura al entrar
    foreach ($data as $item) { /* solo lectura */ }
}

function processByReference(&$data) {   // ✅ no copia, trabaja sobre el original
    foreach ($data as $item) { /* solo lectura */ }
}
```

> 💡 **Consejo senior:** por referencia **solo si de verdad lo necesitas**.
> Regla: primero por valor; si el array es enorme y solo lees, o si debes modificarlo, usa `&`.
> En PHP 7.4+ existe también `readonly` / copias por valor en arrays pequeños... pero la idea es: **no optimices a ciegas, mide**.

---

## 6. Ámbito de las variables (scope)

[`variable_scope.php`](../functions/variable_scope.php)

```php
$x = 10;                     // variable GLOBAL

function testLocal() {
    $y = 5;                  // LOCAL: solo existe dentro de la función
    echo $y;
}

function testGlobal() {
    global $x;               // ⚠️ funciona pero NO se recomienda
    echo $x;
}

function testParameter($value) {   // ✅ MEJOR PRÁCTICA: pasarla como parámetro
    echo $value;
}

function counter() {
    static $count = 0;       // STATIC: recuerda su valor entre llamadas
    $count++;
    echo $count . "\n";
}

counter();   // 1
counter();   // 2
counter();   // 3
```

| Tipo | Visibilidad | Recomendado |
|------|-------------|-------------|
| Local (`$y` dentro) | solo en la función | ✅ sí |
| Parámetro | solo en la función | ✅ **la mejor opción** |
| `global $x` | rompe el encapsulado | ❌ evítalo |
| `static $count` | persiste entre llamadas | ⚠️ úsalo con cuidado |

---

## 7. Funciones puras e impuras

[`pure_functions.php`](../functions/pure_functions.php)

```php
// PURA: mismo input → mismo output, no toca nada de fuera
function add($a, $b) {
    return $a + $b;
}

echo add(2, 3);   // siempre 5
```

Una función **pura**:

- **No** modifica variables de fuera.
- **No** depende del estado global (hora, BD, aleatoriedad).
- Siempre devuelve lo mismo para los mismos argumentos.

**¿Por qué importa?** Las funciones puras son predecibles, fáciles de testear y no producen efectos secundarios sorpresos. Apunta a que la mayoría de tu código lo sea.

---

## 8. Funciones anónimas (closures)

[`anonymous-func.php`](../functions/anonymous-func.php)

```php
// Una función SIN nombre, guardada en una variable
$greet = function(string $name): string {
    return "Hello, $name!";
};

echo $greet("Alice");   // se llama igual que una variable: $greet(...)
```

**Cuándo se usan:** sobre todo como "callback" dentro de `array_map`, `array_filter`, `usort`...

```php
$even = array_filter([1,2,3,4], function ($n) {
    return $n % 2 === 0;
});
```

---

## 9. Arrow function (PHP 7.4+) → versión corta

[`arrow-func.php`](../functions/arrow-func.php)

```php
$multiply = fn(int $a, int $b): int => $a * $b;
echo $multiply(3, 4);   // 12

// Usa automáticamente las variables del exterior (scope)
$tax = 0.21;
$addTax = fn(float $price): float => $price + ($price * $tax);
echo $addTax(100);      // 121
```

| `function () { ... }` | `fn() => ...` |
|---|---|
| más verbosa | en **una sola línea** |
| hay que usar `use ($tax)` para variables externas | las hereda **automáticamente** |
| puede tener varias sentencias | solo **una** expresión |

Ejemplo comparado:

```php
// lo mismo, dos formas
$map1 = function ($n) { return $n * 2; };
$map2 = fn($n) => $n * 2;
```

---

## 10. Funciones de orden superior

[`higher_order_functions.php`](../functions/higher_order_functions.php)

> **Función de orden superior:** una función que **recibe** o **devuelve** otra función.

```php
function applyOperation($a, $b, $operation) {   // $operation es una función
    return $operation($a, $b);
}

$sum = function($x, $y) {
    return $x + $y;
};

echo applyOperation(5, 3, $sum);   // 8
```

Equivale a ejecutar `$sum(5, 3)` → `8`.

**Por qué es poderoso:** `applyOperation` **no sabe** qué hace; solo delega. Le puedes pasar `sum`, `multiply`, lo que sea. Es la base de `array_map` / `array_filter` que ya viste.

---

## 11. Recursión

[`recursion.php`](../functions/recursion.php)

```php
// Factorial: 5! = 5 × 4 × 3 × 2 × 1 = 120
function factorial($n) {
    if ($n <= 1) {
        return 1;                    // ← CASO BASE: el freno
    }

    return $n * factorial($n - 1);   // ← CASO RECURSIVO: se llama a sí misma
}

echo factorial(5);   // 120
```

Toda función recursiva necesita **dos partes obligatorias**:

1. **Caso base (el freno):** la condición que detiene las llamadas. *"¡Para! Ya llegamos al final."*
2. **Caso recursivo (el paso):** se llama a sí misma con un problema **más pequeño**, acercándose al caso base.

Si olvidas el caso base → **bucle infinito** y PHP lanza *maximum function nesting level*.

Deshaciendo `factorial(5)`:

```
5 × factorial(4)
  4 × factorial(3)
    3 × factorial(2)
      2 × factorial(1)  → 1
    3 × 2 = 6
  4 × 6 = 24
5 × 24 = 120
```

> ⚠️ Recursión legítima = profundidad controlada. Si puedes resolverlo con un bucle simple, **el bucle suele ser más eficiente**.

---

## 12. Generadores (`yield`) → no cargar todo en memoria

[`generator_functions.php`](../functions/generator_functions.php)

```php
function numbers() {
    for ($i = 1; $i <= 5; $i++) {
        yield $i;      // "entrega" un valor y se duerme
    }
}

foreach (numbers() as $num) {
    echo $num . "\n";
}
```

**Diferencia clave:**

- `return [1,2,3,4,5]` → construye **todo el array en memoria** de golpe.
- `yield 1; yield 2; ...` → devuelve **un valor cada vez**, bajo demanda.

```php
function readLargeFile() {
    $lines = file('huge.csv');
    foreach ($lines as $line) {
        yield $line;      // procesas línea a línea sin reventar la RAM
    }
}
```

> 💡 Úsalo con archivos grandes, APIs con paginación o secuencias infinitas.

---

## 13. `null` en funciones

[`null_in_php.php`](../functions/null_in_php.php)

```php
$value = null;

function checkValue($v) {
    if ($v === null) {                 // ⚠️ usa === , nunca ==
        return "No hay valor (NULL)";
    }

    return "Valor: $v";
}

echo checkValue($value);   // No hay valor (NULL)
```

`null` es el valor por defecto de muchas funciones y el "dato ausente". Compararlo con `==` es un error clásico (`null == ""` → `true`).

---

## 14. Tabla resumen del directorio

| Archivo | Concepto |
|---------|----------|
| `function.php` | función básica |
| `types-func.php` | tipos en parámetros y retorno |
| `union_types.php` | `string\|int` (PHP 8) |
| `optional-func-args.php` | valores por defecto |
| `named_arguments.php` | argumentos con nombre (PHP 8) |
| `variadic-func-args.php` | `...$args` |
| `pass_by_value.php` | copia |
| `pass_by_reference.php` | referencia `&` |
| `performance_value_vs_reference.php` | coste de copiar |
| `variable_scope.php` | local / global / static |
| `pure_functions.php` | funciones puras |
| `anonymous-func.php` | closures |
| `arrow-func.php` | `fn() =>` |
| `higher_order_functions.php` | recibir/devolver funciones |
| `recursion.php` | recursión y caso base |
| `generator_functions.php` | `yield` |
| `null_in_php.php` | manejo de `null` |

---

## ✅ Ejercicios

1. Escribe `isEven(int $n): bool` y úsala con `array_filter` para quedarte solo los pares.
2. Convierte `function ($n) { return $n * 2; }` en una *arrow function*.
3. Escribe `fibonacci($n)` recursiva (0, 1, 1, 2, 3, 5, 8...).
4. Haz una función `sortByPrice(array $products)` que **modifique** el array original (¿por valor o por referencia?).
5. Crea un generador que vaya entregando los múltiplos de 3 del 1 al 30.

---

**Siguiente:** [07 · Programación orientada a objetos](07-poo.md)
