<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseStudy extends Model
{
    protected $fillable = [
        'titulo', 'descripcion', 'url_demo', 'categoria',
        'metrica_valor', 'metrica_label', 'tags',
        'gradient_inicio', 'gradient_fin', 'imagen', 'activo', 'orden',
    ];

    protected $casts = [
        'tags' => 'array',
        'activo' => 'boolean',
        'orden' => 'integer',
    ];

    public function getPublicUrlAttribute(): ?string
    {
        $url = trim((string) $this->url_demo);

        if ($url === '') {
            return null;
        }

        return preg_match('#^https?://#i', $url) ? $url : "https://{$url}";
    }
}
