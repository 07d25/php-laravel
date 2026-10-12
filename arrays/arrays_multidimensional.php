<?php

// ===============================
// ARRAY MULTIDIMENSIONAL
// ===============================

$users = [
    ["name" => "Ana", "age" => 25],
    ["name" => "Luis", "age" => 30],
    ["name" => "Carlos", "age" => 28]
];

// acceso
echo $users[0]["name"] . "\n";

// recorrer
foreach ($users as $user) {
    echo $user["name"] . " - " . $user["age"] . "\n";
}
