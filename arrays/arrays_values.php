<?php

// ===============================
// VALORES DEL ARRAY (array_values)
// ===============================

$user = [
    "name" => "Ana",
    "age" => 25,
    "role" => "admin"
];

// solo valores (pierde las keys)
$values = array_values($user);

print_r($values);
