<?php

// ===============================
// 1. COMILLAS SIMPLES (' ')
// ===============================
// No interpreta variables dentro

$name = "Ana";

echo 'Hola $name';
// salida: Hola $name (literal)

// ===============================
// 2. COMILLAS DOBLES (" ")
// ===============================
// Sí interpreta variables y admite \n, \t, etc.

echo "Hola $name!\n";
// salida: Hola Ana (interpreta la variable)

// ===============================
// 3. HEREDOC (texto multilínea, interpola)
// ===============================
// Funciona como comillas dobles: $name se sustituye.

$heredoc = <<<EOD
Multi-line string
with variable: $name
EOD;

// ===============================
// 4. NOWDOC (texto multilínea, literal)
// ===============================
// El delimitador va entre comillas simples: NO sustituye nada.

$nowdoc = <<<'TEXT'
Hola $name
Esto es texto literal
TEXT;

echo $heredoc . "\n";  // Interpreta variables → "with variable: Ana"
echo $nowdoc . "\n";   // No interpreta variables (sale "$name" tal cual)