<?php

namespace TeraDevelopers\BagistoDaftraSync\Http\Controllers\Webhooks;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use TeraDevelopers\BagistoDaftraSync\Jobs\HandleDaftraClientCreatedJob;
use TeraDevelopers\BagistoDaftraSync\Jobs\HandleDaftraClientDeletedJob;
use TeraDevelopers\BagistoDaftraSync\Jobs\HandleDaftraClientUpdatedJob;

class DaftraController extends Controller
{
    /**
     * POST /api/webhooks/daftra/client-created
     *
     * Daftra fires this webhook when a client is created directly inside
     * Daftra (e.g. via the Daftra web UI), bypassing the Bagisto storefront.
     *
     * Handling strategy
     * ─────────────────────────────────────────────────────────────────────────
     *  1. Validate the minimum required fields from the payload.
     *  2. Dispatch HandleDaftraClientCreatedJob to the queue — the HTTP
     *     response is returned immediately; no blocking work is done here.
     *  3. The job upserts a row into daftra_client_mappings with
     *     customer_id = NULL (no Bagisto Customer is created or modified).
     *  4. No Bagisto model events fire → no outbound sync is triggered →
     *     zero risk of a Bagisto→Daftra→Bagisto loop.
     * ─────────────────────────────────────────────────────────────────────────
     */
    public function clientCreated(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'id' => ['required', 'integer'],
            'business_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone1' => ['nullable', 'string', 'max:50'],
        ]);

        Log::info('[DaftraSync] client.created webhook received.', [
            'daftra_client_id' => $payload['id'],
            'business_name' => $payload['business_name'] ?? null,
        ]);

        HandleDaftraClientCreatedJob::dispatch($payload);

        return response()->json(['status' => 'queued'], 202);
    }

    /**
     * POST /api/webhooks/daftra/client-updated
     *
     * Handling strategy
     * ─────────────────────────────────────────────────────────────────────────
     *  Case A — mapping has customer_id = NULL (webhook-originated):
     *    Update mapping reference columns (business_name, email, phone1) only.
     *
     *  Case B — mapping has customer_id set (real linked Bagisto customer):
     *    Update mapping reference columns AND overwrite Bagisto Customer fields,
     *    applying only non-empty values (empty string = don't touch).
     *    Uses direct Eloquent save() — customer.update.after does NOT fire — no loop.
     * ─────────────────────────────────────────────────────────────────────────
     */
    public function clientUpdated(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'id' => ['required', 'integer'],
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'business_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'max:255'],
            'phone1' => ['nullable', 'string', 'max:50'],
        ]);

        Log::info('[DaftraSync] client.updated webhook received.', [
            'daftra_client_id' => $payload['id'],
        ]);

        HandleDaftraClientUpdatedJob::dispatch($payload);

        return response()->json(['status' => 'queued'], 202);
    }

    /**
     * POST /api/webhooks/daftra/client-deleted
     *
     * Handling strategy
     * ─────────────────────────────────────────────────────────────────────────
     *  Case A — mapping has customer_id = NULL (webhook-originated, no linked Bagisto customer):
     *    Delete the mapping row only. Nothing else.
     *
     *  Case B — mapping has customer_id set (real linked Bagisto customer):
     *    1. Delete the mapping row first (neutralises the Customer::deleting observer
     *       so it does not dispatch a DeleteClientOnDaftraJob for an already-gone client).
     *    2. Delete the Bagisto customer.
     * ─────────────────────────────────────────────────────────────────────────
     */
    public function clientDeleted(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'id' => ['required', 'integer'],
        ]);

        Log::info('[DaftraSync] client.deleted webhook received.', [
            'daftra_client_id' => $payload['id'],
        ]);

        HandleDaftraClientDeletedJob::dispatch($payload);

        return response()->json(['status' => 'queued'], 202);
    }
}
