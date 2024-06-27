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
        Schema::create('auto_trading_logs', function (Blueprint $table) {
            $table->id();
            $table->double('old_pamm')->default(0);
            $table->double('new_pamm')->default(0);
            $table->decimal('old_amount', 13, 2)->default(0);
            $table->decimal('new_amount', 13, 2)->default(0);
            $table->decimal('old_earning', 13, 2)->default(0);
            $table->decimal('new_earning', 13, 2)->default(0);
            $table->string('status')->nullable();
            $table->string('remarks')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auto_trading_logs');
    }
};
