<?php

// ===============================
// PHP MODERNO - BUCLES WHILE
// ===============================


// ===============================
// 1. WHILE básico
// ===============================

// Inicializamos un contador
$i = 1;

// Mientras la condición sea verdadera, el loop se ejecuta
while ($i <= 5) {
    echo "Iteración número: $i\n";

    // IMPORTANTE: incrementar para evitar loop infinito
    $i++;
}


// ===============================
// 2. WHILE con condición en datos
// ===============================

$usuarios = ["Ana", "Luis", "Carlos"];

// índice inicial
$index = 0;

// count() obtiene el tamaño del array
while ($index < count($usuarios)) {
    echo "Usuario: " . $usuarios[$index] . "\n";

    $index++;
}


// ===============================
// 3. WHILE infinito con break
// ===============================

$contador = 1;

while (true) {
    echo "Contador: $contador\n";

    $contador++;

    // Condición de salida manual
    if ($contador > 3) {
        break; // rompe el loop
    }
}


// ===============================
// 4. DO WHILE (se ejecuta al menos una vez)
// ===============================

$j = 1;

do {
    echo "Do While número: $j\n";
    $j++;
} while ($j <= 3);


// ===============================
// 5. WHILE con continue
// ===============================

$k = 0;

while ($k < 5) {
    $k++;

    // Saltar el número 3
    if ($k == 3) {
        continue; // salta esta iteración
    }

    echo "Número: $k\n";
}

