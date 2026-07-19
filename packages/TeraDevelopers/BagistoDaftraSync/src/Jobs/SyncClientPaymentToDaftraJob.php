<?php

namespace TeraDevelopers\BagistoDaftraSync\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use TeraDevelopers\BagistoDaftraSync\Services\ClientPaymentSyncService;
use Webkul\Sales\Models\Invoice;

class SyncClientPaymentToDaftraJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * Number of seconds to wait before retrying the job.
     *
     * @var int[]
     */
    public array $backoff = [30, 120, 300];

    public function __construct(
        protected Invoice $invoice,
        protected int $daftraClientId
    ) {}

    /**
     * Execute the job.
     */
    public function handle(ClientPaymentSyncService $paymentSync): void
    {
        $paymentSync->handle($this->invoice, $this->daftraClientId);

        Log::info('[DaftraSync] Client Payment synced to Daftra successfully.', [
            'invoice_id' => $this->invoice->id,
            'daftra_client_id' => $this->daftraClientId,
        ]);
    }

    /**
     * Handle a job failure after all retries are exhausted.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('[DaftraSync] SyncClientPaymentToDaftraJob permanently failed.', [
            'invoice_id' => $this->invoice->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
