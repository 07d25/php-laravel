<?php

// ===============================
// TIPOS UNIÓN (union types)
// ===============================

// Puede aceptar string o int
function process(string|int $value) {
    return "Recibido: " . $value;
}

echo process("Hola");
echo "\n";
echo process(123);