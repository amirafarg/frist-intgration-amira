<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('daftra_client_mappings', function (Blueprint $table) {
            $table->unique('customer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daftra_client_mappings', function (Blueprint $table) {
            $table->dropUnique('daftra_client_mappings_customer_id_unique');
        });
    }
};
