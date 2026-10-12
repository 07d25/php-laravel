<?php

namespace App;

// Ejemplo de enum: define un conjunto cerrado de estados posibles.
enum PaymentStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Declined = 'declined';
}
