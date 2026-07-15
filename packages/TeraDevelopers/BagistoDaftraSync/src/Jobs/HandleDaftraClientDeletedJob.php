<?php

namespace TeraDevelopers\BagistoDaftraSync\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use TeraDevelopers\BagistoDaftraSync\Services\DaftraWebhookService;

class HandleDaftraClientDeletedJob implements ShouldQueue
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
     * @param  array<string, mixed>  $payload  Validated Daftra webhook payload
     */
    public function __construct(protected array $payload) {}

    /**
     * Execute the job.
     */
    public function handle(DaftraWebhookService $webhookService): void
    {
        $webhookService->handleClientDeleted($this->payload);
    }

    /**
     * Handle a job failure after all retries are exhausted.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('[DaftraSync] HandleDaftraClientDeletedJob permanently failed.', [
            'daftra_client_id' => $this->payload['id'] ?? null,
            'error' => $exception->getMessage(),
        ]);
    }
}
