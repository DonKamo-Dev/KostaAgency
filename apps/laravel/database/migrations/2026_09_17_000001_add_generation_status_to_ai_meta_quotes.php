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
        Schema::table('ai_meta_quotes', function (Blueprint $table) {
            $table->string('generation_status', 20)->default('completed')->after('ai_result');
            $table->text('error')->nullable()->after('generation_status');
            $table->index('generation_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_meta_quotes', function (Blueprint $table) {
            $table->dropIndex(['generation_status']);
            $table->dropColumn(['error', 'generation_status']);
        });
    }
};
