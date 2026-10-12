<?php

// ===============================
// INTERSECCIÓN DE ARRAYS (array_intersect)
// ===============================

$a = ["apple", "banana", "orange"];
$b = ["banana", "grape", "apple"];

// Elementos en común
$result = array_intersect($a, $b);

print_r($result);
// apple, banana
