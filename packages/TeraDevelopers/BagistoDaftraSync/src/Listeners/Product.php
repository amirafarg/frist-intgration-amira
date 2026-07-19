<?php

namespace TeraDevelopers\BagistoDaftraSync\Listeners;

use Illuminate\Support\Facades\Log;
use TeraDevelopers\BagistoDaftraSync\Jobs\UpdateProductOnDaftraJob;

class Product
{
    /**
     * Handle Product update event.
     */
    public function onUpdateAfter($product): void
    {
        if ($product->type !== 'simple') {
            return;
        }

        // Only sync update if it simple product
        Log::info('[DaftraSync] Dispatching UpdateProductOnDaftraJob.', [
            'product_id' => $product->id,
        ]);

        UpdateProductOnDaftraJob::dispatch($product);
    }
}
