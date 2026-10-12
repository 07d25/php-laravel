<?php

// ===============================
// FILTRAR ARRAY (array_filter)
// ===============================

$numbers = [1, 2, 3, 4, 5, 6];

// solo pares
$even = array_filter($numbers, function ($n) {
    return $n % 2 === 0;
});

print_r($even);
