<?php

namespace App;

// readonly: los valores se asignan una sola vez al crear el objeto.
readonly class Invoice
{
    public function __construct(
        public string $number,
        public float $amount,
        public PaymentStatus $status
    ) {
    }
}
