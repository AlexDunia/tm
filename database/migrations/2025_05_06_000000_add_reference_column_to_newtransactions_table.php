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
        Schema::table('newtransactions', function (Blueprint $table) {
            // Add reference column if it doesn't exist
            if (!Schema::hasColumn('newtransactions', 'reference')) {
                $table->string('reference')->nullable()->after('message');
                $table->index('reference');
            }

            // Add indexes to message column for faster LIKE searches
            if (!Schema::hasIndex('newtransactions', 'newtransactions_message_index')) {
                $table->index('message');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('newtransactions', function (Blueprint $table) {
            if (Schema::hasColumn('newtransactions', 'reference')) {
                $table->dropColumn('reference');
            }

            if (Schema::hasIndex('newtransactions', 'newtransactions_message_index')) {
                $table->dropIndex('newtransactions_message_index');
            }
        });
    }
};
