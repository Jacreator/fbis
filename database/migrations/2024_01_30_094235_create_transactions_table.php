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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('trans_ref');
            $table->string('wallet_id')->nullable();
            $table->string('amount_paid')->nullable();
            $table->string('settlement_amount')->nullable();
            $table->string('status')->nullable();
            $table->string('description')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('current_balance')->nullable();
            $table->string('previous_balance')->nullable();
            $table->string('receiver')->nullable();
            $table->longText('customer')->nullable();
            $table->string('transaction_type')->nullable();
            $table->string('pay_ref')->nullable();
            $table->string('trans_date');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
