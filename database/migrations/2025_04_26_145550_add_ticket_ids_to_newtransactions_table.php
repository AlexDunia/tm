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
            $table->longText('ticket_ids')->nullable()->after('tablename');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('newtransactions', function (Blueprint $table) {
            $table->dropColumn('ticket_ids');
        });
    }
};
