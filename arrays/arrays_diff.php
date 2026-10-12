<?php

// ===============================
// DIFERENCIA DE ARRAYS (array_diff)
// ===============================

$array1 = ["apple", "banana", "orange"];
$array2 = ["banana", "grape"];

// Elementos que están en array1 pero NO en array2
$result = array_diff($array1, $array2);

print_r($result);
// apple, orange
