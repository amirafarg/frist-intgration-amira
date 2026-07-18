<?php

namespace TeraDevelopers\BagistoDaftraSync\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use TeraDevelopers\BagistoDaftraSync\Services\CategorySyncService;

class DeleteCategoryOnDaftraJob implements ShouldQueue
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
        protected int $categoryId,
        protected int $daftraCategoryId
    ) {}

    /**
     * Execute the job.
     */
    public function handle(CategorySyncService $categorySync): void
    {
        $categorySync->delete($this->daftraCategoryId);
    }

    /**
     * Handle a job failure after all retries are exhausted.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('[DaftraSync] DeleteCategoryOnDaftraJob permanently failed.', [
            'category_id' => $this->categoryId,
            'daftra_category_id' => $this->daftraCategoryId,
            'error' => $exception->getMessage(),
        ]);
    }
}
