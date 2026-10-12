<?php

// ===============================
// FUNCIONES DE ORDEN SUPERIOR
// ===============================

function applyOperation($a, $b, $operation) {
    return $operation($a, $b);
}

$sum = function($x, $y) {
    return $x + $y;
};

echo applyOperation(5, 3, $sum); // 8
