<?php

// ===============================
// ÁMBITO DE LAS VARIABLES (scope)
// ===============================

// Variable GLOBAL
$x = 10;

// -------------------------------
// Local Scope
// -------------------------------
function testLocal()
{
    $y = 5; // solo existe dentro de la función
    echo $y;
}

// -------------------------------
// Global keyword (no recomendado)
// -------------------------------
function testGlobal()
{
    global $x;
    echo $x;
}

// -------------------------------
// Mejor práctica: pasar parámetro
// -------------------------------
function testParameter($value)
{
    echo $value;
}

// -------------------------------
// Static variable (persistente)
// -------------------------------
function counter()
{
    static $count = 0;
    $count++;
    echo $count . "\n";
}

// Ejecuciones
testLocal();
testGlobal();
testParameter($x);

counter(); // 1
counter(); // 2
counter(); // 3

