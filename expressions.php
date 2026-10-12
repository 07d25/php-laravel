<?php

// Imprime un mensaje de bienvenida
echo "Hello, World!\n";
echo "Welcome to PHP programming.\n";

// =========================
// Variables
// =========================

// Declaración de variables
$name = "Alice";
$age = 30;

// Interpolación de variables dentro de una cadena
echo "My name is $name and I am $age years old.\n";

// =========================
// Concatenación de cadenas
// =========================

// Une textos usando el operador .
$greeting = "Hello, " . $name . "!";
echo $greeting . "\n";

// =========================
// Operaciones aritméticas
// =========================

// Cantidad de pizzas
$pizzas = 3;

// Porciones por pizza
$slicesPerPizza = 8;

// Multiplicación
$totalSlices = $pizzas * $slicesPerPizza;

echo "Total slices of pizza: $totalSlices\n";

// =========================
// Operadores de comparación
// =========================

// Verifica si la edad es mayor a 18
if ($age > 18) {
    echo "You are an adult.\n";
} else {
    echo "You are a minor.\n";
}

// =========================
// Operadores lógicos
// =========================

// Variable booleana (verdadero o falso)
$isStudent = true;

// && significa AND (Y)
// Ambas condiciones deben ser verdaderas
if ($isStudent && $age < 25) {
    echo "You are a student under 25.\n";
} else {
    echo "You are either not a student or over 25.\n";
}

// =========================
// Ejemplos de valores booleanos
// =========================

// true && false = false
// No imprime nada porque false equivale a vacío
echo (true && false);

// true || false = true
// Imprime 1 porque true se convierte en 1
echo (true || false);

// !true = false
// No imprime nada
echo (!true);

// !false = true
// Imprime 1
echo (!false);

// =========================
// Resumen de operadores
// =========================

// &&  AND  -> ambas condiciones deben ser verdaderas
// ||  OR   -> al menos una condición debe ser verdadera
// !   NOT  -> invierte el valor booleano
//
// true  = 1
// false = vacío al usar echo