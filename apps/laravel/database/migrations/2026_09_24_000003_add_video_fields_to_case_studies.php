<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('case_studies', function (Blueprint $table) {
            if (! Schema::hasColumn('case_studies', 'video_url')) {
                $table->text('video_url')->nullable()->after('url_demo');
            }
            if (! Schema::hasColumn('case_studies', 'video_orientation')) {
                $table->string('video_orientation', 20)->default('vertical')->after('video_url');
            }
            if (! Schema::hasColumn('case_studies', 'video_duration')) {
                $table->string('video_duration', 20)->nullable()->after('video_orientation');
            }
        });
    }

    public function down(): void
    {
        Schema::table('case_studies', function (Blueprint $table) {
            if (Schema::hasColumn('case_studies', 'video_duration')) {
                $table->dropColumn('video_duration');
            }
            if (Schema::hasColumn('case_studies', 'video_orientation')) {
                $table->dropColumn('video_orientation');
            }
            if (Schema::hasColumn('case_studies', 'video_url')) {
                $table->dropColumn('video_url');
            }
        });
    }
};
