<?php

namespace App;

// Ejemplo de herencia: Employee reutiliza la lógica de User.
class Employee extends User
{
    public string $position;

    public function __construct(string $name, int $age, string $position)
    {
        // Reutilizamos el constructor de la clase padre.
        parent::__construct($name, $age);
        $this->position = $position;
    }

    public function introduce(): string
    {
        // Extendemos el comportamiento del método heredado.
        return parent::introduce() . " I work as a {$this->position}.";
    }
}
