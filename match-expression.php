<?php

// ===============================
// MATCH: HTTP ERROR CODES
// ===============================

$httpCode = 201;

// Match para códigos HTTP
$message = match ($httpCode) {

    200 => "OK - La petición fue exitosa",
    201 => "Created - Recurso creado correctamente",
    400 => "Bad Request - Error en la solicitud",
    401 => "Unauthorized - No autenticado",
    403 => "Forbidden - No tienes permisos",
    404 => "Not Found - Recurso no encontrado",
    500 => "Internal Server Error - Error del servidor",
    default => "Código HTTP desconocido"
};

echo $message . "\n";


// ===============================
// MATCH: TALLA DE ROPA
// ===============================

$size = "M";

// Match para tallas
$msg = match ($size) {

    "XS" => "Talla extra pequeña (XS)",
    "S" => "Talla pequeña (S)",
    "M" => "Talla mediana (M)",
    "L" => "Talla grande (L)",
    "XL" => "Talla extra grande (XL)",
    default => "Talla no reconocida"
};

echo $msg . "\n";
