<?php

namespace App;

// final: esta clase no puede ser extendida.
final class InvoicePrinter
{
    public function format(Invoice $invoice): string
    {
        return "Invoice {$invoice->number} | Amount: {$invoice->amount} | Status: {$invoice->status->value}";
    }
}
