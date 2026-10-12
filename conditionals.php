<?php

// ===============================
// PHP MODERNO - CONDICIONALES
// ===============================

// Variable base
$edad = 20;
$nombre = null;
$rol = "editor";
$nota = 85;
$tienePermiso = true;
$username = "alice123";
$password = "password123";

// ===============================
// 1. IF / ELSE (condicional clásico)
// ===============================
if ($edad >= 18) {
    echo "Eres mayor de edad\n";
} else {
    echo "Eres menor de edad\n";
}

// ===============================
// 2. IF con AND (&&)
// ===============================
if ($edad >= 18 && $tienePermiso) {
    echo "Puedes entrar\n";
} else {
    echo "No puedes entrar\n";
}

// En PHP, === significa:
// Igualdad estricta (valor + tipo de dato)
if ($username === "admin" && $password === "admin123") {
    echo "Bienvenido admin\n";
} else {
    echo "Credenciales incorrectas\n";
}

// ===============================
// 3. IF / ELSEIF / ELSE
// ===============================
if ($nota >= 90) {
    echo "Excelente\n";
} elseif ($nota >= 70) {
    echo "Aprobado\n";
} else {
    echo "Reprobado\n";
}

// ===============================
// 4. Operador ternario (forma corta)
// ===============================
$estado = ($edad >= 18) ? "Adulto" : "Menor";
echo $estado . "\n";

// ===============================
// 5. Null coalescing (??)
// Si $nombre es null, usa "Invitado"
// ===============================
$nombreFinal = $nombre ?? "Invitado";
echo "Usuario: " . $nombreFinal . "\n";

// ===============================
// 6. Null coalescing assignment (??=)
// Asigna solo si es null
// ===============================
$nombre ??= "Usuario por defecto";
echo $nombre . "\n";

// ===============================
// 7. MATCH (PHP 8+ moderno)
// Mejor que switch
// ===============================
$mensajeRol = match ($rol) {
    "admin" => "Acceso total",
    "editor" => "Puede editar contenido",
    "usuario" => "Acceso limitado",
    default => "Rol desconocido"
};

echo $mensajeRol . "\n";