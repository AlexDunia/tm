<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to optimize database performance with indexes.
     */
    public function up(): void
    {
        // Add indexes to carts table for faster queries
        Schema::table('carts', function (Blueprint $table) {
            $table->index(['user_id', 'eventname', 'cname'], 'carts_user_event_name_index');
            $table->index('user_id', 'carts_user_id_index');
        });

        // Add indexes to mctlists table for faster searches
        Schema::table('mctlists', function (Blueprint $table) {
            $table->index('name', 'mctlists_name_index');
            $table->index('description', 'mctlists_description_index');
            $table->index('category', 'mctlists_category_index');
            $table->index('date', 'mctlists_date_index');
        });

        // Add indexes to ticket_types table
        Schema::table('ticket_types', function (Blueprint $table) {
            $table->index(['mctlists_id', 'is_active'], 'ticket_types_event_active_index');
            $table->index(['sales_start', 'sales_end', 'is_active'], 'ticket_types_sales_window_index');
        });

        // Add indexes for transactions
        Schema::table('newtransactions', function (Blueprint $table) {
            $table->index('user_id', 'newtransactions_user_id_index');

            // Check if reference column exists before creating index
            if (Schema::hasColumn('newtransactions', 'reference')) {
                $table->index('reference', 'newtransactions_reference_index');
            }

            $table->index('status', 'newtransactions_status_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove indexes from carts table
        Schema::table('carts', function (Blueprint $table) {
            $table->dropIndex('carts_user_event_name_index');
            $table->dropIndex('carts_user_id_index');
        });

        // Remove indexes from mctlists table
        Schema::table('mctlists', function (Blueprint $table) {
            $table->dropIndex('mctlists_name_index');
            $table->dropIndex('mctlists_description_index');
            $table->dropIndex('mctlists_category_index');
            $table->dropIndex('mctlists_date_index');
        });

        // Remove indexes from ticket_types table
        Schema::table('ticket_types', function (Blueprint $table) {
            $table->dropIndex('ticket_types_event_active_index');
            $table->dropIndex('ticket_types_sales_window_index');
        });

        // Remove indexes from transactions
        Schema::table('newtransactions', function (Blueprint $table) {
            $table->dropIndex('newtransactions_user_id_index');

            // Check if reference column exists before dropping index
            if (Schema::hasColumn('newtransactions', 'reference')) {
                $table->dropIndex('newtransactions_reference_index');
            }

            $table->dropIndex('newtransactions_status_index');
        });
    }
};
