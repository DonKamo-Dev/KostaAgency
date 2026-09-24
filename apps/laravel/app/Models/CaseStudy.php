<?php

namespace App\Models;

use App\Support\CaseStudyImage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class CaseStudy extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'titulo', 'descripcion', 'url_demo', 'categoria', 'source_type',
        'client_id', 'linked_case_study_id',
        'metrica_valor', 'metrica_label', 'tags',
        'gradient_inicio', 'gradient_fin', 'imagen', 'imagen_data', 'imagen_mime',
        'activo', 'orden',
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

    public function getImageUrlAttribute(): ?string
    {
        if ($this->imagen === CaseStudyImage::DATABASE_MARKER) {
            return route('case-studies.image', [
                'caseStudy' => $this,
                'v' => $this->updated_at?->timestamp,
            ]);
        }

        return $this->imagen ? Storage::disk('public')->url($this->imagen) : null;
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function linkedCaseStudy()
    {
        return $this->belongsTo(self::class, 'linked_case_study_id');
    }

    public function scopeForDisplay(Builder $query): Builder
    {
        return $query->select([
            'id', 'titulo', 'descripcion', 'url_demo', 'categoria', 'source_type',
            'client_id', 'linked_case_study_id',
            'metrica_valor', 'metrica_label', 'tags', 'gradient_inicio',
            'gradient_fin', 'imagen', 'activo', 'orden',
            'created_at', 'updated_at', 'deleted_at',
        ]);
    }
}
