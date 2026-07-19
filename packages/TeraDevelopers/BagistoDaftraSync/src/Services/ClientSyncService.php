<?php

namespace TeraDevelopers\BagistoDaftraSync\Services;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use TeraDevelopers\BagistoDaftraSync\Models\DaftraClientMapping;
use Webkul\Customer\Models\Customer;

class ClientSyncService
{
    /**
     * Find or create a Daftra client for the given Bagisto customer.
     * Returns the Daftra client ID on success, throws on failure so
     * the calling job can retry.
     *
     * @throws \RuntimeException
     */
    public function handle(Customer $customer): int
    {
        $mapping = DaftraClientMapping::where('customer_id', $customer->id)->first();

        if ($mapping) {
            return $mapping->daftra_client_id;
        }

        $subdomain = config('daftra.subdomain');
        $apiKey = config('daftra.api_key');
        $url = "https://{$subdomain}.daftra.com/api2/clients";

        $response = Http::withHeaders([
            'apikey' => $apiKey,
            'Accept' => 'application/json',
        ])->post($url, [
            'Client' => [
                'business_name' => trim($customer->first_name.' '.$customer->last_name) ?: 'Customer '.$customer->id,
                'type' => 2, // Individual
                'first_name' => $customer->first_name,
                'last_name' => $customer->last_name,
                'email' => $customer->email,
                'phone1' => $customer->phone,
            ],
        ]);

        // Daftra returns 202 Accepted on successful creation.
        if ($response->status() === 202) {
            $daftraClientId = $response->json('id');

            if ($daftraClientId) {
                try {
                    DaftraClientMapping::create([
                        'customer_id' => $customer->id,
                        'daftra_client_id' => $daftraClientId,
                    ]);
                } catch (QueryException $e) {
                    // Race condition: another job created the mapping first — that's fine.
                    $mapping = DaftraClientMapping::where('customer_id', $customer->id)->first();

                    return $mapping->daftra_client_id;
                }

                return $daftraClientId;
            }
        }

        Log::error('[DaftraSync] Failed to create Daftra client', [
            'customer_id' => $customer->id,
            'status' => $response->status(),
            'response' => $response->json(),
        ]);

        throw new \RuntimeException(
            "Daftra client creation failed for customer #{$customer->id} (HTTP {$response->status()})"
        );
    }

    /**
     * Push updated customer fields to the existing Daftra client.
     * Returns false (skip) if no mapping exists yet — the customer will be
     * created on their next invoice sync or via the registration job.
     * Throws on API failure so the calling job can retry.
     *
     * @throws \RuntimeException
     */
    public function update(Customer $customer): bool
    {
        $mapping = DaftraClientMapping::where('customer_id', $customer->id)->first();

        if (! $mapping) {
            Log::info('[DaftraSync] Skipping update — no Daftra client mapping found.', [
                'customer_id' => $customer->id,
            ]);

            return false;
        }

        $subdomain = config('daftra.subdomain');
        $apiKey = config('daftra.api_key');
        $url = "https://{$subdomain}.daftra.com/api2/clients/{$mapping->daftra_client_id}.json";

        $response = Http::withHeaders([
            'apikey' => $apiKey,
            'Accept' => 'application/json',
        ])->put($url, [
            'Client' => [
                'business_name' => trim($customer->first_name.' '.$customer->last_name) ?: 'Customer '.$customer->id,
                'first_name' => $customer->first_name,
                'last_name' => $customer->last_name,
                'email' => $customer->email,
                'phone1' => $customer->phone,
            ],
        ]);

        if ($response->status() === 200) {
            return true;
        }

        Log::error('[DaftraSync] Failed to update Daftra client', [
            'customer_id' => $customer->id,
            'daftra_client_id' => $mapping->daftra_client_id,
            'status' => $response->status(),
            'response' => $response->json(),
        ]);

        throw new \RuntimeException(
            "Daftra client update failed for customer #{$customer->id} (HTTP {$response->status()})"
        );
    }

    /**
     * Delete a Daftra client by their Daftra ID.
     *
     * Response handling:
     *   200 → deleted successfully, return true
     *   404 → already gone (idempotent), return true
     *   400 → client has transactions; Daftra won't allow deletion.
     *          Log a warning and return false — do NOT throw, no retry.
     *   5xx / network → throw RuntimeException so the job retries.
     *
     * @throws \RuntimeException
     */
    public function delete(int $daftraClientId): bool
    {
        $subdomain = config('daftra.subdomain');
        $apiKey = config('daftra.api_key');
        $url = "https://{$subdomain}.daftra.com/api2/clients/{$daftraClientId}.json";

        $response = Http::withHeaders([
            'apikey' => $apiKey,
            'Accept' => 'application/json',
        ])->delete($url);

        // 200 — deleted successfully.
        if ($response->status() === 200) {
            Log::info('[DaftraSync] Daftra client deleted successfully.', [
                'daftra_client_id' => $daftraClientId,
            ]);

            return true;
        }

        // 404 — already gone; treat as success (idempotent).
        if ($response->status() === 404) {
            Log::info('[DaftraSync] Daftra client not found on delete — already removed.', [
                'daftra_client_id' => $daftraClientId,
            ]);

            return true;
        }

        // 400 — client has linked transactions; Daftra refuses deletion.
        // We still clean up the local mapping but do NOT retry.
        if ($response->status() === 400) {
            Log::warning('[DaftraSync] Daftra client cannot be deleted — has existing transactions.', [
                'daftra_client_id' => $daftraClientId,
                'response' => $response->json(),
            ]);

            return false;
        }

        // Any other failure (5xx, network, etc.) — throw so the job retries.
        Log::error('[DaftraSync] Failed to delete Daftra client.', [
            'daftra_client_id' => $daftraClientId,
            'status' => $response->status(),
            'response' => $response->json(),
        ]);

        throw new \RuntimeException(
            "Daftra client deletion failed for Daftra client #{$daftraClientId} (HTTP {$response->status()})"
        );
    }
}
