<?php

// ===============================
// RECURSIÓN
// ===============================

// Factorial: 5! = 5*4*3*2*1
function factorial($n) {
    if ($n <= 1) {
        return 1; // caso base
    }

    return $n * factorial($n - 1);
}

echo factorial(5); // 120

// ¿Qué es la Recursión?
// La recursión es una técnica donde una función se llama a sí misma para resolver un problema.
// Para que no se quede llamándose infinitamente (y rompa tu programa), toda función recursiva necesita obligatoriamente dos partes:
// Caso base (El freno): Es la condición que detiene las llamadas. 
// Le dice a la función: "¡Para! Ya llegamos al final, ahora regresa".
// Caso recursivo (El paso): Es la parte donde la función se llama a sí misma, pero con un problema más pequeño que el anterior para acercarse al caso base.