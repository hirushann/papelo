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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('session_id')->nullable()->index();
            $table->string('guest_email')->nullable();
            $table->string('claim_token')->nullable()->unique();
            $table->foreignId('paper_id')->constrained('papers')->cascadeOnDelete();
            $table->decimal('amount_paid', 8, 2);
            $table->string('payhere_order_id')->nullable()->index();
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->timestamps();

            // We can't have a unique constraint on just user_id and paper_id if user_id is nullable.
            // Better to remove the unique constraint, or only enforce it via application logic.
            // $table->unique(['user_id', 'paper_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
