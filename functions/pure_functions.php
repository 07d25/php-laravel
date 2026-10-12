<?php

// ===============================
// FUNCIÓN PURA
// ===============================

// No modifica nada externo, siempre mismo input = mismo output
function add($a, $b) {
    return $a + $b;
}

echo add(2, 3); // 5