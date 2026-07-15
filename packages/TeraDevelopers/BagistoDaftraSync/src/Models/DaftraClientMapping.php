<?php

namespace TeraDevelopers\BagistoDaftraSync\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Customer\Models\Customer;

class DaftraClientMapping extends Model
{
    /**
     * customer_id is nullable by design.
     *
     * Rows created by the outbound sync (Bagisto → Daftra) always have a
     * customer_id.  Rows created by an inbound webhook (Daftra → Bagisto,
     * e.g. "client.created") have no corresponding Bagisto Customer, so
     * customer_id is intentionally left NULL.  This is a deliberate scope
     * limit for the test integration, not an oversight.
     */
    protected $fillable = [
        'daftra_client_id',
        'customer_id',   // nullable — see comment above
        'business_name', // captured from Daftra webhook payload (reference only)
        'email',         // captured from Daftra webhook payload (reference only)
        'phone1',        // captured from Daftra webhook payload (reference only)
    ];

    /**
     * The Bagisto customer linked to this mapping.
     * Returns null for webhook-originated rows (customer_id is NULL).
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
