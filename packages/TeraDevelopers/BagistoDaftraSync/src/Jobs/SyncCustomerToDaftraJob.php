<?php

namespace TeraDevelopers\BagistoDaftraSync\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use TeraDevelopers\BagistoDaftraSync\Services\ClientSyncService;
use Webkul\Customer\Models\Customer;

class SyncCustomerToDaftraJob implements ShouldQueue
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

    public function __construct(protected Customer $customer) {}

    /**
     * Execute the job.
     * Delegates entirely to ClientSyncService which handles find-or-create
     * logic and persists the mapping in daftra_client_mappings.
     */
    public function handle(ClientSyncService $clientSync): void
    {
        $daftraClientId = $clientSync->handle($this->customer);

        Log::info('[DaftraSync] Customer synced to Daftra successfully.', [
            'customer_id' => $this->customer->id,
            'daftra_client_id' => $daftraClientId,
        ]);
    }

    /**
     * Handle a job failure after all retries are exhausted.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('[DaftraSync] SyncCustomerToDaftraJob permanently failed.', [
            'customer_id' => $this->customer->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
