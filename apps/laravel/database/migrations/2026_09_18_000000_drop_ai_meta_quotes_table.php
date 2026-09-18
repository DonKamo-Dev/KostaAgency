<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('ai_meta_quotes');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Table intentionally dropped permanently as feature was retired.
    }
};
