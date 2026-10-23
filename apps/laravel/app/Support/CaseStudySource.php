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
            $needsProyectoUsa = ! Schema::hasColumn('case_studies', 'proyecto_usa');

            if ($needsSourceType || $needsClientId || $needsLinkedCase || $needsVideoUrl || $needsVideoOrientation || $needsVideoDuration || $needsProyectoUsa) {
                Schema::table('case_studies', function (Blueprint $table) use (
                    $needsSourceType, $needsClientId, $needsLinkedCase,
                    $needsVideoUrl, $needsVideoOrientation, $needsVideoDuration,
                    $needsProyectoUsa
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
                    if ($needsProyectoUsa) {
                        $table->boolean('proyecto_usa')->default(false);
                    }
                });
            }

            try {
                DB::table('case_studies')->where('categoria', 'social')->update(['categoria' => 'films']);

                $orderMap = [
                    'Compulago' => 1,
                    'Classy Cartagena' => 2,
                    'Auto Clean Bridge' => 3,
                    'Lux by LS' => 4,
                    'CESD Plataform' => 5,
                    'HB Mantenimiento' => 6,
                    'Hi Custom Blinds' => 7,
                    'SIT Plataform' => 8,
                    'Cartagena English Speaking Dentist' => 9,
                ];

                foreach ($orderMap as $title => $ord) {
                    DB::table('case_studies')->where('titulo', 'like', "%{$title}%")->update(['orden' => $ord]);
                }
            } catch (\Throwable) {
                // Ignore if update fails
            }

            self::$schemaChecked = true;
        } catch (\Throwable) {
            // Keep app safe
        }
    }
}
