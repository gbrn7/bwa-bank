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
        Schema::create('data_plan_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_plan_id')->constrained('data_plans');
            $table->foreignId('transaction_id')->constrained('transactions');
            $table->string('phone_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_plan_histories');
    }
};
