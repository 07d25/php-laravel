<?php

namespace App;

// Otra implementación concreta del mismo contrato.
class PaypalPaymentProcessor extends AbstractPaymentProcessor
{
    public function getProviderName(): string
    {
        return 'PayPal';
    }

    protected function charge(float $amount): bool
    {
        // Ejemplo didáctico: PayPal aplica un límite distinto.
        return $amount <= 2500;
    }
}
