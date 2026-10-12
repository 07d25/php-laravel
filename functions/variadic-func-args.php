<?php

// ===============================
// ARGUMENTOS VARIÁDICOS (...)
// ===============================

// Permite recibir cantidad infinita de parámetros
function sumAll(int ...$numbers): int {
    $total = 0;

    // recorremos todos los números recibidos
    foreach ($numbers as $num) {
        $total += $num;
    }

    return $total;
}

// Puedes pasar muchos valores
echo sumAll(1, 2, 3, 4, 5); // 15
