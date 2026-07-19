<?php

namespace TeraDevelopers\BagistoDaftraSync\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use TeraDevelopers\BagistoDaftraSync\Models\DaftraProductMapping;
use Webkul\Product\Models\Product;

class ProductSyncService
{
    public function handle(Product $product): void {}

    public function update(Product $product): bool
    {
        $mapping = DaftraProductMapping::where('product_id', $product->id)->first();

        if (! $mapping) {
            Log::info('[DaftraSync] Skipping update — no Daftra product mapping found.', [
                'product_id' => $product->id,
            ]);

            return false;
        }

        $subdomain = config('daftra.subdomain');
        $apiKey = config('daftra.api_key');
        $url = "https://{$subdomain}.daftra.com/api2/products/{$mapping->daftra_product_id}.json";

        // Load the default-locale flat row for name / description / price / status.
        // product_flats is HasMany (one row per locale+channel); grab the default locale.
        $flat = $product->product_flats()
            ->where('locale', config('app.locale'))
            ->first();

        $response = Http::withHeaders([
            'apikey' => $apiKey,
            'Accept' => 'application/json',
        ])->put($url, [
            'Product' => [
                'name' => $flat?->name ?? $product->sku,
                'product_code' => $product->sku,
                'description' => $flat?->description,
                'unit_price' => $flat?->price,
                // Bagisto: status 1 = enabled, 0 = disabled.
                // Daftra:  status 0 = active,  1 = inactive.
                'status' => $flat?->status ? 0 : 1,
            ],
        ]);

        if ($response->status() === 200) {
            Log::info('[DaftraSync] Product updated on Daftra successfully.', [
                'product_id' => $product->id,
                'daftra_product_id' => $mapping->daftra_product_id,
            ]);

            return true;
        }

        // 400 — permanent validation failure; retrying will never fix it.
        // Log the error and return false so the job completes without retrying.
        if ($response->status() === 400) {
            Log::error('[DaftraSync] Daftra rejected product update — validation error (will not retry)', [
                'product_id' => $product->id,
                'daftra_product_id' => $mapping->daftra_product_id,
                'response' => $response->json(),
            ]);

            return false;
        }

        // Any other failure (5xx, network, etc.) — throw so the job retries.
        Log::error('[DaftraSync] Failed to update Daftra product', [
            'product_id' => $product->id,
            'daftra_product_id' => $mapping->daftra_product_id,
            'status' => $response->status(),
            'response' => $response->json(),
        ]);

        throw new \RuntimeException(
            "Daftra product update failed for product #{$product->id} (HTTP {$response->status()})"
        );
    }
}
