<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trading_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('meta_login')->unique();
            $table->string('meta_group')->nullable();
            $table->string('name')->nullable();
            $table->string('country')->nullable();
            $table->unsignedInteger('leverage')->nullable();
            $table->string('registration')->nullable();
            $table->string('last_access')->nullable();
            $table->decimal('balance', 13)->nullable();
            $table->decimal('credit', 13)->nullable();
            $table->decimal('bonus', 13)->nullable();
            $table->unsignedBigInteger('account_type')->default(1);
            $table->string('remarks')->nullable();
            $table->string('module')->default('trading');
            $table->string('category')->nullable();
            $table->string('acc_status')->default('Active');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trading_users');
    }
};
