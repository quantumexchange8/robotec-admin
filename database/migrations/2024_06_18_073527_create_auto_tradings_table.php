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
        Schema::create('auto_tradings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('meta_login');
            $table->unsignedBigInteger('trading_account_id');
            $table->unsignedBigInteger('transaction_id');
            $table->decimal('investment_amount', 13, 2)->default(0);
            $table->string('period')->default('90');
            $table->string('status')->default('ongoing');
            $table->timestamp('matured_at')->nullable();
            $table->double('cumulative_pamm_return')->default(0);
            $table->decimal('cumulative_amount', 13, 2)->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');
            $table->foreign('trading_account_id')->references('id')->on('trading_accounts')->onUpdate('cascade');
            $table->foreign('transaction_id')->references('id')->on('transactions')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auto_tradings');
    }
};
