<?php

// ===============================
// DESEMPAQUETADO DE ARRAYS (PHP 7.4+)
// ===============================

$a = [1, 2, 3];
$b = [4, 5, 6];

// expandir arrays dentro de otro
$result = [...$a, ...$b];

print_r($result);
