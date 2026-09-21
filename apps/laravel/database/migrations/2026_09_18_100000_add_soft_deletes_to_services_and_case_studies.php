<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('services') && ! Schema::hasColumn('services', 'deleted_at')) {
            Schema::table('services', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        if (Schema::hasTable('case_studies') && ! Schema::hasColumn('case_studies', 'deleted_at')) {
            Schema::table('case_studies', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('services') && Schema::hasColumn('services', 'deleted_at')) {
            Schema::table('services', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        if (Schema::hasTable('case_studies') && Schema::hasColumn('case_studies', 'deleted_at')) {
            Schema::table('case_studies', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};
