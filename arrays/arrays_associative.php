<?php

// ===============================
// ARRAY ASOCIATIVO (clave => valor)
// ===============================

$user = [
    "name" => "Ana",
    "age" => 25,
    "role" => "admin"
];

// acceso por clave
echo $user["name"] . "\n";

// recorrer
foreach ($user as $key => $value) {
    echo "$key: $value\n";
}
