<?php

// ===============================
// ARGUMENTOS OPCIONALES
// ===============================

function greet(string $name = "Enciso"): string {
    return "Hello, $name!";
}

// Llamadas a la función

echo greet("Alice") . "\n";  // usa "Alice"
echo greet() . "\n";         // usa "Enciso"
