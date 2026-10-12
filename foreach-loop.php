<?php

// ===============================
// CARRITO DE COMPRAS (BASKET)
// ===============================

// Array asociativo simulando un carrito
$basket = [
    "apple" => 3,
    "banana" => 5,
    "orange" => 2,
    "milk" => 1
];

// Variable para contar el total de items
$totalItems = 0;

// ===============================
// BUCLE FOREACH
// ===============================

// Recorremos cada producto del carrito
// $product = clave (key)
// $quantity = valor (value)
foreach ($basket as $product => $quantity) {

    echo "Producto: $product | Cantidad: $quantity\n";

    // Sumamos la cantidad al total
    $totalItems += $quantity;
}

// ===============================
// RESULTADO FINAL
// ===============================

echo "=====================\n";
echo "Total de items: $totalItems\n";
