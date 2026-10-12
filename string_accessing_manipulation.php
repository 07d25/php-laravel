<?php

// ===============================
// STRING: BASE
// ===============================

$text = "Hello PHP World";

// ===============================
// 1. ACCEDER A UN CARÁCTER
// ===============================

// Los strings en PHP son como arrays de caracteres
echo $text[0]; // H (primer carácter)
echo "\n";

echo $text[6]; // P
echo "\n";

// ===============================
// 2. LONGITUD DE STRING
// ===============================

echo strlen($text); 
// cuenta caracteres
echo "\n";

// ===============================
// 3. CONTAR PALABRAS
// ===============================

echo str_word_count($text); 
// cuenta palabras
echo "\n";

// ===============================
// 4. CAMBIAR A MAYÚSCULAS / MINÚSCULAS
// ===============================

echo strtoupper($text);
echo "\n";

echo strtolower($text);
echo "\n";

// ===============================
// 5. REEMPLAZAR TEXTO
// ===============================

echo str_replace("PHP", "Laravel", $text);
// Hello Laravel World
echo "\n";

// ===============================
// 6. EXTRAER SUBSTRING
// ===============================

echo substr($text, 6, 3); 
// PHP (empieza en índice 6, toma 3 chars)
echo "\n";

// ===============================
// 7. BUSCAR POSICIÓN DE TEXTO
// ===============================

echo strpos($text, "PHP"); 
// devuelve posición donde empieza "PHP"
echo "\n";

// ===============================
// 8. ELIMINAR ESPACIOS
// ===============================

$dirty = "   Hello World   ";

echo trim($dirty);
// elimina espacios al inicio y final
echo "\n";

// ===============================
// 9. DIVIDIR STRING EN ARRAY
// ===============================

$csv = "apple,banana,orange";

$items = explode(",", $csv);

print_r($items);

// ===============================
// 10. UNIR ARRAY EN STRING
// ===============================

$list = ["apple", "banana", "orange"];

echo implode(" - ", $list);
// apple - banana - orange
echo "\n";