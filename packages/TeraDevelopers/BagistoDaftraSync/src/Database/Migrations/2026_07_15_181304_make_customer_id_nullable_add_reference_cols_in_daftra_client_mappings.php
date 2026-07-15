<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Make customer_id nullable and add denormalised reference columns.
     *
     * WHY customer_id IS NULLABLE
     * ─────────────────────────────────────────────────────────────────────────
     * The daftra_client_mappings table is written from two directions:
     *
     *  • Bagisto → Daftra (outbound sync):
     *      A Bagisto customer registers, a Daftra client is created, and a
     *      mapping row is inserted with BOTH customer_id AND daftra_client_id.
     *
     *  • Daftra → Bagisto (inbound webhook — "client.created"):
     *      Daftra creates a client directly (e.g. via the Daftra web UI).
     *      The webhook fires and we record the Daftra client in our table so
     *      we know it exists.  There is NO corresponding Bagisto Customer for
     *      this row — customer_id is intentionally left NULL.
     *
     * Leaving customer_id NULL for webhook-originated rows is a deliberate
     * scope limit for this test integration, NOT an oversight.  Linking an
     * inbound Daftra client to a new or existing Bagisto Customer is
     * explicitly out of scope for the current phase.
     *
     * NOTE: The unique constraint on customer_id is also dropped so that
     * multiple NULL values are permitted (NULL ≠ NULL in SQL unique indexes).
     * ─────────────────────────────────────────────────────────────────────────
     */
    public function up(): void
    {
        Schema::table('daftra_client_mappings', function (Blueprint $table) {
            // Drop the FK first — MySQL requires the FK to be removed before
            // it will allow the unique index it depends on to be dropped
            // (error 1553 otherwise).
            $table->dropForeign(['customer_id']);
            $table->dropUnique('daftra_client_mappings_customer_id_unique');

            $table->unsignedInteger('customer_id')->nullable()->change();

            // Denormalised reference columns captured from the Daftra webhook
            // payload.  These are informational only — no Bagisto model is
            // created or updated when they are populated.
            $table->string('business_name')->nullable()->after('customer_id');
            $table->string('email')->nullable()->after('business_name');
            $table->string('phone1')->nullable()->after('email');

            // Enforce "one row per Daftra client" at the DB level.
            //
            // daftra_client_id is now the primary lookup key for the inbound
            // (Daftra → Bagisto) direction via updateOrCreate.  Without this
            // constraint a race condition — two simultaneous deliveries of the
            // same webhook — would silently insert two rows for the same Daftra
            // client, breaking every future lookup that assumes uniqueness.
            // The DB constraint is the only reliable guard; application-level
            // updateOrCreate alone is not enough under concurrency.
            $table->unique('daftra_client_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daftra_client_mappings', function (Blueprint $table) {
            $table->dropUnique('daftra_client_mappings_daftra_client_id_unique');
            $table->dropColumn(['business_name', 'email', 'phone1']);
            $table->unsignedInteger('customer_id')->nullable(false)->change();
            $table->foreign('customer_id')->references('id')->on('customers')->cascadeOnDelete();
            $table->unique('customer_id');
        });
    }
};
