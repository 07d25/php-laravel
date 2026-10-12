<?php

// ===============================
// FUNCIONES GENERADORAS (yield)
// ===============================

// yield = no guarda todo en memoria
function numbers() {
    for ($i = 1; $i <= 5; $i++) {
        yield $i;
    }
}

// Iteración eficiente
foreach (numbers() as $num) {
    echo $num . "\n";
}