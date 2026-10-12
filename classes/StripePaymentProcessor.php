<?php

namespace App;

// Implementación concreta de un procesador de pagos.
class StripePaymentProcessor extends AbstractPaymentProcessor
{
    public function getProviderName(): string
    {
        return 'Stripe';
    }

    protected function charge(float $amount): bool
    {
        // Ejemplo didáctico: Stripe aprueba pagos hasta cierto límite.
        return $amount <= 5000;
    }
}
