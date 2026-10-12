<?php

// ===============================
// FUNCIÓN ANÓNIMA
// ===============================

// Funcion sin nombre - Guardamos una función en una variable
$greet = function(string $name): string {
    return "Hello, $name!";
};

// Llamamos la función
echo $greet("Alice");