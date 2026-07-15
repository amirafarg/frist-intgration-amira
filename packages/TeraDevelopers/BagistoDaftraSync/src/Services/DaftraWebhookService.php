<?php

namespace TeraDevelopers\BagistoDaftraSync\Services;

use Illuminate\Support\Facades\Log;
use TeraDevelopers\BagistoDaftraSync\Models\DaftraClientMapping;
use Webkul\Customer\Models\Customer;

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

    /**
     * Handle an inbound Daftra "client.deleted" webhook.
     *
     * Case A — customer_id IS NULL (webhook-originated mapping, no linked Bagisto customer)
     *   Delete the mapping row. Nothing else.
     *
     * Case B — customer_id IS NOT NULL (real linked Bagisto customer)
     *   Step 1: Delete the mapping row FIRST.
     *           The Customer::deleting observer in BagistoDaftraSyncServiceProvider looks up
     *           a mapping by customer_id and dispatches DeleteClientOnDaftraJob if it finds one.
     *           Deleting the mapping first means the observer finds nothing → no job is
     *           dispatched → no redundant DELETE call to a Daftra client that is already gone.
     *   Step 2: Delete the Bagisto customer.
     *
     * The method is idempotent — if no mapping exists for the given daftra_client_id,
     * it logs and returns without error (safe for re-delivered webhooks).
     *
     * @param  array<string, mixed>  $payload  Validated Daftra webhook payload
     */
    public function handleClientDeleted(array $payload): void
    {
        $daftraClientId = (int) $payload['id'];

        $mapping = DaftraClientMapping::where('daftra_client_id', $daftraClientId)->first();

        if (! $mapping) {
            Log::info('[DaftraSync] client.deleted webhook — no mapping found, nothing to do.', [
                'daftra_client_id' => $daftraClientId,
            ]);

            return;
        }

        // Case A — webhook-originated row, no Bagisto customer attached.
        if ($mapping->customer_id === null) {
            $mapping->delete();

            Log::info('[DaftraSync] client.deleted webhook — mapping removed (no linked customer).', [
                'daftra_client_id' => $daftraClientId,
                'mapping_id' => $mapping->id,
            ]);

            return;
        }

        // Case B — real linked Bagisto customer.
        $customerId = $mapping->customer_id;

        // Delete the mapping first so the Customer::deleting observer finds no mapping
        // and does not dispatch a DeleteClientOnDaftraJob for an already-gone Daftra client.
        $mapping->delete();

        $customer = Customer::find($customerId);

        if (! $customer) {
            Log::info('[DaftraSync] client.deleted webhook — mapping removed, Bagisto customer already gone.', [
                'daftra_client_id' => $daftraClientId,
                'customer_id' => $customerId,
            ]);

            return;
        }

        $customer->delete();

        Log::info('[DaftraSync] client.deleted webhook — mapping removed and Bagisto customer deleted.', [
            'daftra_client_id' => $daftraClientId,
            'customer_id' => $customerId,
        ]);
    }
}
