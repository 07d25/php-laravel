<?php

// ===============================
// ARROW FUNCTION (PHP 7.4+)
// ===============================

// Sintaxis corta con "fn"
$multiply = fn(int $a, int $b): int => $a * $b;
echo $multiply(3, 4); // 12



// usa variable externa automáticamente
$tax = 0.21;
$addTax = fn(float $price): float => $price + ($price * $tax);
echo $addTax(100); // 121