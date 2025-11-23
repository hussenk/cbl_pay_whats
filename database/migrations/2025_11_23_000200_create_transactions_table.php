<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignId('sender_account_id')->constrained('accounts')->cascadeOnDelete();
            $table->foreignId('receiver_account_id')->constrained('accounts')->cascadeOnDelete();
            $table->enum('type', ['internal', 'external'])->default('internal');
            $table->decimal('amount', 18, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('status', 30)->default('success');
            $table->string('reference')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};


