<?php

namespace App;

// Clase abstracta:
// ofrece lógica compartida y obliga a las clases hijas a completar la parte variable.
abstract class AbstractPaymentProcessor implements PaymentMethod
{
    abstract public function getProviderName(): string;

    final public function pay(float $amount): PaymentStatus
    {
        // Centralizamos la validación común para todos los procesadores.
        if ($amount <= 0) {
            return PaymentStatus::Declined;
        }

        return $this->charge($amount) ? PaymentStatus::Approved : PaymentStatus::Declined;
    }

    // La parte específica queda para cada proveedor.
    abstract protected function charge(float $amount): bool;
}
