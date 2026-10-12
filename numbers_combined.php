<?php

// ===============================
// EJEMPLO REAL COMBINADO
// ===============================

$prices = [10.5, 20.99, 5.75, 100.3];

// total
$total = array_sum($prices);

// mínimo y máximo
$min = min($prices);
$max = max($prices);

// promedio
$average = $total / count($prices);

// output formateado
echo "Total: " . number_format($total, 2) . "\n";
echo "Min: $min\n";
echo "Max: $max\n";
echo "Average: " . number_format($average, 2) . "\n";
