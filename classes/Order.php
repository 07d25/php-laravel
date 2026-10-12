<?php

namespace App;

// Esta clase usa un trait y un enum al mismo tiempo.
class Order
{
    use Timestampable;

    public function __construct(
        public string $number,
        public float $total,
        public PaymentStatus $status = PaymentStatus::Pending
    ) {
        // Inicializamos el timestamp al crear la orden.
        $this->markCreated();
    }

    public function markAsApproved(): void
    {
        $this->status = PaymentStatus::Approved;
        $this->touch();
    }
}
