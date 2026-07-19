<?php

namespace TeraDevelopers\BagistoDaftraSync\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Webkul\Sales\Models\Refund;

class RefundSyncService
{
    /**
     * Send a Bagisto refund to Daftra as a new Refund Receipt (`POST /api2/refund_receipts`).
     * According to Daftra OpenAPI spec:
     * - `Invoice.subscription_id` is required (the ID of the source invoice being refunded in Daftra).
     * - Line items follow the same pattern as Add New Invoice (`item`, `description`, `quantity`, `unit_price`, etc.).
     *
     * @throws \RuntimeException
     */
    public function handle(Refund $refund, int $daftraClientId): void
    {
        $subdomain = config('daftra.subdomain');
        $apiKey = config('daftra.api_key');
        $url = "https://{$subdomain}.daftra.com/api2/refund_receipts";

        // 1. Get source Daftra invoice ID (`subscription_id`) dynamically from Daftra API (no new migration needed)
        $sourceInvoiceId = $this->getSourceInvoiceIdFromDaftra($refund, $daftraClientId, $subdomain, $apiKey);

        // 2. Map line items with exact same pattern as InvoiceSyncService
        $lineItems = $refund->items->map(fn ($item) => [
            'item' => $item->name,
            'description' => $item->order_item->description ?? $item->sku,
            'quantity' => $item->qty,
            'unit_price' => round((float) $item->price, 4),
            'discount' => round((float) ($item->discount_amount ?: 0), 4),
            'discount_type' => 2, // 2 = Value (Amount)
        ])->values()->all();

        $payload = [
            'Invoice' => [
                'subscription_id' => $sourceInvoiceId,
                'client_id' => $daftraClientId,
                'no' => 'RR-'.($refund->increment_id ?? $refund->id),
                'date' => $refund->created_at->format('Y-m-d'),
                'notes' => 'Refund #'.$refund->id.' for Bagisto order #'.($refund->order->increment_id ?? $refund->order_id),
                'shipping_amount' => round((float) $refund->shipping_amount, 4),
                'discount_amount' => round((float) $refund->discount_amount, 4),
            ],
            'InvoiceItem' => $lineItems,
        ];

        Log::info('[DaftraSync] Refund Payload', [
            'subscription_id' => $sourceInvoiceId,
            'payload' => $payload,
        ]);

        $response = Http::withHeaders([
            'apikey' => $apiKey,
            'Accept' => 'application/json',
        ])->post($url, $payload);

        // Daftra returns 202 Accepted on successful creation.
        if ($response->status() === 202) {
            return;
        }

        Log::error('[DaftraSync] Failed to create Daftra refund receipt', [
            'refund_id' => $refund->id,
            'daftra_client_id' => $daftraClientId,
            'subscription_id' => $sourceInvoiceId,
            'status' => $response->status(),
            'response' => $response->json(),
        ]);

        throw new \RuntimeException(
            "Daftra refund receipt creation failed for Bagisto refund #{$refund->id} (HTTP {$response->status()})"
        );
    }

    /**
     * Dynamically retrieve the Daftra invoice ID (`subscription_id`) corresponding to the order/invoice
     * without requiring any local database mapping table or migration.
     *
     * @throws \RuntimeException
     */
    protected function getSourceInvoiceIdFromDaftra(Refund $refund, int $daftraClientId, string $subdomain, string $apiKey): int
    {
        $url = "https://{$subdomain}.daftra.com/api2/invoices.json";

        $response = Http::withHeaders([
            'apikey' => $apiKey,
            'Accept' => 'application/json',
        ])->get($url, [
            'client_id' => $daftraClientId,
        ]);

        Log::info('[DaftraSync] Invoices List Response', [
            'status' => $response->status(),
            'body' => $response->json(),
        ]);

        if ($response->successful()) {
            $invoices = $response->json('data') ?? [];

            $orderNo = (string) ($refund->order->increment_id ?? $refund->order_id);
            $orderInvoiceNos = $refund->order->invoices->pluck('increment_id')->filter()->map(fn ($val) => (string) $val)->toArray();
            $orderInvoiceIds = $refund->order->invoices->pluck('id')->filter()->map(fn ($val) => (string) $val)->toArray();

            foreach ($invoices as $inv) {
                $daftraInvNo = (string) ($inv['Invoice']['no'] ?? '');
                $daftraInvNotes = (string) ($inv['Invoice']['notes'] ?? '');
                $daftraInvId = (int) ($inv['Invoice']['id'] ?? 0);

                if ($daftraInvId <= 0) {
                    continue;
                }

                // Match 1: Check if Daftra invoice `no` matches any Bagisto invoice increment_id or id for this order
                if (in_array($daftraInvNo, $orderInvoiceNos, true) || in_array($daftraInvNo, $orderInvoiceIds, true)) {
                    return $daftraInvId;
                }

                // Match 2: Check if Daftra invoice `notes` contains "#" + order increment_id (e.g. "Synced from Bagisto order #10001")
                if (str_contains($daftraInvNotes, "#{$orderNo}")) {
                    return $daftraInvId;
                }
            }

            // Fallback Match: If there is exactly one invoice for this client in Daftra, use it directly
            if (count($invoices) === 1 && ! empty($invoices[0]['Invoice']['id'])) {
                return (int) $invoices[0]['Invoice']['id'];
            }
        }

        throw new \RuntimeException(
            "Source Daftra invoice (`subscription_id`) not found via API for Bagisto Order #{$refund->order_id}. Cannot create refund receipt."
        );
    }
}
