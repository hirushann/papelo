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
        Schema::table('questions', function (Blueprint $table) {
            $table->string('type')->default('mcq')->after('paper_id');
            $table->text('instruction')->nullable()->after('type');
            $table->text('model_solution')->nullable()->after('instruction');
            $table->boolean('allow_photo')->default(false)->after('model_solution');
            $table->json('data')->nullable()->after('allow_photo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn(['type', 'instruction', 'model_solution', 'allow_photo', 'data']);
        });
    }
};
