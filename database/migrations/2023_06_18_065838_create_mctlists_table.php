<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Note: When you want to run migrations just add S at the end to aviod plenty wahala.
    /**
     * Run the migrations.

     */
    public function up(): void
    {
        Schema::create('mctlists', function (Blueprint $table) {
            $table->id();
            // $table->string('image');
            $table->string('name');
            $table->string('location');
            $table->string('description');
            $table->string('date');
            $table->string('image')->nullable();
            $table->string('heroimage')->nullable();
            $table->string('herolink')->nullable();
            // Add table type and price here now.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mctlists');
    }
};
