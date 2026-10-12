<?php

// ===============================
// PRUEBA DE RENDIMIENTO
// VALOR vs REFERENCIA
// ===============================

// Array grande simulado
$bigArray = range(1, 100000);

// -------------------------------
// POR VALOR (por defecto)
// -------------------------------
function processByValue($data)
{
    foreach ($data as $item) {
        // solo lectura
    }
}

processByValue($bigArray);


// -------------------------------
// POR REFERENCIA
// -------------------------------
function processByReference(&$data)
{
    foreach ($data as $item) {
        // sin copiar estructura
    }
}

processByReference($bigArray);