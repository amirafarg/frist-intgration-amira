<?php

namespace TeraDevelopers\BagistoDaftraSync\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use TeraDevelopers\BagistoDaftraSync\Models\DaftraClientMapping;
use TeraDevelopers\BagistoDaftraSync\Services\ClientSyncService;

class DeleteClientOnDaftraJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    // NOTE: SerializesModels is intentionally NOT used here.
    // The customer record is already deleted from the DB by the time this
    // job runs, so there is no model to re-hydrate. Primitives are passed
    // instead so the job survives the customer deletion.

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
        protected int $customerId,
        protected int $daftraClientId,
    ) {}

    /**
     * Execute the job.
     *
     * Step 1: Remove the local mapping row — always, regardless of what Daftra says.
     * Step 2: Call Daftra DELETE /clients/{id}.json.
     *   • 200/404 → success, done.
     *   • 400     → has transactions, cannot delete on Daftra side; log warning, done (no retry).
     *   • 5xx     → throw → job retries with backoff.
     */
    public function handle(ClientSyncService $clientSync): void
    {
        // Step 1 — Always clean up the local mapping first so a stale record
        // can never block a future re-sync of the same customer.
        DaftraClientMapping::where('customer_id', $this->customerId)->delete();

        Log::info('[DaftraSync] Local mapping removed for deleted customer.', [
            'customer_id' => $this->customerId,
            'daftra_client_id' => $this->daftraClientId,
        ]);

        // Step 2 — Call Daftra API (throws on 5xx → job retries).
        $clientSync->delete($this->daftraClientId);
    }

    /**
     * Handle a job failure after all retries are exhausted.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('[DaftraSync] DeleteClientOnDaftraJob permanently failed.', [
            'customer_id' => $this->customerId,
            'daftra_client_id' => $this->daftraClientId,
            'error' => $exception->getMessage(),
        ]);
    }
}
