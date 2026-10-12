# Aprendiendo PHP desde cero 🐘

> Repositorio de aprendizaje: **PHP moderno (8.x)** + una app de ejemplo en **Laravel**.
> Todo el **código está en inglés** (como en la vida real) y **los comentarios y esta documentación están en español**.

---

## ¿Qué es este repo?

Es un conjunto de **ejemplos sueltos y diminutos**, uno por concepto. Cada archivo hace **una sola cosa** y se puede ejecutar por separado. No hay que instalar nada raro: basta con tener PHP en la terminal.

Además, dentro de `task-list/` hay una aplicación **Laravel** completa (framework profesional que usan empresas reales) para que veas a dónde lleva todo esto.

---

## Requisitos previos

1. **PHP 8.1 o superior** instalado y agregado al `PATH` (para poder escribir `php` en la terminal).
2. Un editor de código (recomendado: [VS Code](https://code.visualstudio.com/)).
3. (Opcional) **Composer** para la parte de Laravel.

Comprueba que todo está bien con:

```bash
php -v
```

Si te sale algo como `PHP 8.x.x`, estás listo. Si no, mira la guía: [docs/00-como-ejecutar.md](docs/00-como-ejecutar.md).

---

## 🗺️ Ruta de aprendizaje (sigue este orden)

No leas los archivos en orden alfabético: sigue este camino. Cada paso supone el anterior.

| # | Paso | Documentación | Archivos de práctica |
|---|------|---------------|----------------------|
| 0 | Instalar PHP y ejecutar scripts | [docs/00-como-ejecutar.md](docs/00-como-ejecutar.md) | — |
| 1 | Sintaxis, variables y tipos de datos | [docs/01-sintaxis-y-tipos.md](docs/01-sintaxis-y-tipos.md) | `expressions.php`, `data-types.php` |
| 2 | Cadenas de texto (strings) | [docs/02-strings.md](docs/02-strings.md) | `strings.php`, `string_accessing_manipulation.php`, `formatting_strings.php`, `advanced_strings_encoding.php`, `searching_regex.php` |
| 3 | Números | [docs/03-numeros.md](docs/03-numeros.md) | `numbers_*.php`, `min_max.php`, `random_numbers.php` |
| 4 | Condicionales y bucles | [docs/04-control-de-flujo.md](docs/04-control-de-flujo.md) | `conditionals.php`, `switch.php`, `match-expression.php`, `while-loop.php`, `foreach-loop.php`, `guess-secret-game.php` |
| 5 | Arrays (listas y diccionarios) | [docs/05-arrays.md](docs/05-arrays.md) | `arrays/` |
| 6 | Funciones | [docs/06-funciones.md](docs/06-funciones.md) | `functions/` |
| 7 | Programación orientada a objetos (clases) | [docs/07-poo.md](docs/07-poo.md) | `classes/` |
| 8 | Tu primera app con Laravel | [docs/08-laravel.md](docs/08-laravel.md) | `task-list/` |

---

## 📂 Estructura del repositorio

```
php-laravel/
│
├── README.md                     ← estás aquí
├── docs/                         ← documentación explicada paso a paso
│
├── expressions.php               ← variables, operadores, concatenación
├── data-types.php                ← string, int, float, bool, array, null, object
│
├── strings.php                   ← comillas simples/dobles, heredoc/nowdoc
├── string_accessing_manipulation.php  ← acceder, cortar, buscar, reemplazar
├── formatting_strings.php        ← printf, sprintf, fechas, JSON
├── advanced_strings_encoding.php ← base64, URL encode, escapar HTML (XSS)
├── searching_regex.php           ← buscar con strpos y expresiones regulares
│
├── numbers_integer_float.php     ← enteros y decimales
├── numbers_functions.php         ← abs, round, floor, ceil
├── numbers_formatting.php        ← number_format
├── numbers_combined.php          ← ejemplo real con precios
├── min_max.php                   ← mínimo y máximo
├── random_numbers.php            ← números aleatorios
│
├── conditionals.php              ← if, else, ternario, ??, ??=
├── switch.php                    ← switch clásico
├── match-expression.php          ← match de PHP 8
├── while-loop.php                ← while, do-while, break, continue
├── foreach-loop.php              ← recorrer arrays (carrito de compras)
├── guess-secret-game.php         ← mini juego interactivo por consola
│
├── arrays/                       ← 16 archivos sobre arrays
├── functions/                    ← 20 archivos sobre funciones
├── classes/                      ← 15 archivos sobre POO (+ main.php)
│       └── README.md
│
└── task-list/                    ← app Laravel de ejemplo
```

---

## ▶️ Cómo ejecutar cualquier archivo

Desde la raíz del repo:

```bash
php data-types.php
php arrays/arrays_indexed.php
php functions/recursion.php
php classes/main.php          # ejecuta todos los ejemplos de clases
```

El juego interactivo (escribe y responde en la terminal):

```bash
php guess-secret-game.php
```

---

## 💡 Consejos de quien ya ha recorrido este camino

1. **Ejecuta, rompe, corrige.** No leas pasivamente: cambia un valor, guarda y vuelve a ejecutar.
2. **Un archivo = un concepto.** Si no entiendes uno, léelo con la documentación al lado.
3. **Usa `var_dump()` y `print_r()`** para "espiar" qué hay dentro de una variable. Es tu mejor amigo al principio.
4. **El error es información.** PHP te dice archivo y línea: úsala.
5. **Cuando termines la ruta**, pasa al [docs/08-laravel.md](docs/08-laravel.md).

---

## 📖 Temas de la sección

En esta sección sentaremos las bases para trabajar con PHP moderno en el desarrollo backend.
Exploraremos la configuración para uso en la terminal, las estructuras fundamentales del lenguaje, incluyendo buenas prácticas y ejemplos claros para comenzar a programar de forma profesional.

Aquí veremos:

- ¿Qué es PHP?
- Configurar PHP en tu sistema (agregar al PATH).
- Sintaxis básica y comentarios.
- Variables y tipos de datos.
- Strings y concatenación.
- Tipado estricto con `declare(strict_types=1)`.
- Operadores en PHP y su uso en ejemplos prácticos.

Esta sección es esencial para comprender el lenguaje desde cero y dominar los fundamentos necesarios para construir aplicaciones robustas y limpias con PHP moderno.
