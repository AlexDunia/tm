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
        Schema::create('newtransactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');

            $table->string('status');
            $table->string('message');
            $table->string('email');
            $table->string('phone');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('quantity');
            $table->string('eventname');
            $table->string('tablename');
            $table->decimal('amount', 10, 2); // Numeric with 10 digits and 2 decimal places
            $table->timestamps();
            // $table->id();
            // $table->string('status');
            // $table->string('message')->nullable();
            // $table->string('email')->nullable();
            // $table->string('phone')->nullable();
            // $table->unsignedInteger('amount')->nullable();
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newtransactions');
    }
};
