<?php

// ===============================
// PASO DE VALOR (por defecto)
// ===============================

// La función recibe una COPIA del valor
function changeValue($num) {
    $num = 100; // solo cambia la copia
}

$x = 10;

changeValue($x);

// El valor original NO cambia
echo $x; // 10