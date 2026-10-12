<?php

// ===============================
// ARGUMENTOS CON NOMBRE
// PHP 8+
// ===============================

function createUser(string $name, int $age, string $role) {
    return "$name - $age - $role";
}

// No importa el orden
echo createUser(
    role: "admin",
    age: 25,
    name: "Ana"
);
