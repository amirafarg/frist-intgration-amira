<?php

namespace TeraDevelopers\BagistoDaftraSync\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use TeraDevelopers\BagistoDaftraSync\Services\ProductSyncService;
use Webkul\Product\Models\Product;

class UpdateProductOnDaftraJob implements ShouldQueue
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

    public function __construct(protected Product $product) {}

    /**
     * Execute the job.
     * Delegates to ProductSyncService::update() which calls PUT /products/{id}.json
     */
    public function handle(ProductSyncService $productSync): void
    {
        $synced = $productSync->update($this->product);

        if ($synced) {
            Log::info('[DaftraSync] Product updated on Daftra successfully.', [
                'product_id' => $this->product->id,
            ]);
        }
    }

    /**
     * Handle a job failure after all retries are exhausted.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('[DaftraSync] UpdateProductOnDaftraJob permanently failed.', [
            'product_id' => $this->product->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
