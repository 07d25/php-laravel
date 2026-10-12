<?php

// ===============================
// SWITCH: TALLA DE ROPA
// ===============================

// Variable con la talla del usuario
$size = "M";

// ===============================
// SENTENCIA SWITCH
// ===============================

switch ($size) {

    case "XS":
        echo "Talla extra pequeña (XS)\n";
        break;

    case "S":
        echo "Talla pequeña (S)\n";
        break;

    case "M":
        echo "Talla mediana (M)\n";
        break;

    case "L":
        echo "Talla grande (L)\n";
        break;

    case "XL":
        echo "Talla extra grande (XL)\n";
        break;

    // Caso por defecto si no coincide ninguna talla
    default:
        echo "Talla no reconocida\n";
        break;
}
