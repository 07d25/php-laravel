<?php

namespace App;

// Ejemplo de miembros estáticos:
// no necesitas crear un objeto para usar la clase.
class MathUtils
{
    public const PI = 3.14159;

    public static function square(float $number): float
    {
        return $number * $number;
    }

    public static function circleArea(float $radius): float
    {
        return self::PI * self::square($radius);
    }
}
