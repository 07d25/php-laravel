<?php

// ===============================
// EJEMPLO REAL COMBINADO
// ===============================

$products = [
    ["name" => "Laptop", "price" => 1000],
    ["name" => "Mouse", "price" => 50],
    ["name" => "Keyboard", "price" => 150]
];

// FILTRAR productos caros
$expensive = array_filter($products, function ($p) {
    return $p["price"] > 100;
});

// MAP: obtener solo precios
$prices = array_map(function ($p) {
    return $p["price"];
}, $products);

// REDUCE: total de precios
$total = array_reduce($prices, function ($carry, $item) {
    return $carry + $item;
}, 0);

echo "Total: $total\n";
