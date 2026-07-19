<?php

namespace TeraDevelopers\BagistoDaftraSync\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Webkul\Sales\Models\Invoice;

class ClientPaymentSyncService
{
    /**
     * Record a client payment in Daftara to mark the invoice as paid.
     *
     * @throws \RuntimeException
     */
    public function handle(Invoice $invoice, int $daftraClientId): void
    {
        $subdomain = config('daftra.subdomain');
        $apiKey = config('daftra.api_key');
        $url = "https://{$subdomain}.daftra.com/api2/client_payments.json";

        $bagistoPaymentMethod = $invoice->order->payment->method ?? '';
        $paymentMethod = $bagistoPaymentMethod === 'cashondelivery' ? 'cash' : 'bank';

        $response = Http::withHeaders([
            'apikey' => $apiKey,
            'Accept' => 'application/json',
        ])->post($url, [
            'ClientPayment' => [
                'client_id' => $daftraClientId,
                'amount' => (float) $invoice->grand_total,
                'payment_method' => $paymentMethod,
                'date' => now()->format('Y-m-d'),
                'status' => 1,
                'currency_code' => $invoice->order_currency_code,
            ],
        ]);

        if ($response->status() === 202) {
            return;
        }

        Log::error('[DaftraSync] Failed to create Daftra client payment', [
            'invoice_id' => $invoice->id,
            'client_id' => $daftraClientId,
            'status' => $response->status(),
            'response' => $response->json(),
        ]);

        throw new \RuntimeException(
            "Daftra client payment creation failed for invoice #{$invoice->id} (HTTP {$response->status()})"
        );
    }
}
