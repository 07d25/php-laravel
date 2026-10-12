<?php

// ===============================
// JUEGO: ENCUENTRA EL NÚMERO SECRETO
// ===============================

// Número secreto (puede ser aleatorio)
$secretNumber = 7;

// Intentos del usuario
$attempts = 0;

// Máximo de intentos permitidos
$maxAttempts = 5;

// Variable donde guardamos la respuesta del usuario
$guess = null;

// ===============================
// WHILE LOOP PRINCIPAL DEL JUEGO
// ===============================

// Se ejecuta mientras:
// - el usuario no adivine el número
// - y no supere los intentos máximos
while ($guess !== $secretNumber && $attempts < $maxAttempts) {

    echo "Encuentra el número (1-10): ";

    // Leer input desde consola
    $guess = (int) trim(fgets(STDIN));

    // Aumentar intentos
    $attempts++;

    // ===============================
    // VALIDACIONES
    // ===============================

    if ($guess === $secretNumber) {
        echo "🎉 ¡Correcto! Adivinaste el número.\n";
        break;
    }

    if ($attempts >= $maxAttempts) {
        echo "❌ Se acabaron los intentos. El número era $secretNumber\n";
        break;
    }

    // Pistas para el usuario
    if ($guess < $secretNumber) {
        echo "📉 El número es mayor\n";
    } else {
        echo "📈 El número es menor\n";
    }

    echo "Intentos restantes: " . ($maxAttempts - $attempts) . "\n\n";
}
