<?php

namespace TeraDevelopers\BagistoDaftraSync\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use TeraDevelopers\BagistoDaftraSync\Services\DaftraWebhookService;

class HandleDaftraClientCreatedJob implements ShouldQueue
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

    /**
     * This job invokes the DaftraWebhookService which creates a new 
     * Bagisto Customer and links it to the incoming Daftra client ID.
     * No outbound Daftra API call is made.
     * 
     * By using direct Eloquent creation ($customer->save()), no Bagisto 
     * custom events are fired, preventing a Bagisto→Daftra→Bagisto sync loop.
     *
     * @param  array<string, mixed>  $payload  Daftra webhook payload (already validated by the controller)
     */
    public function __construct(protected array $payload) {}

    /**
     * Execute the job.
     */
    public function handle(DaftraWebhookService $webhookService): void
    {
        // Service logs the upsert result (mapping_id, was_created, daftra_client_id).
        // No additional log needed here — failed() below covers the failure case.
        $webhookService->handleClientCreated($this->payload);
    }

    /**
     * Handle a job failure after all retries are exhausted.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('[DaftraSync] HandleDaftraClientCreatedJob permanently failed.', [
            'daftra_client_id' => $this->payload['id'] ?? null,
            'error' => $exception->getMessage(),
        ]);
    }
}
