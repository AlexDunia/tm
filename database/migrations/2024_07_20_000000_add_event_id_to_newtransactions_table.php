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
        // Only add the column if it doesn't exist
        if (!Schema::hasColumn('newtransactions', 'event_id')) {
            Schema::table('newtransactions', function (Blueprint $table) {
                $table->foreignId('event_id')->nullable()->after('user_id')->constrained('mctlists')->nullOnDelete();
                $table->index('event_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('newtransactions', 'event_id')) {
            Schema::table('newtransactions', function (Blueprint $table) {
                $table->dropForeign(['event_id']);
                $table->dropIndex(['event_id']);
                $table->dropColumn('event_id');
            });
        }
    }
};
