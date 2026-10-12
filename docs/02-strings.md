# 02 · Cadenas de texto (strings)

> Archivos de práctica:
> [`strings.php`](../strings.php) ·
> [`string_accessing_manipulation.php`](../string_accessing_manipulation.php) ·
> [`formatting_strings.php`](../formatting_strings.php) ·
> [`advanced_strings_encoding.php`](../advanced_strings_encoding.php) ·
> [`searching_regex.php`](../searching_regex.php)

```bash
php strings.php
php string_accessing_manipulation.php
php formatting_strings.php
php advanced_strings_encoding.php
php searching_regex.php
```

---

## 1. Las tres formas de escribir un texto

### 1.1 Comillas simples `' '` → **texto literal**

```php
$name = "Ana";

echo 'Hola $name';
// salida exacta: Hola $name  (¡la variable NO se sustituye!)
```

### 1.2 Comillas dobles `" "` → **interpreta variables**

```php
echo "Hola $name!\n";
// salida: Hola Ana!
```

Además en comillas dobles funcionan los **caracteres de escape**:

| Secuencia | Significado |
|-----------|-------------|
| `\n` | salto de línea |
| `\t` | tabulador |
| `\"` | comilla doble literal |
| `\\` | barra invertida literal |

### 1.3 Textos multilínea: `heredoc` y `nowdoc`

```php
$heredoc = <<<EOD
Multi-line string
with variable: $name
EOD;                 // ← heredoc: COMO comillas dobles, interpreta $name

$nowdoc = <<<'TEXT'
Hola $name
Esto es texto literal
TEXT;                // ← nowdoc (comillas en el delimitador): NO interpreta nada
```

**Cuándo usarlos:** cuando tienes bloques largos (SQL, HTML, JSON de ejemplo) y no quieres estar escapando comillas.

> 📌 Resumen rápido:
> `'...'` literal · `"..."` interpola · `<<<X ... X;` interpola · `<<<'X' ... X;` literal.

---

## 2. Acceder y modificar cadenas

Con `$text = "Hello PHP World";`:

### 2.1 Acceder a un carácter (como si fuera un array)

```php
echo $text[0];    // H  (primer carácter, índices desde 0)
echo $text[6];    // P
```

### 2.2 Longitud y conteos

```php
echo strlen($text);          // 16  → cuenta caracteres
echo str_word_count($text);  // 3   → cuenta palabras
```

### 2.3 Mayúsculas y minúsculas

```php
echo strtoupper($text);   // HELLO PHP WORLD
echo strtolower($text);   // hello php world
```

### 2.4 Reemplazar

```php
echo str_replace("PHP", "Laravel", $text);
// Hello Laravel World
```

### 2.5 Extraer un trozo (`substr`)

```php
echo substr($text, 6, 3);
//          inicio ↑   ↑ cantidad de caracteres
// PHP
```

### 2.6 Buscar posición (`strpos`)

```php
echo strpos($text, "PHP");   // 6 → índice donde empieza
```

> ⚠️ **Trampa clásica:** si no lo encuentra devuelve `false`, y `false` es igual a `0` con `==`.
> Compara **siempre** de forma estricta:
> ```php
> if (strpos($text, "PHP") !== false) { ... }
> ```

### 2.7 Quitar espacios (`trim`)

```php
$dirty = "   Hello World   ";
echo trim($dirty);   // "Hello World" (quita espacios a izquierda y derecha)
```

También existen `ltrim()` (izquierda) y `rtrim()` (derecha).

### 2.8 String → array y array → string

```php
// Cortar por separador
$items = explode(",", "apple,banana,orange");
print_r($items);
// Array ( [0] => apple [1] => banana [2] => orange )

// Unir con separador
echo implode(" - ", ["apple", "banana", "orange"]);
// apple - banana - orange
```

> `explode` / `implode` son de las funciones que más vas a usar en la vida real (CSV, query strings, rutas...).

---

## 3. Dar formato al texto

### 3.1 `printf` → imprime formateado

```php
$name = "Ana";
$age = 25;

printf("Nombre: %s | Edad: %d\n", $name, $age);
// Nombre: Ana | Edad: 25
```

| Placeholder | Tipo |
|-------------|------|
| `%s` | string |
| `%d` | entero |
| `%f` | decimal |
| `%%` | signo `%` literal |

### 3.2 `sprintf` → igual pero **devuelve** el string

```php
$price = 19.99;
$result = sprintf("El precio es $%.2f", $price);
//  %.2f = decimal con 2 cifras → "El precio es $19.99"
echo $result;
```

### 3.3 `number_format` → números con formato

```php
echo number_format(1234567.891, 2);                       // 1,234,567.89
echo number_format(1234567.891, 2, ".", ",");             // 1,234,567.89 (explícito)
echo number_format(1999.5678, 2);                         // 1,999.57
```

### 3.4 `str_pad` → rellenar hasta un ancho

```php
echo str_pad("PHP", 10) . "END\n";                             // PHP       END
echo str_pad("PHP", 10, "0", STR_PAD_LEFT) . "\n";             // 0000000PHP
```

Útil para alinear reportes o numerar con ceros.

### 3.5 Interpolación avanzada con llaves

```php
$user = "Ana";
$plan = "Pro";

echo "Usuario: {$user} | Plan: {$plan}\n";
// Usar {} deja claro dónde empieza y acaba la variable
```

### 3.6 Fechas con `DateTime`

```php
$date = new DateTime();
echo $date->format("d-m-Y H:i:s") . "\n";
```

| Código | Significado | Ejemplo |
|--------|-------------|---------|
| `d` | día 2 dígitos | `13` |
| `m` | mes | `06` |
| `Y` | año 4 dígitos | `2026` |
| `H` | hora 24h | `12` |
| `i` | minutos | `30` |
| `s` | segundos | `00` |

### 3.7 JSON (fundamental para APIs)

```php
$data = ["name" => "Ana", "age" => 25, "active" => true];

echo json_encode($data, JSON_PRETTY_PRINT);
```

Salida:

```json
{
    "name": "Ana",
    "age": 25,
    "active": true
}
```

- `json_encode()` → array/objeto **→** texto JSON.
- `json_decode($texto, true)` → texto JSON **→** array (con `true`) u objeto.

---

## 4. Codificación y seguridad (muy importante)

Fichero: `advanced_strings_encoding.php`.

### 4.1 Base64 → transporte de datos

```php
$text = "Hola PHP moderno";

$encoded = base64_encode($text);      // texto → base64
echo $encoded;

$decoded = base64_decode($encoded);   // base64 → texto
echo $decoded;
```

> ⚠️ **Base64 NO es cifrado**: cualquiera lo puede decodificar. Es para **transportar** datos (binarios, tokens simples), no para **proteger**los.

### 4.2 URL encoding → no romper URLs

```php
$query = "php & laravel saas";
$finalUrl = "https://site.com/search?q=" . urlencode($query);
// https://site.com/search?q=php+%26+laravel+saas
```

Los espacios y símbolos como `&`, `?`, `#` rompen una URL si los dejas tal cual.
`urlencode()` / `urldecode()` arreglan eso. (El navegador hace esto solo al enviar formularios.)

### 4.3 Escapar HTML → evitar XSS

```php
$userInput = "<script>alert('hack')</script>";

echo htmlspecialchars($userInput);
// &lt;script&gt;alert('hack')&lt;/script&gt;  → se ve como TEXTO, no se ejecuta
```

> **Lección de seguridad:** **nunca** imprimas datos que vengan del usuario sin pasarlos por `htmlspecialchars()`.
> Si no, estás abierto a **XSS** (Cross-Site Scripting), uno de los ataques más comunes de la web.
> En Laravel esto lo hace el motor de plantillas *Blade* automáticamente.

### 4.4 Salida cruda vs escapada

```php
$html = "<h1>Hola Mundo</h1>";

echo $html;                          // el navegador lo pinta como título
echo htmlspecialchars($html);        // se muestra el texto "<h1>Hola Mundo</h1>"
```

---

## 5. Buscar texto y expresiones regulares

Fichero: `searching_regex.php`.

### 5.1 Búsqueda simple

```php
$text = "El usuario ana@email.com se registró el 2026-06-13";

$pos = strpos($text, "usuario");
if ($pos !== false) {
    echo "Encontrado en posición: $pos\n";
}
```

### 5.2 `str_contains` (PHP 8) → mucho más legible

```php
if (str_contains($text, "email.com")) {
    echo "Contiene email\n";
}
```

También: `str_starts_with()` y `str_ends_with()`.

### 5.3 Expresiones regulares: `preg_match`

```php
// Busca el primer email que encuentre
if (preg_match("/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-z]{2,}/", $text, $matches)) {
    echo "Email encontrado: " . $matches[0] . "\n";
}
```

Anatomía de un patrón: **va entre `/.../`** y cada patrón tiene "inicio y fin de búsqueda".

| Patrón | Busca |
|--------|-------|
| `/\d{4}-\d{2}-\d{2}/` | una fecha `2026-06-13` |
| `/[a-z]+/` | una o más letras minúsculas |
| `/^[\w.%+-]+@[\w.-]+\.[a-zA-Z]{2,}$/` | un email completo (de principio `^` a fin `$`) |

Atajos útiles: `\d` dígito · `\w` letra/dígito/_ · `+` una o más veces · `{2,}` dos o más · `^` inicio · `$` fin.

### 5.4 Reemplazar con regex → `preg_replace`

```php
// Oculta el email en un log (buena práctica de privacidad)
$hidden = preg_replace("/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-z]{2,}/", "***@***.com", $text);
echo $hidden;
```

### 5.5 Todos los coincidentes → `preg_match_all`

```php
$text2 = "Emails: a@test.com, b@test.com, c@test.com";

preg_match_all("/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-z]{2,}/", $text2, $allEmails);

print_r($allEmails[0]);   // array con los 3 emails
```

> **Consejo senior:** usa regex solo cuando `str_contains` / `explode` no llegan.
> Una regex ilegible se mantiene peor que diez `if`.

---

## ✅ Ejercicios

1. Dado `$full = "Ana García"`, imprime solo `Ana` y solo `García`.
2. Pon `email: ana@mail.com` en mayúsculas **sin tocar** el email.
3. Convierte `["lunes","martes"]` en el texto `lunes, martes` (pista: `implode`).
4. Escapa `<b>hola</b>` y comprueba la salida con `echo`.
5. Extrae con regex la fecha de: `"Envío el 2026-10-12"`.

---

**Siguiente:** [03 · Números](03-numeros.md)
