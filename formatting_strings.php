<?php

// ===============================
// 1. printf (formateo clásico)
// ===============================

$name = "Ana";
$age = 25;

// %s = string, %d = integer
printf("Nombre: %s | Edad: %d\n", $name, $age);

// ===============================
// 2. sprintf (devuelve string)
// ===============================

$price = 19.99;

$result = sprintf("El precio es $%.2f", $price);

echo $result . "\n";

// ===============================
// 3. number_format (formato de números)
// ===============================

$number = 1234567.891;

echo number_format($number, 2);
// 1,234,567.89

echo "\n";

// ===============================
// 4. str_pad (rellenar string)
// ===============================

$text = "PHP";

// Rellena a la derecha con espacios hasta 10 caracteres
echo str_pad($text, 10) . "END\n";

// Rellena con ceros a la izquierda
echo str_pad($text, 10, "0", STR_PAD_LEFT) . "\n";

// ===============================
// 5. HEREDOC (formato multilínea)
// ===============================

$name = "Ana";

$html = <<<HTML
<h1>Hola $name</h1>
<p>Bienvenida al sistema</p>
HTML;

echo $html;

// ===============================
// 6. INTERPOLACIÓN AVANZADA
// ===============================

$user = "Ana";
$plan = "Pro";

echo "Usuario: {$user} | Plan: {$plan}\n";

// ===============================
// 7. FORMATO DE FECHAS
// ===============================

$date = new DateTime();

echo $date->format("d-m-Y H:i:s") . "\n";
// 2026-06-13 12:30:00 (ejemplo)

// ===============================
// 8. JSON (muy usado en APIs)
// ===============================

$data = [
    "name" => "Ana",
    "age" => 25,
    "active" => true
];

echo json_encode($data, JSON_PRETTY_PRINT);

