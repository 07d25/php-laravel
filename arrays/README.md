# Arrays — 16 ejemplos

> 📖 **Guía completa y explicada paso a paso:** [docs/05-arrays.md](../docs/05-arrays.md)
> 💻 **Código en inglés, comentarios en español.**

En PHP el **array** es la estructura de datos más importante: sirve como lista, diccionario, tabla...

## Cómo ejecutarlos

```bash
php arrays/arrays_indexed.php
php arrays/arrays_filter.php
# ...o cualquiera de los de abajo
```

## Índice de archivos

### Los 3 tipos básicos
| Archivo | Concepto |
|---------|----------|
| `arrays_indexed.php` | array indexado (lista por posición) |
| `arrays_associative.php` | array asociativo (clave ⇒ valor) |
| `arrays_multidimensional.php` | arrays anidados (tablas) |

### Consultar y extraer
| Archivo | Concepto |
|---------|----------|
| `arrays_keys.php` | `array_keys()` → solo claves |
| `arrays_values.php` | `array_values()` → solo valores |
| `arrays_sort.php` | `sort()` / `rsort()` → ordenar |

### Combinar
| Archivo | Concepto |
|---------|----------|
| `arrays_merge.php` | `array_merge()` → unir |
| `arrays_diff.php` | `array_diff()` → lo que sobra |
| `arrays_intersect.php` | `array_intersect()` → lo común |
| `arrays_combined_real.php` | merge + unique + intersect (caso real) |

### Las 3 funciones estrella: map · filter · reduce
| Archivo | Concepto |
|---------|----------|
| `arrays_map.php` | **transformar** cada elemento |
| `arrays_filter.php` | **seleccionar** los que cumplen |
| `arrays_reduce.php` | **acumular** todo en un valor |
| `arrays_combined.php` | map + filter + reduce en un caso real |

### Sintaxis moderna
| Archivo | Concepto |
|---------|----------|
| `arrays_unpacking.php` | `...$a` dentro de otro array (PHP 7.4+) |
| `arrays_unpacking_functions.php` | `...$array` como argumentos de función |

## Resumen en una frase

> `map` transforma · `filter` selecciona · `reduce` resume · `merge` une · `diff` resta · `intersect` cruza.

**Siguiente paso:** abrir la [guía completa](../docs/05-arrays.md) y hacer los ejercicios del final.
