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
        Schema::create('trading_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedInteger('meta_login')->unique();
            $table->unsignedBigInteger('account_type')->default(1);
            $table->integer('margin_leverage')->nullable();
            $table->integer('currency_digits')->nullable();
            $table->decimal('balance', 13)->nullable();
            $table->decimal('credit', 13)->nullable();
            $table->decimal('bonus', 13)->nullable();
            $table->decimal('floating', 13)->nullable();
            $table->decimal('equity', 13)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trading_accounts');
    }
};
