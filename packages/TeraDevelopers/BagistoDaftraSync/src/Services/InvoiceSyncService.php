<?php

namespace TeraDevelopers\BagistoDaftraSync\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Webkul\Sales\Models\Invoice;

class InvoiceSyncService
{
    /**
     * Send a Bagisto invoice to Daftra as a new invoice under the given client.
     * Throws on failure so the calling job can retry.
     *
     * NOTE: The InvoiceItem payload fields below follow the standard Daftra v2
     * invoice API pattern. Adjust field names against your Daftra invoice API
     * spec if they differ (e.g. `unit_price` vs `price`, `product_id`, etc.).
     *
     * @throws \RuntimeException
     */
    public function handle(Invoice $invoice, int $daftraClientId): void
    {
        $subdomain = config('daftra.subdomain');
        $apiKey = config('daftra.api_key');
        $url = "https://{$subdomain}.daftra.com/api2/invoices";

        $lineItems = $invoice->items->map(fn($item) => [
            'item' => $item->name,
            'description' => $item->description ?? $item->sku,
            'quantity' => $item->qty,
            'unit_price' => round((float) $item->price, 4),
            'discount' => round((float) $item->discount_amount, 4),
            'discount_type' => 2, // 2 = Value (Amount)
        ])->values()->all();

        $payload = [
            'Invoice' => [
                'client_id' => $daftraClientId,
                'no' => $invoice->increment_id ?? (string) $invoice->id,
                'date' => $invoice->created_at->format('Y-m-d'),
                'notes' => 'Synced from Bagisto order #' . ($invoice->order->increment_id ?? $invoice->order_id),
                'shipping_amount' => round((float) $invoice->shipping_amount, 4),
                'discount_amount' => round((float) $invoice->discount_amount, 4),
            ],
            'InvoiceItem' => $lineItems,
        ];

        $response = Http::withHeaders([
            'APIKEY' => $apiKey,
            'Accept' => 'application/json',
        ])->post($url, $payload);

        // Daftra returns 202 Accepted on successful creation.
        if ($response->status() === 202) {
            return;
        }

        Log::error('[DaftraSync] Failed to create Daftra invoice', [
            'invoice_id' => $invoice->id,
            'daftra_client_id' => $daftraClientId,
            'status' => $response->status(),
            'response' => $response->json(),
        ]);

        throw new \RuntimeException(
            "Daftra invoice creation failed for Bagisto invoice #{$invoice->id} (HTTP {$response->status()})"
        );
    }
}
