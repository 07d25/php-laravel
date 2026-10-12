# 01 · Sintaxis, variables y tipos de datos

> Archivos de práctica: [`expressions.php`](../expressions.php) y [`data-types.php`](../data-types.php)

```bash
php expressions.php
php data-types.php
```

---

## 1. La estructura mínima de un archivo PHP

```php
<?php          // ← toda fila PHP empieza con esto (una vez por archivo)

// Comentario de una sola línea

# Comentario alternativo (también válido)

/**
 * Comentario
 * de varias líneas
 */

echo "Hola\n";  // instrucción
```

> **Regla de oro:** cada instrucción termina en `;`. Si olvidas uno, PHP lanza un *parse error*.

---

## 2. Variables

En PHP **toda variable empieza con `$`**:

```php
$name = "Alice";   // string
$age  = 30;        // integer
```

Puntos clave:

- **No se declaran tipos**: el tipo lo define el valor que asignas.
- Son **case sensitive**: `$name` y `$Name` son variables distintas.
- Puedes reasignar con otro tipo después (aunque mejor no lo hagas).

### Interpolación (meter variables dentro de un texto)

```php
$name = "Alice";

// ✅ Con comillas dobles PHP sustituye la variable
echo "My name is $name\n";          // My name is Alice

// ✅ Con llaves cuando hay más texto pegado
echo "Hola a {$name}!\n";

// ❌ Con comillas simples NUNCA la sustituye
echo 'My name is $name\n';          // My name is $name  (literal)
```

---

## 3. Concatenación: unir textos

El operador de unión de cadenas es el **punto `.`**:

```php
$name = "Alice";
$greeting = "Hello, " . $name . "!";   // "Hello, Alice!"
echo $greeting . "\n";
```

> ⚠️ En PHP `+` **no** sirve para unir textos (a diferencia de otros lenguajes). `+` es solo aritmética.

---

## 4. Operadores aritméticos

```php
$pizzas = 3;
$slicesPerPizza = 8;
$totalSlices = $pizzas * $slicesPerPizza;   // 24
```

| Operador | Significado | Ejemplo | Resultado |
|----------|-------------|---------|-----------|
| `+` | suma | `7 + 3` | `10` |
| `-` | resta | `7 - 3` | `4` |
| `*` | multiplicación | `7 * 3` | `21` |
| `/` | división | `7 / 2` | `3.5` |
| `%` | resto (módulo) | `7 % 2` | `1` |
| `**` | potencia | `2 ** 3` | `8` |

---

## 5. Operadores de comparación

```php
$age = 30;

if ($age > 18) {
    echo "You are an adult.\n";
} else {
    echo "You are an minor.\n";
}
```

| Operador | Significado | ⚠️ Cuidado |
|----------|-------------|-----------|
| `==` | igual (con **conversión** de tipos) | `"5" == 5` → `true` |
| `===` | igual **estricto**: valor **y** tipo | `"5" === 5` → `false` |
| `!=` / `<>` | distinto | |
| `!==` | distinto estricto | **úsalo siempre** |
| `<`, `>`, `<=`, `>=` | menor, mayor... | |

> **Regla senior:** en condiciones de comparación, usa **casi siempre `===` y `!==`**.
> Evita sorpresas como que `"" == false` sea `true`.

Ejemplo del repo (login con comparación estricta):

```php
if ($username === "admin" && $password === "admin123") {
    echo "Bienvenido admin\n";
} else {
    echo "Credenciales incorrectas\n";
}
```

---

## 6. Operadores lógicos

```php
$isStudent = true;
$age = 30;

if ($isStudent && $age < 25) { ... }   // AND: ambas deben ser true
if ($isStudent || $age < 25)  { ... }  // OR:  al menos una true
if (!$isStudent)              { ... }  // NOT: invierte
```

| Corto | Palabra | Significado |
|-------|---------|-------------|
| `&&` | `and` | y |
| `\|\|` | `or` | o |
| `!` | `not` | no |

### Detalle que confunde a todos al principio

Al hacer `echo` de un booleano, PHP lo convierte a número:

```php
echo true;    // imprime 1
echo false;   // imprime nada (cadena vacía)
echo !false;  // imprime 1
```

---

## 7. Los 8 tipos de datos de PHP

Todo lo anterior se resume en `data-types.php`. PHP 8 tiene **8 tipos**:

### 7.1 `string` — texto

```php
$name = "Alice";
echo $name . "\n";
```

### 7.2 `integer` — entero

```php
$age = 30;
```

### 7.3 `float` (o `double`) — decimal

```php
$price = 19.99;
```

### 7.4 `boolean` — verdadero/falso

```php
$isStudent = true;
var_dump($isStudent);   // bool(true)
```

### 7.5 `array` — colección ordenada de valores

```php
$fruits = ["Apple", "Banana", "Orange"];

// Un array puede mezclar tipos y hasta anidarse
$data = [
  "texto",
  123,
  3.14,
  true,
  null,
  ["otro", "array"],
  new stdClass()
];

print_r($fruits);
var_dump($data);
```

> En PHP **un array es una superherramienta**: lista, diccionario, pila... Todo a la vez.
> Lo veremos a fondo en [05 · Arrays](05-arrays.md).

### 7.6 `null` — "sin valor"

```php
$middleName = null;
var_dump($middleName);   // NULL
```

`null` significa: *esta variable existe pero no tiene contenido*.

### 7.7 `object` — objeto (instancia de una clase)

```php
class User
{
    public string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}

$user = new User("Alice");
var_dump($user);
```

Salida:

```
object(User)#2 (1) {
  ["name"]=>
  string(5) "Alice"
}
```

En inglés se dice **class** = plano/plantilla, **object** = la cosa construida.
Más en [07 · Programación orientada a objetos](07-poo.md).

### 7.8 `resource` — puntero a recurso externo

No aparece en el ejemplo (se usa con archivos, bases de datos, imágenes...). Lo tocarás al conectarte a una BD.

---

## 8. Cómo comprobar el tipo de una variable

```php
$type = "Alice";

var_dump($type);                 // string(5) "Alice"  → te da valor + tipo
echo gettype($type);             // string
var_dump(is_string($type));      // bool(true)
is_int($type);  is_float($type); is_bool($type);  is_array($type);  is_null($type);
```

---

## 9. Convertir tipos (type casting)

```php
$priceText = "19.99";

$priceNumber = (float) $priceText;   // string → decimal
$age         = (int) "30";           // string → entero  (30)
$text        = (string) 25;          // entero → texto   ("25")
$flag        = (bool) "";            // cadena vacía → false
```

| Expresión | ¿Es `true`? |
|-----------|-------------|
| `true`, `1`, `"hola"`, `[1,2]` | ✅ sí |
| `false`, `0`, `""`, `null`, `[]` | ❌ no |

---

## 10. Comentarios: por qué importan

```php
// Explica el porqué, no el qué.
$totalSlices = $pizzas * $slicesPerPizza;   // 3 pizzas × 8 porciones
```

Buenas prácticas:

- Comenta **decisiones y trampas**, no lo obvio (`$i++ // incrementa` sobra).
- Deja el código hablando con **nombres claros**: `$totalSlices` mejor que `$ts`.
- En este repo **los comentarios están en español y el código en inglés**, a propósito: así escribes como en tu trabajo y entiendes como estudiante.

---

## ✅ Ejercicios para practicar

1. Crea `$price = 19.99` y muestra por pantalla: `El precio es 19.99 EUR`.
2. Comprueba con `var_dump()` qué pasa con `"5" == 5` y `"5" === 5`.
3. Declara `$isAdmin = false` y usa `!` para imprimir un mensaje distinto en cada caso.
4. Convierte `"12345"` a entero y multiplícala por 2.

---

**Siguiente:** [02 · Cadenas de texto (strings)](02-strings.md)
