<?php

namespace TeraDevelopers\BagistoDaftraSync\Services;

class InvoiceSyncService
{
    public function handle($invoice)
    {
        \Log::info('InvoiceSyncService triggered!', ['invoice_id' => $invoice->id ?? 'unknown']);
    }
}