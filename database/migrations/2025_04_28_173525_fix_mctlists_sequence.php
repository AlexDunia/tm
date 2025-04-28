<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This migration fixes the PostgreSQL sequence for the mctlists table
     * to prevent "duplicate key value violates unique constraint" errors.
     */
    public function up(): void
    {
        // Reset the PostgreSQL sequence to the maximum ID value plus one
        DB::statement("SELECT setval(pg_get_serial_sequence('mctlists', 'id'), (SELECT MAX(id) FROM mctlists) + 1, false)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No down migration needed for this fix
    }
};
