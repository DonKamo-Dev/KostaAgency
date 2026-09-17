<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_meta_quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('client_name');
            $table->string('industry')->nullable();
            $table->unsignedInteger('budget_cop');
            $table->unsignedTinyInteger('duration_days');
            $table->unsignedInteger('daily_budget_cop');
            $table->string('age_range');
            $table->string('location');
            $table->json('interests');
            $table->string('campaign_type');
            $table->string('destination');
            $table->string('whatsapp_number')->nullable();
            $table->json('ai_result');
            $table->timestamps();

            $table->index('company_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_meta_quotes');
    }
};
