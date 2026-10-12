<?php

namespace App;

// Ejemplo de visibilidad de miembros:
// - private: solo visible dentro de esta clase
// - protected: visible en esta clase y en las hijas
// - public: accesible desde cualquier parte
class BankAccount
{
    private float $balance = 0.0;
    protected string $owner;

    public function __construct(string $owner)
    {
        $this->owner = $owner;
    }

    public function getOwner(): string
    {
        return $this->owner;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function deposit(float $amount): void
    {
        // Protegemos el estado interno con validaciones simples.
        if ($amount > 0) {
            $this->balance += $amount;
        }
    }

    public function withdraw(float $amount): bool
    {
        if ($amount > 0 && $amount <= $this->balance) {
            $this->balance -= $amount;
            return true;
        }

        return false;
    }
}
