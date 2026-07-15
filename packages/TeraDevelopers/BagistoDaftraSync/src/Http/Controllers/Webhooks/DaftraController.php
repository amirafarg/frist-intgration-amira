<?php

namespace TeraDevelopers\BagistoDaftraSync\Http\Controllers\Webhooks;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use TeraDevelopers\BagistoDaftraSync\Jobs\HandleDaftraClientCreatedJob;

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
     * Placeholder — not implemented in this phase.
     */
    public function clientUpdated(Request $request): JsonResponse
    {
        Log::info('[DaftraSync] client.updated webhook received (not yet implemented).', [
            'payload' => $request->all(),
        ]);

        return response()->json(['status' => 'not_implemented'], 501);
    }

    /**
     * POST /api/webhooks/daftra/client-deleted
     *
     * Placeholder — not implemented in this phase.
     */
    public function clientDeleted(Request $request): JsonResponse
    {
        Log::info('[DaftraSync] client.deleted webhook received (not yet implemented).', [
            'payload' => $request->all(),
        ]);

        return response()->json(['status' => 'not_implemented'], 501);
    }
}
