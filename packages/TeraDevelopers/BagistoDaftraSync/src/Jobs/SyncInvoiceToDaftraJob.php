<?php

namespace TeraDevelopers\BagistoDaftraSync\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use TeraDevelopers\BagistoDaftraSync\Services\ClientSyncService;
use TeraDevelopers\BagistoDaftraSync\Services\InvoiceSyncService;
use Webkul\Sales\Models\Invoice;

class SyncInvoiceToDaftraJob implements ShouldQueue
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

    public function __construct(protected Invoice $invoice) {}

    /**
     * Execute the job.
     */
    public function handle(ClientSyncService $clientSync, InvoiceSyncService $invoiceSync): void
    {
        $invoice = $this->invoice;

        $order = $invoice->order;

        // Guest checkout: order has no customer_id → skip silently.
        // Returning without throwing marks the job as succeeded (no retry).
        if (! $order || ! $order->customer_id) {
            Log::info('[DaftraSync] Skipping guest-order invoice — no customer account.', [
                'invoice_id' => $invoice->id,
                'order_id'   => $invoice->order_id,
            ]);

            return;
        }

        $customer = $order->customer;

        // Step 1 — Ensure the Daftra client exists (creates one via API if absent).
        // Throws \RuntimeException on API failure → job retries with backoff.
        $daftraClientId = $clientSync->handle($customer);

        // Step 2 — Push the invoice to Daftra under that client.
        // Throws \RuntimeException on API failure → job retries with backoff.
        $invoiceSync->handle($invoice, $daftraClientId);

        Log::info('[DaftraSync] Invoice synced to Daftra successfully.', [
            'invoice_id' => $invoice->id,
            'daftra_client_id' => $daftraClientId,
        ]);
    }

    /**
     * Handle a job failure after all retries are exhausted.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('[DaftraSync] SyncInvoiceToDaftraJob permanently failed.', [
            'invoice_id' => $this->invoice->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
