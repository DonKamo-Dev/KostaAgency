<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;

final class CaseStudyImage
{
    /** @return array{imagen: null, imagen_data: string, imagen_mime: string} */
    public static function attributesFrom(UploadedFile $image): array
    {
        $contents = file_get_contents($image->getRealPath());

        if ($contents === false) {
            throw new \RuntimeException('No se pudo leer la imagen cargada.');
        }

        return [
            'imagen' => null,
            'imagen_data' => base64_encode($contents),
            'imagen_mime' => $image->getMimeType() ?: 'application/octet-stream',
        ];
    }
}
