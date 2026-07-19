<?php

namespace TeraDevelopers\BagistoDaftraSync\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use TeraDevelopers\BagistoDaftraSync\Models\DaftraClientMapping;
use Webkul\Customer\Models\Customer;

class DaftraWebhookService
{
    /**
     * Handle an inbound Daftra "client.created" webhook.
     *
     * Scope & design intent
     * ─────────────────────────────────────────────────────────────────────────
     * This method records that a Daftra client exists and creates a Bagisto
     * Customer record linked to it.
     *
     *  • By using direct Eloquent creation ($customer->save()), no Bagisto
     *    model event (like customer.create.after) fires.
     *  • No outbound Daftra API call is made, so there is zero risk of
     *    triggering an outbound sync loop.
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

        $mapping = DaftraClientMapping::where('daftra_client_id', $daftraClientId)->first();

        // If mapping exists and already has a customer_id, do nothing.
        if ($mapping && $mapping->customer_id !== null) {
            Log::info('[DaftraSync] client.created webhook — mapping already linked to Bagisto customer.', [
                'daftra_client_id' => $daftraClientId,
                'customer_id' => $mapping->customer_id,
            ]);

            return $daftraClientId;
        }

        // We need to create a Bagisto Customer.
        // Use ?: (falsy coalesce) to normalize empty strings from Daftra to null,
        // preventing unique constraint violations on phone/email columns.
        $customer = new Customer;
        $customer->first_name = ($payload['first_name'] ?? null)
            ?: ($payload['business_name'] ?? null)
            ?: 'Unknown';
        $customer->last_name = ($payload['last_name'] ?? null) ?: 'Unknown';

        // Bagisto requires an email. Use placeholder if not provided by Daftra.
        $customer->email = ($payload['email'] ?? null)
            ?: "daftra_{$daftraClientId}@placeholder.local";

        // Normalize empty string to null — phone has a UNIQUE index in Bagisto.
        $customer->phone = ($payload['phone1'] ?? null) ?: null;
        $customer->customer_group_id = 2; // Default 'General' group in Bagisto
        $customer->status = 1; // Active
        $customer->is_verified = 1;
        $customer->password = bcrypt(Str::random(16));

        $customer->save(); // Direct eloquent save prevents Bagisto custom events from firing

        $mapping = DaftraClientMapping::updateOrCreate(
            ['daftra_client_id' => $daftraClientId],
            [
                'customer_id' => $customer->id,
                'business_name' => $payload['business_name'] ?? null,
                'email' => $payload['email'] ?? null,
                'phone1' => $payload['phone1'] ?? null,
            ]
        );

        Log::info('[DaftraSync] daftra_client_mappings upserted from webhook, Bagisto customer created.', [
            'daftra_client_id' => $daftraClientId,
            'mapping_id' => $mapping->id,
            'customer_id' => $customer->id,
        ]);

        // Publish a real-time event to Redis so the Node.js WebSocket server
        // can instantly broadcast it to connected admin browsers.
        //
        // We open a DEDICATED raw phpredis connection for the publish.
        // Reason: phpredis cannot share one connection between normal Eloquent
        // queue-worker commands and pub/sub publish calls — reusing the
        // Laravel-managed connection blocks indefinitely. A fresh connect →
        // publish → close is the correct pattern and is non-blocking.
        try {
            $pub = new \Redis;
            $pub->connect(
                config('database.redis.default.host', '127.0.0.1'),
                (int) config('database.redis.default.port', 6379)
            );
            $pub->publish(
                'daftra.customer.created',
                json_encode([
                    'customer_id' => $customer->id,
                    'first_name' => $customer->first_name,
                    'last_name' => $customer->last_name,
                    'email' => $customer->email,
                    'daftra_id' => $daftraClientId,
                    'business_name' => $payload['business_name'] ?? null,
                    'created_at' => now()->toIso8601String(),
                ])
            );
            $pub->close();
        } catch (\Throwable $e) {
            // Never let a realtime failure crash the queue job.
            Log::warning('[DaftraSync] Redis publish failed — realtime notification skipped.', [
                'error' => $e->getMessage(),
            ]);
        }

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

    /**
     * Handle an inbound Daftra "client.updated" webhook.
     *
     * Always: update the reference columns in daftra_client_mappings.
     *
     * Case A — customer_id IS NULL (webhook-originated, no linked Bagisto customer):
     *   Update the mapping row only. Nothing else.
     *
     * Case B — customer_id IS NOT NULL (real linked Bagisto customer):
     *   Additionally overwrite the Bagisto Customer's first_name / last_name / email / phone,
     *   but ONLY for fields where the Daftra payload value is non-empty (filled()).
     *   Empty strings from Daftra (e.g. phone1: "") are treated as "don't touch" — they
     *   do not overwrite whatever Bagisto has on record.
     *
     * Loop prevention (Case B):
     *   The update is applied via $customer->save() directly on the Eloquent model.
     *   Bagisto's customer.update.after custom event is fired by Bagisto's own
     *   controller/repository flow — it does NOT fire from a plain Eloquent save().
     *   Therefore UpdateClientOnDaftraJob is never dispatched, no outbound Daftra API
     *   call is made, and no client.updated webhook is triggered in return.
     *
     * Idempotent — re-delivery of the same webhook produces the same state.
     *
     * @param  array<string, mixed>  $payload  Validated Daftra webhook payload
     */
    public function handleClientUpdated(array $payload): void
    {
        $daftraClientId = (int) $payload['id'];

        $mapping = DaftraClientMapping::where('daftra_client_id', $daftraClientId)->first();

        if (! $mapping) {
            Log::info('[DaftraSync] client.updated webhook — no mapping found, nothing to do.', [
                'daftra_client_id' => $daftraClientId,
            ]);

            return;
        }

        // Update reference columns on the mapping row.
        // Normalize empty strings to null so "" doesn't land in the DB.
        $mapping->update([
            'business_name' => $payload['business_name'] ?: null,
            'email' => $payload['email'] ?: null,
            'phone1' => $payload['phone1'] ?: null,
        ]);

        // Case A — no linked Bagisto customer, done.
        if ($mapping->customer_id === null) {
            Log::info('[DaftraSync] client.updated webhook — mapping updated (no linked customer).', [
                'daftra_client_id' => $daftraClientId,
                'mapping_id' => $mapping->id,
            ]);

            return;
        }

        // Case B — real linked Bagisto customer.
        $customer = Customer::find($mapping->customer_id);

        if (! $customer) {
            Log::info('[DaftraSync] client.updated webhook — mapping updated, Bagisto customer already gone.', [
                'daftra_client_id' => $daftraClientId,
                'customer_id' => $mapping->customer_id,
            ]);

            return;
        }

        // Apply each field only if the payload value is non-empty.
        // filled() returns false for both null and "".
        if (filled($payload['first_name'] ?? null)) {
            $customer->first_name = $payload['first_name'];
        }

        if (filled($payload['last_name'] ?? null)) {
            $customer->last_name = $payload['last_name'];
        }

        if (filled($payload['email'] ?? null)) {
            $customer->email = $payload['email'];
        }

        if (filled($payload['phone1'] ?? null)) {
            $customer->phone = $payload['phone1'];
        }

        // Direct Eloquent save — does NOT fire Bagisto's customer.update.after custom event.
        // UpdateClientOnDaftraJob is therefore never dispatched. No loop.
        $customer->save();

        Log::info('[DaftraSync] client.updated webhook — mapping and Bagisto customer updated.', [
            'daftra_client_id' => $daftraClientId,
            'customer_id' => $customer->id,
        ]);
    }
}
