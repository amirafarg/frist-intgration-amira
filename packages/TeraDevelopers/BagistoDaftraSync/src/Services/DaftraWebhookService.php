<?php

namespace TeraDevelopers\BagistoDaftraSync\Services;

use Illuminate\Support\Facades\Log;
use TeraDevelopers\BagistoDaftraSync\Models\DaftraClientMapping;

class DaftraWebhookService
{
    /**
     * Handle an inbound Daftra "client.created" webhook.
     *
     * Scope & design intent
     * ─────────────────────────────────────────────────────────────────────────
     * This method records that a Daftra client exists without creating or
     * touching any Bagisto Customer record.
     *
     *  • customer_id stays NULL — deliberate scope limit for the test
     *    integration; linking an inbound Daftra client to a Bagisto Customer
     *    is explicitly out of scope for the current phase.
     *
     *  • No outbound Daftra API call is made, so there is zero risk of
     *    triggering the outbound sync loop (no Bagisto model event fires).
     *
     *  • updateOrCreate is used (keyed on daftra_client_id) so the handler
     *    is idempotent — re-delivery of the same webhook is safe.
     * ─────────────────────────────────────────────────────────────────────────
     *
     * @param  array<string, mixed>  $payload  Validated Daftra webhook payload
     * @return int The Daftra client ID that was upserted
     */
    public function handleClientCreated(array $payload): int
    {
        $daftraClientId = (int) $payload['id'];

        $mapping = DaftraClientMapping::updateOrCreate(
            // Search key — daftra_client_id must be unique in the table.
            ['daftra_client_id' => $daftraClientId],
            // Reference columns populated from the webhook payload.
            // customer_id is intentionally omitted — it stays NULL.
            [
                'business_name' => $payload['business_name'] ?? null,
                'email' => $payload['email'] ?? null,
                'phone1' => $payload['phone1'] ?? null,
            ]
        );

        Log::info('[DaftraSync] daftra_client_mappings upserted from webhook.', [
            'daftra_client_id' => $daftraClientId,
            'mapping_id' => $mapping->id,
            'was_created' => $mapping->wasRecentlyCreated,
            'business_name' => $payload['business_name'] ?? null,
        ]);

        return $daftraClientId;
    }
}
