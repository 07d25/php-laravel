<?php

namespace App;

// Una interfaz define un contrato que otras clases deben cumplir.
interface PaymentMethod
{
    public function getProviderName(): string;

    public function pay(float $amount): PaymentStatus;
}
