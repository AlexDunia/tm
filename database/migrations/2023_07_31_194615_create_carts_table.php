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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('cname');
            $table->string('clocation');
            $table->string('cdescription')->nullable();
            $table->string('cquantity')->nullable();
            $table->string('cprice')->nullable();
            $table->string('eventname')->nullable();
            $table->string('ctotalprice')->nullable();
            // $table->int('quantity_earlybird');
            // $table->int('quantity_regular');
            // $table->int('quantity_earlybird');
            $table->timestamps();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
