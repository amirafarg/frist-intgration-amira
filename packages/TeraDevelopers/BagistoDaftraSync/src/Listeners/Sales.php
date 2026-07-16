<?php

namespace TeraDevelopers\BagistoDaftraSync\Listeners;

use TeraDevelopers\BagistoDaftraSync\Jobs\SyncInvoiceToDaftraJob;
use TeraDevelopers\BagistoDaftraSync\Jobs\SyncRefundToDaftraJob;

class Sales
{
    /**
     * Handle invoice creation event.
     */
    public function onInvoiceSaveAfter($invoice): void
    {
        SyncInvoiceToDaftraJob::dispatch($invoice);
    }

    /**
     * Handle refund creation event.
     */
    public function onRefundSaveAfter($refund): void
    {
        SyncRefundToDaftraJob::dispatch($refund);
    }
}
