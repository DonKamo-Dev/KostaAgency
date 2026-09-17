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
            $table->string('platform')->default('META')->after('campaign_type');
        });
    }

    public function down(): void
    {
        Schema::table('ai_meta_quotes', function (Blueprint $table) {
            $table->dropColumn('platform');
        });
    }
};
