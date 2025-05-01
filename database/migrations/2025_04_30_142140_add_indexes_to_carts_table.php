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
            // Check if indexes exist before adding them
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $doctrineTable = $sm->listTableDetails('carts');

            // Add compound index for finding specific cart items if it doesn't exist
            if (!$doctrineTable->hasIndex('carts_user_id_cname_eventname_index')) {
                $table->index(['user_id', 'cname', 'eventname']);
            }

            // Add index for sorting if it doesn't exist
            if (!$doctrineTable->hasIndex('carts_created_at_index')) {
                $table->index('created_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            // Drop indexes only if they exist
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $doctrineTable = $sm->listTableDetails('carts');

            if ($doctrineTable->hasIndex('carts_user_id_cname_eventname_index')) {
                $table->dropIndex(['user_id', 'cname', 'eventname']);
            }

            if ($doctrineTable->hasIndex('carts_created_at_index')) {
                $table->dropIndex(['created_at']);
            }
        });
    }
};
