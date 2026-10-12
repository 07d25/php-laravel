<?php

// ===============================
// TEXTO BASE
// ===============================

$text = "El usuario ana@email.com se registró el 2026-06-13";

// ===============================
// 1. BUSCAR TEXTO (strpos)
// ===============================

// Devuelve la posición donde empieza el texto
$pos = strpos($text, "usuario");

if ($pos !== false) {
    echo "Encontrado en posición: $pos\n";
} else {
    echo "No encontrado\n";
}

// ===============================
// 2. BUSCAR SI EXISTE TEXTO (str_contains - PHP 8+)
// ===============================

if (str_contains($text, "email.com")) {
    echo "Contiene email\n";
}

// ===============================
// 3. EXPRESIONES REGULARES (preg_match)
// ===============================

// Buscar email en el texto
if (preg_match("/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-z]{2,}/", $text, $matches)) {

    echo "Email encontrado: " . $matches[0] . "\n";
}

// ===============================
// 4. EXTRAER FECHA CON REGEX
// ===============================

// Formato YYYY-MM-DD
if (preg_match("/\d{4}-\d{2}-\d{2}/", $text, $matches)) {

    echo "Fecha encontrada: " . $matches[0] . "\n";
}

// ===============================
// 5. REEMPLAZAR CON REGEX (preg_replace)
// ===============================

// Ocultar email por seguridad
$hidden = preg_replace(
    "/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-z]{2,}/",
    "***@***.com",
    $text
);

echo $hidden . "\n";

// ===============================
// 6. EXTRAER TODOS LOS MATCHES (preg_match_all)
// ===============================

$text2 = "Emails: a@test.com, b@test.com, c@test.com";

preg_match_all(
    "/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-z]{2,}/",
    $text2,
    $allEmails
);

print_r($allEmails[0]);

// ===============================
// 7. VALIDAR FORMATO (EMAIL SIMPLE)
// ===============================

$email = "test@example.com";

if (preg_match("/^[\w.%+-]+@[\w.-]+\.[a-zA-Z]{2,}$/", $email)) {
    echo "Email válido\n";
} else {
    echo "Email inválido\n";
}