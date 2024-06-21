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
        Schema::create('commission_payouts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('upline_id');
            $table->unsignedBigInteger('downline_id');
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->decimal('amount')->nullable();
            $table->string('status')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('handle_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');
            $table->foreign('upline_id')->references('id')->on('users')->onUpdate('cascade');
            $table->foreign('downline_id')->references('id')->on('users')->onUpdate('cascade');
            $table->foreign('transaction_id')->references('id')->on('transactions')->onUpdate('cascade');
            $table->foreign('handle_by')->references('id')->on('users')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_payouts');
    }
};
