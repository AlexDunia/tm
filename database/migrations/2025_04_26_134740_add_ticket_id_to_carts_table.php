<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            // Add ticket_id column - nullable because legacy records won't have it
            $table->unsignedBigInteger('ticket_id')->nullable()->after('user_id');

            // Add foreign key constraint to the ticket_types table
            $table->foreign('ticket_id')
                  ->references('id')
                  ->on('ticket_types')
                  ->onDelete('set null'); // If ticket is deleted, keep cart item but set ticket_id to null
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['ticket_id']);

            // Then drop the column
            $table->dropColumn('ticket_id');
        });
    }
};
