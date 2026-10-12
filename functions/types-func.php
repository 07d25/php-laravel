<?php

// ===============================
// TIPOS EN FUNCIONES
// ===============================

// Parámetros tipados + retorno tipado
function add(int $a, int $b): int {
    return $a + $b;
}

echo add(5, 10);

// Definimos una función con tipado de parámetro y retorno
function greet(string $name): string {
    return "Hello, $name!";
}


echo greet("Alice");