# Funciones — 17 ejemplos

> 📖 **Guía completa y explicada paso a paso:** [docs/06-funciones.md](../docs/06-funciones.md)
> 💻 **Código en inglés, comentarios en español.**

Una **función** es un trozo de código con nombre que puedes ejecutar tantas veces como quieras.

```php
function greet(string $name): string {
    return "Hello, $name!";
}

echo greet("Alice");   // Hello, Alice!
```

## Cómo ejecutarlos

```bash
php functions/function.php
php functions/recursion.php
# ...o cualquiera de los de abajo
```

## Índice de archivos

### Fundamentos
| Archivo | Concepto |
|---------|----------|
| `function.php` | función básica |
| `types-func.php` | tipos en parámetros y en el retorno |
| `optional-func-args.php` | argumentos con valor por defecto |
| `null_in_php.php` | manejo de `null` |

### PHP 8
| Archivo | Concepto |
|---------|----------|
| `named_arguments.php` | argumentos con nombre (`role: "admin"`) |
| `union_types.php` | tipos unión (`string\|int`) |

### Valores, referencias y ámbito
| Archivo | Concepto |
|---------|----------|
| `pass_by_value.php` | recibe una **copia** (por defecto) |
| `pass_by_reference.php` | `&` modifica el **original** |
| `performance_value_vs_reference.php` | coste de copiar arrays grandes |
| `variable_scope.php` | local / `global` / `static` |
| `pure_functions.php` | funciones puras (mismo input → mismo output) |

### Funciones como valores
| Archivo | Concepto |
|---------|----------|
| `anonymous-func.php` | funciones anónimas (closures) |
| `arrow-func.php` | `fn() => ...` (PHP 7.4+) |
| `higher_order_functions.php` | funciones que reciben/devuelven funciones |
| `variadic-func-args.php` | `...$args` (argumentos ilimitados) |

### Técnicas avanzadas
| Archivo | Concepto |
|---------|----------|
| `recursion.php` | recursión: caso base + caso recursivo |
| `generator_functions.php` | `yield` → no cargar todo en memoria |

## Resumen en una frase

> Escribe funciones **pequeñas, puras y tipadas**; pásale los datos como parámetros en lugar de usar `global`.

**Siguiente paso:** abrir la [guía completa](../docs/06-funciones.md) y hacer los ejercicios del final.
