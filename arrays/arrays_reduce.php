<?php

// ===============================
// ACUMULAR ARRAY (array_reduce)
// ===============================

$numbers = [1, 2, 3, 4];

// suma total
$total = array_reduce($numbers, function ($carry, $item) {
    return $carry + $item;
}, 0);

echo $total . "\n";
