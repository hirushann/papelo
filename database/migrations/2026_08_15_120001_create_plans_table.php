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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');            // "Practice", "Progress", "Pass"
            $table->string('slug')->unique();  // "practice", "progress", "pass"
            $table->string('ls_variant_id')->nullable()->comment('Lemon Squeezy variant ID');
            $table->decimal('price', 8, 2);    // Monthly price in LKR
            $table->unsignedInteger('paper_limit')->nullable()->comment('Papers per month, null = unlimited');
            $table->json('features')->nullable()->comment('Feature list for display');
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
