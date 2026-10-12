# 03 · Números

> Archivos de práctica:
> [`numbers_integer_float.php`](../numbers_integer_float.php) ·
> [`numbers_functions.php`](../numbers_functions.php) ·
> [`numbers_formatting.php`](../numbers_formatting.php) ·
> [`min_max.php`](../min_max.php) ·
> [`random_numbers.php`](../random_numbers.php) ·
> [`numbers_combined.php`](../numbers_combined.php)

```bash
php numbers_integer_float.php
php numbers_combined.php
```

---

## 1. Enteros y decimales

```php
$intNumber = 10;        // integer (entero)
$floatNumber = 10.5;    // float   (decimal)

var_dump($intNumber);    // int(10)
var_dump($floatNumber);  // float(10.5)

echo $intNumber + $floatNumber;   // 20.5 → PHP promueve int a float
```

Datos clave:

- En PHP **no hay límite práctico de enteros** en 64 bits (hasta 9.22 × 10¹⁸).
- Un decimal **siempre lleva punto** (`10.5`), nunca coma.
- Ojo con la coma decimal al **mostrar** (eso es formato, ver punto 3).

---

## 2. Funciones numéricas básicas

```php
$number = -15.7;

echo abs($number);    // 15.7   valor absoluto (quita el signo)
echo round($number);  // -16    redondeo normal (el .5 sube en magnitud)
echo floor($number);  // -16    "piso": hacia menos infinito
echo ceil($number);   // -15    "techo": hacia más infinito
```

| Función | Qué hace | `15.7` | `-15.7` |
|---------|----------|--------|---------|
| `abs()` | valor absoluto | `15.7` | `15.7` |
| `round()` | redondea | `16` | `-16` |
| `floor()` | siempre hacia abajo | `15` | `-16` |
| `ceil()` | siempre hacia arriba | `16` | `-15` |

> `round(15.55, 2)` → `15.56`: el **segundo parámetro** son los decimales a conservar.

### Evitar problemas de coma flotante

```php
echo 0.1 + 0.2;              // 0.30000000000000004  ← ¡nunca confíes en esto!
echo round(0.1 + 0.2, 2);    // 0.3

// Con dinero, lo profesional es redondear SIEMPRE a 2 decimales
$price = round($price, 2);
```

---

## 3. Formato de números para mostrar

```php
$price = 1999.5678;

echo number_format($price, 2);                 // 1,999.57
echo number_format($price, 2, ".", ",");       // 1,999.57 (separador miles, punto decimal)
```

Firma: `number_format($numero, $decimales, $separadorDecimal, $separadorMiles)`.

```php
// Para España/Latam (coma decimal, punto de miles):
echo number_format(1234.5, 2, ",", ".");       // 1.234,50
```

> ⚠️ Esto es **solo presentación**: el valor original sigue siendo `float`.
> Nunca hagas cálculos sobre un número ya formateado como texto.

---

## 4. Mínimo y máximo

```php
$numbers = [5, 2, 9, 1, 7, 0, -15];

echo min($numbers);   // -15
echo max($numbers);   // 9
```

También funciona con varios argumentos sueltos: `min(3, 8, 1)` → `1`.

---

## 5. Números aleatorios

```php
echo rand(1, 10) . "\n";              // rango entero (forma clásica)
echo random_int(1, 100) . "\n";       // ✅ forma moderna y criptográficamente segura
echo mt_rand() . "\n";                // Mersenne Twister (rápido, no seguro)
echo mt_rand() / mt_getrandmax();     // decimal entre 0 y 1
```

| Función | Uso recomendado |
|---------|-----------------|
| `random_int(a, b)` | **Casi siempre esta.** Aleatoriedad real: tokens, códigos, contraseñas, sorteos. |
| `rand(a, b)` / `mt_rand()` | Juegos, animaciones, datos de prueba. Rápido pero **predecible**. |
| `mt_rand() / mt_getrandmax()` | Generar un float aleatorio. |

> 🔒 Si la aleatoriedad importa para **seguridad** (tokens, sesiones, contraseñas), usa `random_int()`.

---

## 6. Ejemplo real combinado

Fichero `numbers_combined.php`: todo junto, como encontrarías en un panel de ventas.

```php
$prices = [10.5, 20.99, 5.75, 100.3];

$total = array_sum($prices);              // suma de todos → 137.54
$min = min($prices);                      // 5.75
$max = max($prices);                      // 100.3
$average = $total / count($prices);       // promedio → count() cuenta elementos

echo "Total: " . number_format($total, 2) . "\n";    // Total: 137.54
echo "Min: $min\n";
echo "Max: $max\n";
echo "Average: " . number_format($average, 2) . "\n"; // Average: 34.39
```

Funciones que acabas de aprender y son **de uso diario**:

| Función | Para qué |
|---------|----------|
| `array_sum()` | suma todos los valores de un array |
| `count()` | cuántos elementos tiene un array |
| `min()` / `max()` | extremos |
| `number_format()` | presentar el resultado |

---

## 7. Operadores útiles con números

```php
$a = 10;
$b = 3;

echo $a + $b;    // 13
echo $a - $b;    // 7
echo $a * $b;    // 30
echo $a / $b;    // 3.3333333333333
echo $a % $b;    // 1   (resto de la división)
echo $a ** $b;   // 1000 (potencia)

// Asignación compuesta
$count = 0;
$count += 5;   // 5   (equivale a $count = $count + 5)
$count++;      // 6
$count--;      // 5
```

**Truco muy usado:** ¿es par o impar?

```php
$n = 7;
if ($n % 2 === 0) {
    echo "Par";
} else {
    echo "Impar";
}
```

---

## ✅ Ejercicios

1. Dado un precio con 4 decimales, muéstralo con 2 decimales y separador de miles.
2. Calcula el promedio de `[8, 9.5, 7, 10]` y muéstralo con `number_format`.
3. Genera 5 números aleatorios del 1 al 50 con `random_int()` (pista: bucle).
4. Redondea `2.5`, `2.4`, `-2.5` con `round()`, `floor()` y `ceil()` y compara.

---

**Siguiente:** [04 · Condicionales y bucles](04-control-de-flujo.md)
