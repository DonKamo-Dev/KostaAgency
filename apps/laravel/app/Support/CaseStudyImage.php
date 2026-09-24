<?php

namespace App\Support;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;

final class CaseStudyImage
{
    public const DATABASE_MARKER = '__database__';

    /** @return array{imagen: string, imagen_data: string, imagen_mime: string} */
    public static function attributesFrom(UploadedFile $image): array
    {
        self::ensurePersistenceColumns();

        $contents = file_get_contents($image->getRealPath());

        if ($contents === false) {
            throw new \RuntimeException('No se pudo leer la imagen cargada.');
        }

        return [
            'imagen' => self::DATABASE_MARKER,
            'imagen_data' => base64_encode($contents),
            'imagen_mime' => $image->getMimeType() ?: 'application/octet-stream',
        ];
    }

    private static function ensurePersistenceColumns(): void
    {
        if (! Schema::hasColumn('case_studies', 'imagen_data')) {
            Schema::table('case_studies', function (Blueprint $table) {
                $table->longText('imagen_data')->nullable()->after('imagen');
            });
        }

        if (! Schema::hasColumn('case_studies', 'imagen_mime')) {
            Schema::table('case_studies', function (Blueprint $table) {
                $table->string('imagen_mime', 100)->nullable()->after('imagen_data');
            });
        }
    }
}
