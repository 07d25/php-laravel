<?php

namespace App;

// Esta clase muestra cómo "protected" permite reutilizar datos en una clase hija.
class PremiumBankAccount extends BankAccount
{
    public function getPremiumLabel(): string
    {
        // La propiedad $owner es protected, así que una clase hija puede leerla.
        return "Premium account owner: {$this->owner}";
    }

    public function applyMonthlyBonus(float $bonus): void
    {
        // Podemos leer la propiedad protegida heredada.
        if ($bonus > 0) {
            $this->deposit($bonus);
        }
    }
}
