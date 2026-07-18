<?php

namespace TeraDevelopers\BagistoDaftraSync\Services;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use TeraDevelopers\BagistoDaftraSync\Models\DaftraCategoryMapping;
use Webkul\Category\Contracts\Category;

class CategorySyncService
{
    /**
     * Find or create a Daftra category for the given Bagisto category.
     * Returns the Daftra category ID on success, throws on failure so
     * the calling job can retry.
     *
     * @throws \RuntimeException
     */
    public function handle(Category $category): int
    {
        $mapping = DaftraCategoryMapping::where('category_id', $category->id)->first();

        if ($mapping) {
            return $mapping->daftra_category_id;
        }

        $subdomain = config('daftra.subdomain');
        $apiKey = config('daftra.api_key');
        $url = "https://{$subdomain}.daftra.com/api2/product_categories.json";

        $parentId = 0;
        if ($category->parent_id) {
            $parentMapping = DaftraCategoryMapping::where('category_id', $category->parent_id)->first();
            if ($parentMapping) {
                $parentId = $parentMapping->daftra_category_id;
            }
        }

        $response = Http::withHeaders([
            'APIKEY' => $apiKey,
            'Accept' => 'application/json',
        ])->post($url, [
            'Category' => [
                'name' => $category->name ?: 'Category ' . $category->id,
                'category_type' => 1, // Product Category
                'parent_id' => $parentId,
                'description' => $category->description ?? '',
                'display_order' => $category->position ?? 0,
            ],
        ]);

        // Daftra returns 200 with code 202 in body for product categories creation
        if ($response->status() === 200) {
            // Need to check the body because daftra sometimes uses strange patterns
            $responseData = $response->json();
            $daftraCategoryId = $responseData['Category']['id'] ?? null;

            if ($daftraCategoryId) {
                try {
                    DaftraCategoryMapping::create([
                        'category_id' => $category->id,
                        'daftra_category_id' => $daftraCategoryId,
                    ]);
                } catch (QueryException $e) {
                    // Race condition: another job created the mapping first — that's fine.
                    $mapping = DaftraCategoryMapping::where('category_id', $category->id)->first();

                    return $mapping->daftra_category_id;
                }

                return $daftraCategoryId;
            }
        }

        Log::error('[DaftraSync] Failed to create Daftra category', [
            'category_id' => $category->id,
            'status' => $response->status(),
            'response' => $response->json(),
        ]);

        throw new \RuntimeException(
            "Daftra category creation failed for category #{$category->id} (HTTP {$response->status()})"
        );
    }

    /**
     * Push updated category fields to the existing Daftra category.
     * Returns false (skip) if no mapping exists yet.
     * Throws on API failure so the calling job can retry.
     *
     * @throws \RuntimeException
     */
    public function update(Category $category): bool
    {
        $mapping = DaftraCategoryMapping::where('category_id', $category->id)->first();

        if (! $mapping) {
            Log::info('[DaftraSync] Skipping update — no Daftra category mapping found.', [
                'category_id' => $category->id,
            ]);

            return false;
        }

        $subdomain = config('daftra.subdomain');
        $apiKey = config('daftra.api_key');
        $url = "https://{$subdomain}.daftra.com/api2/product_categories/{$mapping->daftra_category_id}.json";

        $parentId = 0;
        if ($category->parent_id) {
            $parentMapping = DaftraCategoryMapping::where('category_id', $category->parent_id)->first();
            if ($parentMapping) {
                $parentId = $parentMapping->daftra_category_id;
            }
        }

        $response = Http::withHeaders([
            'APIKEY' => $apiKey,
            'Accept' => 'application/json',
        ])->put($url, [
            'Category' => [
                'name' => $category->name ?: 'Category ' . $category->id,
                'category_type' => 1,
                'parent_id' => $parentId,
                'description' => $category->description ?? '',
                'display_order' => $category->position ?? 0,
            ],
        ]);

        if ($response->status() === 200) {
            return true;
        }

        Log::error('[DaftraSync] Failed to update Daftra category', [
            'category_id' => $category->id,
            'daftra_category_id' => $mapping->daftra_category_id,
            'status' => $response->status(),
            'response' => $response->json(),
        ]);

        throw new \RuntimeException(
            "Daftra category update failed for category #{$category->id} (HTTP {$response->status()})"
        );
    }

    /**
     * Delete a Daftra category by their Daftra ID.
     *
     * @throws \RuntimeException
     */
    public function delete(int $daftraCategoryId): bool
    {
        $subdomain = config('daftra.subdomain');
        $apiKey = config('daftra.api_key');
        $url = "https://{$subdomain}.daftra.com/api2/product_categories/1/{$daftraCategoryId}.json";

        $response = Http::withHeaders([
            'APIKEY' => $apiKey,
            'Accept' => 'application/json',
        ])->delete($url);

        if ($response->status() === 200) {
            Log::info('[DaftraSync] Daftra category deleted successfully.', [
                'daftra_category_id' => $daftraCategoryId,
            ]);

            return true;
        }

        if ($response->status() === 404) {
            Log::info('[DaftraSync] Daftra category not found on delete — already removed.', [
                'daftra_category_id' => $daftraCategoryId,
                'response' => $response->json(),
            ]);

            return false;
        }

        if ($response->status() === 400) {
            Log::warning('[DaftraSync] Daftra category cannot be deleted — has existing products.', [
                'daftra_category_id' => $daftraCategoryId,
                'response' => $response->json(),
            ]);

            return false;
        }

        Log::error('[DaftraSync] Failed to delete Daftra category.', [
            'daftra_category_id' => $daftraCategoryId,
            'status' => $response->status(),
        ]);

        throw new \RuntimeException(
            "Daftra category deletion failed for Daftra category #{$daftraCategoryId} (HTTP {$response->status()})"
        );
    }
}
