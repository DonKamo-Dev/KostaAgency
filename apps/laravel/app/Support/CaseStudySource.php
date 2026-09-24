<?php

namespace App\Support;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

final class CaseStudySource
{
    public static function ensureSchema(): void
    {
        if (! Schema::hasColumn('case_studies', 'source_type')) {
            Schema::table('case_studies', function (Blueprint $table) {
                $table->string('source_type', 20)->nullable()->after('categoria');
            });
        }

        if (! Schema::hasColumn('case_studies', 'client_id')) {
            Schema::table('case_studies', function (Blueprint $table) {
                $table->unsignedBigInteger('client_id')->nullable()->after('source_type');
            });
        }

        if (! Schema::hasColumn('case_studies', 'linked_case_study_id')) {
            Schema::table('case_studies', function (Blueprint $table) {
                $table->unsignedBigInteger('linked_case_study_id')->nullable()->after('client_id');
            });
        }

        try {
            DB::table('case_studies')->where('categoria', 'social')->update(['categoria' => 'films']);
        } catch (\Throwable) {
            // Ignore if table not ready
        }
    }
}
