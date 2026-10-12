# 05 · Arrays (listas y diccionarios)

> Carpeta de práctica: [`arrays/`](../arrays/) — 16 archivos, uno por concepto.

```bash
php arrays/arrays_indexed.php
php arrays/arrays_filter.php
# ...etc
```

En PHP el **array es la estructura de datos estrella**. Aquí se guarda todo: listas, diccionarios, tablas, colecciones...

---

## 1. Los tres tipos de array

### 1.1 Array indexado (lista ordenada)

[`arrays_indexed.php`](../arrays/arrays_indexed.php)

```php
$fruits = ["apple", "banana", "orange"];

echo $fruits[0];    // apple  ← los índices empiezan en 0

foreach ($fruits as $fruit) {
    echo $fruit . "\n";
}
```

### 1.2 Array asociativo (clave ⇒ valor, como un diccionario)

[`arrays_associative.php`](../arrays/arrays_associative.php)

```php
$user = [
    "name" => "Ana",
    "age"  => 25,
    "role" => "admin"
];

echo $user["name"];    // Ana

foreach ($user as $key => $value) {
    echo "$key: $value\n";
}
```

> El operador `=>` significa **"apunta a"**: `"name" => "Ana"`.

### 1.3 Array multidimensional (lista de listas / tablas)

[`arrays_multidimensional.php`](../arrays/arrays_multidimensional.php)

```php
$users = [
    ["name" => "Ana",     "age" => 25],
    ["name" => "Luis",    "age" => 30],
    ["name" => "Carlos",  "age" => 28],
];

echo $users[0]["name"];    // Ana  → primero el índice 0, luego la clave "name"

foreach ($users as $user) {
    echo $user["name"] . " - " . $user["age"] . "\n";
}
```

Esto es literalmente **una tabla de base de datos en PHP**.

---

## 2. Agregar, eliminar y consultar

```php
$fruits = ["apple"];

$fruits[] = "banana";          // [] = "añade al final"
$fruits[] = "orange";

$fruits[1] = "BANANA";         // sobrescribe la posición 1

unset($fruits[0]);             // elimina el elemento con índice 0

echo count($fruits);           // número de elementos
isset($fruits[1]);             // ¿existe esa clave?
in_array("orange", $fruits);   // ¿está el valor en el array?
```

---

## 3. Extraer claves y valores

| Archivo | Función | Qué devuelve |
|---------|---------|--------------|
| [`arrays_keys.php`](../arrays/arrays_keys.php) | `array_keys($user)` | solo las claves: `["name","age","role"]` |
| [`arrays_values.php`](../arrays/arrays_values.php) | `array_values($user)` | solo los valores, **renumerados** desde 0 |

```php
$user = ["name" => "Ana", "age" => 25, "role" => "admin"];

print_r(array_keys($user));     // [name, age, role]
print_r(array_values($user));   // [Ana, 25, admin]  ← pierde las claves
```

> `array_values()` es muy útil cuando has filtrado un array y necesitas **reindexar**.

---

## 4. Ordenar

[`arrays_sort.php`](../arrays/arrays_sort.php)

```php
$numbers = [5, 2, 9, 1, 7];

sort($numbers);     // ascendente  → [1, 2, 5, 7, 9]
rsort($numbers);    // descendente → [9, 7, 5, 2, 1]
```

También hay (para arrays asociativos):

```php
asort($user);    // ordena por VALOR manteniendo las claves
ksort($user);    // ordena por CLAVE
arsort($user);   // por valor, descendente
```

> ⚠️ `sort()` **modifica el array original** y **pierde las claves** si era asociativo.

---

## 5. Unir, comparar y combinar arrays

| Archivo | Función | Qué hace |
|---------|---------|----------|
| [`arrays_merge.php`](../arrays/arrays_merge.php) | `array_merge($a, $b)` | une dos arrays en uno |
| [`arrays_diff.php`](../arrays/arrays_diff.php) | `array_diff($a, $b)` | lo que está en `$a` pero **no** en `$b` |
| [`arrays_intersect.php`](../arrays/arrays_intersect.php) | `array_intersect($a, $b)` | lo que está en **ambos** |

```php
// merge
$result = array_merge(["apple", "banana"], ["orange", "grape"]);
// [apple, banana, orange, grape]

// diff → lo que sobra
array_diff(["apple", "banana", "orange"], ["banana", "grape"]);
// [apple, orange]

// intersect → lo común
array_intersect(["apple","banana","orange"], ["banana","grape","apple"]);
// [apple, banana]
```

### Caso real: planes de suscripción

[`arrays_combined_real.php`](../arrays/arrays_combined_real.php)

```php
$planA = ["basic", "pro"];
$planB = ["pro", "enterprise"];

$allPlans = array_merge($planA, $planB);   // todos
$uniquePlans = array_unique($allPlans);    // sin duplicados → [basic, pro, enterprise]
$commonPlans = array_intersect($planA, $planB);  // comunes → [pro]

print_r($uniquePlans);
print_r($commonPlans);
```

`array_unique()` elimina valores repetidos: muy útil tras un `array_merge`.

---

## 6. Las tres funciones "funcionales": `map`, `filter`, `reduce`

Son **el corazón** de cómo se manipulan colecciones en PHP (y en cualquier lenguaje moderno).

### 🔁 `array_map` → transforma cada elemento

[`arrays_map.php`](../arrays/arrays_map.php)

```php
$numbers = [1, 2, 3, 4];

$result = array_map(function ($n) {
    return $n * 2;       // lo que devuelve cada llamada es el nuevo valor
}, $numbers);

print_r($result);        // [2, 4, 6, 8]
```

### 🧹 `array_filter` → queda solo con los que cumplen

[`arrays_filter.php`](../arrays/arrays_filter.php)

```php
$numbers = [1, 2, 3, 4, 5, 6];

$even = array_filter($numbers, function ($n) {
    return $n % 2 === 0;   // devuelve true si lo queremos conservar
});

print_r($even);            // [2, 4, 6]
```

### 📦 `array_reduce` → convierte la lista en un único valor

[`arrays_reduce.php`](../arrays/arrays_reduce.php)

```php
$numbers = [1, 2, 3, 4];

$total = array_reduce($numbers, function ($carry, $item) {
    return $carry + $item;   // $carry es el "acumulador"
}, 0);                       // valor inicial del acumulador

echo $total;                 // 10
```

Paso a paso: `0+1=1` → `1+2=3` → `3+3=6` → `6+4=10`.

> 🧠 **Truco para recordarlas:**
> `map` = **transforma** (mismo número de elementos) ·
> `filter` = **selecciona** (menos elementos) ·
> `reduce` = **resumen** (un solo elemento).

---

## 7. Ejemplo real combinado

[`arrays_combined.php`](../arrays/arrays_combined.php) — catálogo de productos con las tres juntas:

```php
$products = [
    ["name" => "Laptop",   "price" => 1000],
    ["name" => "Mouse",    "price" => 50],
    ["name" => "Keyboard", "price" => 150]
];

// 1) FILTRAR: quedarnos con los caros (> 100)
$expensive = array_filter($products, function ($p) {
    return $p["price"] > 100;
});
// Laptop, Keyboard

// 2) MAP: de cada producto, solo su precio
$prices = array_map(function ($p) {
    return $p["price"];
}, $products);
// [1000, 50, 150]

// 3) REDUCE: sumar todos los precios
$total = array_reduce($prices, function ($carry, $item) {
    return $carry + $item;
}, 0);

echo "Total: $total\n";   // Total: 1200
```

> Este patrón **filtrar → transformar → resumir** aparece en todos los frameworks (Laravel lo tiene con `->filter()`, `->map()`, `->reduce()`).

---

## 8. Unpacking (desempaquetar) arrays

### 8.1 Dentro de otro array (PHP 7.4+)

[`arrays_unpacking.php`](../arrays/arrays_unpacking.php)

```php
$a = [1, 2, 3];
$b = [4, 5, 6];

$result = [...$a, ...$b];   // "..." expande el array
print_r($result);           // [1, 2, 3, 4, 5, 6]
```

### 8.2 Como argumentos de una función

[`arrays_unpacking_functions.php`](../arrays/arrays_unpacking_functions.php)

```php
function sum($a, $b, $c) {
    return $a + $b + $c;
}

$values = [10, 20, 30];

echo sum(...$values);   // 60  → equivale a sum(10, 20, 30)
```

Muy útil cuando recibes un array y necesitas pasarlo como parámetros sueltos.

---

## 9. Tabla resumen de todo el directorio

| Archivo | Concepto |
|---------|----------|
| `arrays_indexed.php` | lista por posición |
| `arrays_associative.php` | diccionario clave→valor |
| `arrays_multidimensional.php` | tablas / anidados |
| `arrays_keys.php` / `arrays_values.php` | extraer claves o valores |
| `arrays_sort.php` | ordenar |
| `arrays_merge.php` | unir |
| `arrays_diff.php` | diferencias |
| `arrays_intersect.php` | intersección |
| `arrays_map.php` | transformar |
| `arrays_filter.php` | filtrar |
| `arrays_reduce.php` | acumular |
| `arrays_combined.php` | map + filter + reduce juntos |
| `arrays_combined_real.php` | merge + unique + intersect |
| `arrays_unpacking.php` | `...` en arrays |
| `arrays_unpacking_functions.php` | `...` en funciones |

---

## ✅ Ejercicios

1. Crea el inventario de una tienda (nombre, stock, precio) y muestra solo los que tienen stock `0`.
2. Suma todos los precios con `array_reduce` y muéstralo con `number_format`.
3. Une dos listas de usuarios y elimina los duplicados.
4. Ordena la lista de edades de mayor a menor.
5. Dado `["ana","luis","ana"]`, cuenta cuántas veces aparece cada nombre (pista: `array_count_values()`).

---

**Siguiente:** [06 · Funciones](06-funciones.md)
