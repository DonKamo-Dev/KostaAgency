<?php

namespace App\Support;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

final class CaseStudySource
{
    private static bool $schemaChecked = false;

    public static function ensureSchema(): void
    {
        if (self::$schemaChecked) {
            return;
        }

        try {
            if (! Schema::hasTable('case_studies')) {
                return;
            }

            $needsSourceType = ! Schema::hasColumn('case_studies', 'source_type');
            $needsClientId = ! Schema::hasColumn('case_studies', 'client_id');
            $needsLinkedCase = ! Schema::hasColumn('case_studies', 'linked_case_study_id');
            $needsVideoUrl = ! Schema::hasColumn('case_studies', 'video_url');
            $needsVideoOrientation = ! Schema::hasColumn('case_studies', 'video_orientation');
            $needsVideoDuration = ! Schema::hasColumn('case_studies', 'video_duration');

            if ($needsSourceType || $needsClientId || $needsLinkedCase || $needsVideoUrl || $needsVideoOrientation || $needsVideoDuration) {
                Schema::table('case_studies', function (Blueprint $table) use (
                    $needsSourceType, $needsClientId, $needsLinkedCase,
                    $needsVideoUrl, $needsVideoOrientation, $needsVideoDuration
                ) {
                    if ($needsSourceType) {
                        $table->string('source_type', 20)->nullable();
                    }
                    if ($needsClientId) {
                        $table->unsignedBigInteger('client_id')->nullable();
                    }
                    if ($needsLinkedCase) {
                        $table->unsignedBigInteger('linked_case_study_id')->nullable();
                    }
                    if ($needsVideoUrl) {
                        $table->text('video_url')->nullable();
                    }
                    if ($needsVideoOrientation) {
                        $table->string('video_orientation', 20)->default('vertical');
                    }
                    if ($needsVideoDuration) {
                        $table->string('video_duration', 20)->nullable();
                    }
                });
            }

            try {
                DB::table('case_studies')->where('categoria', 'social')->update(['categoria' => 'films']);
            } catch (\Throwable) {
                // Ignore if update fails
            }

            self::$schemaChecked = true;
        } catch (\Throwable) {
            // Keep app safe
        }
    }
}
