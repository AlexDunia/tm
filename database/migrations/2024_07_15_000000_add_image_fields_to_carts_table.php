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
            if (!Schema::hasColumn('carts', 'image')) {
                $table->string('image')->nullable();
            }
            if (!Schema::hasColumn('carts', 'event_image')) {
                $table->string('event_image')->nullable();
            }
            if (!Schema::hasColumn('carts', 'thumbnail')) {
                $table->string('thumbnail')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn(['image', 'event_image', 'thumbnail']);
        });
    }
};
