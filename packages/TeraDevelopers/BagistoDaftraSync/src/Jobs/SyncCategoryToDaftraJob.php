<?php

namespace TeraDevelopers\BagistoDaftraSync\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use TeraDevelopers\BagistoDaftraSync\Services\CategorySyncService;
use Webkul\Category\Models\Category;

class SyncCategoryToDaftraJob implements ShouldQueue
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

    public function __construct(protected Category $category) {}

    /**
     * Execute the job.
     */
    public function handle(CategorySyncService $categorySync): void
    {
        $daftraCategoryId = $categorySync->handle($this->category);

        Log::info('[DaftraSync] Category synced to Daftra successfully.', [
            'category_id' => $this->category->id,
            'daftra_category_id' => $daftraCategoryId,
        ]);
    }

    /**
     * Handle a job failure after all retries are exhausted.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('[DaftraSync] SyncCategoryToDaftraJob permanently failed.', [
            'category_id' => $this->category->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
