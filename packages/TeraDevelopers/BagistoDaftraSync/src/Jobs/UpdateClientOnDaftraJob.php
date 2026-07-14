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

class UpdateClientOnDaftraJob implements ShouldQueue
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
     * Delegates to ClientSyncService::update() which calls PUT /api2/clients/{id}.json.
     * Returns early (no retry) when no Daftra mapping exists for this customer yet.
     */
    public function handle(ClientSyncService $clientSync): void
    {
        $synced = $clientSync->update($this->customer);

        if ($synced) {
            Log::info('[DaftraSync] Customer updated on Daftra successfully.', [
                'customer_id' => $this->customer->id,
            ]);
        }
    }

    /**
     * Handle a job failure after all retries are exhausted.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('[DaftraSync] UpdateClientOnDaftraJob permanently failed.', [
            'customer_id' => $this->customer->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
