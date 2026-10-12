<?php

namespace App;

// Ejemplo base de una clase en PHP.
// Una clase agrupa estado (propiedades) y comportamiento (métodos) en una sola unidad.
class User
{
    // Propiedades públicas para mantener el ejemplo simple y directo.
    public string $name;
    public int $age;

    public function __construct(string $name, int $age)
    {
        // $this representa la instancia actual.
        $this->name = $name;
        $this->age = $age;
    }

    public function introduce(): string
    {
        return "Hello, my name is {$this->name} and I am {$this->age} years old.";
    }
}
