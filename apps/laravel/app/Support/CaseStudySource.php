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

            if ($needsSourceType || $needsClientId || $needsLinkedCase) {
                Schema::table('case_studies', function (Blueprint $table) use ($needsSourceType, $needsClientId, $needsLinkedCase) {
                    if ($needsSourceType) {
                        $table->string('source_type', 20)->nullable();
                    }
                    if ($needsClientId) {
                        $table->unsignedBigInteger('client_id')->nullable();
                    }
                    if ($needsLinkedCase) {
                        $table->unsignedBigInteger('linked_case_study_id')->nullable();
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
