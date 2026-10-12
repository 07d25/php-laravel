# 00 · Cómo instalar PHP y ejecutar los ejemplos

> Objetivo: que puedas escribir `php archivo.php` en tu terminal y ver resultados. Sin esto, nada más funciona.

---

## 1. ¿Qué es PHP?

PHP es un **lenguaje de programación que se ejecuta en el servidor** (por eso se llama "backend").
Tú escribes un archivo `.php`, lo ejecuta **PHP en la terminal o en un servidor web**, y el resultado puede ser texto, datos en JSON, una página web, etc.

Características que te importan al empezar:

- Los archivos terminan en `.php`.
- El código empieza siempre con la etiqueta `<?php`.
- Es **interpretado**: no hay que compilar, guardas y ejecutas.
- Es **tipado dinámico**, pero admite **tipado estricto** (lo veremos en la ruta).

---

## 2. Instalar PHP

### Linux (Debian/Ubuntu)

```bash
sudo apt update
sudo apt install php-cli
```

### macOS

```bash
brew install php
```

### Windows

Descarga el instalador desde <https://windows.php.net/download/> y **marca la opción "Add PHP to PATH"**.

---

## 3. Verificar la instalación (y el PATH)

```bash
php -v
```

Salida esperada:

```
PHP 8.x.x (cli) ...
```

### Si sale "command not found: php"

Tu sistema no sabe dónde está el ejecutable. Tienes que agregarlo al `PATH`:

```bash
# 1) Localizar dónde está PHP
which php          # Linux/macOS
where php          # Windows (CMD)

# 2) Agregar la carpeta al PATH (Linux/macOS) en tu ~/.bashrc o ~/.zshrc
export PATH="$PATH:/ruta/donde/esta/php"

# 3) Recargar
source ~/.bashrc   # o source ~/.zshrc
```

---

## 4. Tu primer archivo

Crea `hola.php`:

```php
<?php

// Los comentarios con // no se ejecutan: son para humanos.
echo "Hola, mundo!\n";   // echo imprime texto en pantalla
```

Ejecútalo:

```bash
php hola.php
```

Salida:

```
Hola, mundo!
```

**Ya programaste en PHP.** 🎉 El resto del repo es variación sobre esto.

---

## 5. Comandos que vas a usar todo el tiempo

| Comando | Para qué sirve |
|---------|----------------|
| `php archivo.php` | Ejecutar un script |
| `php -l archivo.php` | **L**int: revisa errores de sintaxis sin ejecutarlo |
| `php -v` | Versión de PHP |
| `php -r 'echo 1+1;'` | Ejecutar una línea suelta |

Ejemplo real de uso del linter:

```bash
$ php -l data-types.php
No syntax errors detected in data-types.php
```

> **Truco:** si tu script falla, primero `php -l` para descartar errores de tipeo.

---

## 6. Entender la salida de un error

Rompe algo a propósito (borra un `;`) y ejecuta:

```bash
php data-types.php
```

PHP te dirá algo como:

```
PHP Parse error:  syntax error, unexpected end of file in data-types.php on line 42
```

**Lee siempre: qué pasó + en qué archivo + en qué línea.** Con eso llegas lejos.

---

## 7. Formas de "mirar" una variable

Son tus herramientas de depuración:

```php
$fruits = ["apple", "banana"];

echo $fruits[0];   // imprime un valor simple: apple
print_r($fruits);  // imprime arrays de forma legible
var_dump($fruits); // igual que print_r PERO además muestra tipo y longitud
```

Salida de `var_dump()`:

```
array(2) {
  [0]=>
  string(5) "apple"
  [1]=>
  string(6) "banana"
}
```

---

## 8. Ejecutar los ejemplos de este repo

Si estás dentro de la carpeta del repo:

```bash
php expressions.php
php arrays/arrays_filter.php
php functions/recursion.php
php classes/main.php
```

Algunos ejemplos **piden datos por teclado** (el juego):

```bash
php guess-secret-game.php
```

Escribe tu número y pulsa **Enter**.

---

## 9. (Opcional) Habilitar el modo estricto

Al inicio de un archivo puedes declarar:

```php
<?php
declare(strict_types=1);
```

Con esto, PHP **deja de convertir tipos automáticamente** y lanza un error si le pasas un `string` donde espera un `int`. Es la forma de escribir código profesional: los errores aparecen **temprano**.

Lo usaremos sobre todo en funciones y clases.

---

## ✅ Checklist antes de continuar

- [ ] `php -v` muestra PHP 8.1 o superior.
- [ ] Puedes crear un archivo y ejecutarlo con `php archivo.php`.
- [ ] Sabes usar `php -l` para revisar errores.
- [ ] Sabes qué hacen `echo`, `print_r()` y `var_dump()`.

**Siguiente:** [01 · Sintaxis, variables y tipos de datos](01-sintaxis-y-tipos.md)
