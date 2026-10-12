<?php

// ===============================
// CLAVES DEL ARRAY (array_keys)
// ===============================

$user = [
    "name" => "Ana",
    "age" => 25,
    "role" => "admin"
];

// solo keys
$keys = array_keys($user);

print_r($keys);
