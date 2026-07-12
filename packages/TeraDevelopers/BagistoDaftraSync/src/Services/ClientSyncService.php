<?php

namespace TeraDevelopers\BagistoDaftraSync\Services;

use TeraDevelopers\BagistoDaftraSync\Models\DaftraClientMapping;
use Webkul\Customer\Models\Customer;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClientSyncService
{
    public function handle(Customer $customer)
    {
        $mapping = DaftraClientMapping::where('customer_id', $customer->id)->first();

        if ($mapping) {
            return $mapping->daftra_client_id;
        }

        $subdomain = config('daftra.subdomain');
        $apiKey = config('daftra.api_key');

        // Daftra API endpoint v1 for client creation
        $url = "https://{$subdomain}.daftra.com/api2/clients";

        $response = Http::withHeaders([
            'APIKEY' => $apiKey,
            'Accept' => 'application/json',
        ])->post($url, [
                    'Client' => [
                        'business_name' => trim($customer->first_name . ' ' . $customer->last_name) ?: 'Customer ' . $customer->id,
                        'first_name' => $customer->first_name,
                        'last_name' => $customer->last_name,
                        'email' => $customer->email,
                        'phone1' => $customer->phone,
                        'type' => 2, // Individual
                    ]
                ]);

        if ($response->successful()) {
            $responseData = $response->json();
            $daftraClientId = $responseData['id'] ?? null;

            if ($daftraClientId) {
                try {
                    DaftraClientMapping::create([
                        'customer_id' => $customer->id,
                        'daftra_client_id' => $daftraClientId,
                    ]);

                    return $daftraClientId;

                } catch (\Illuminate\Database\QueryException $e) {
                    $freshMapping = DaftraClientMapping::where('customer_id', $customer->id)->first();
                    return $freshMapping?->daftra_client_id;
                }
            }
        }

        Log::error('Failed to create Daftra client', [
            'customer_id' => $customer->id,
            'status' => $response->status(),
            'response' => $response->json(),
        ]);

        return null;
    }
}