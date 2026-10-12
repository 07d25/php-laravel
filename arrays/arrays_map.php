<?php

// ===============================
// TRANSFORMAR ARRAY (array_map)
// ===============================

$numbers = [1, 2, 3, 4];

// multiplicar por 2
$result = array_map(function ($n) {
    return $n * 2;
}, $numbers);

print_r($result);
