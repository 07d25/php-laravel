<?php

// ===============================
// DESEMPAQUETADO EN FUNCIONES
// ===============================

function sum($a, $b, $c) {
    return $a + $b + $c;
}

$values = [10, 20, 30];

// ... expande el array como argumentos
echo sum(...$values);