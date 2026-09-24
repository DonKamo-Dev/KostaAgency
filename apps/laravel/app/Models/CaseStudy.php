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
        'video_url', 'video_orientation', 'video_duration',
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

    public function getVideoStreamUrlAttribute(): ?string
    {
        $url = trim((string) $this->video_url);

        if ($url === '') {
            return null;
        }

        if (str_starts_with($url, '/storage/') || str_starts_with($url, 'storage/')) {
            return asset(ltrim($url, '/'));
        }

        return $url;
    }

    public function getIsReelAttribute(): bool
    {
        return ($this->video_orientation ?? 'vertical') === 'vertical';
    }

    public function getIsHorizontalAttribute(): bool
    {
        return ($this->video_orientation ?? '') === 'horizontal';
    }

    public function getIsDirectVideoAttribute(): bool
    {
        return \App\Support\CaseStudyVideo::isDirectVideo($this->video_url);
    }

    public function getVideoEmbedUrlAttribute(): ?string
    {
        return \App\Support\CaseStudyVideo::resolveEmbedUrl($this->video_url);
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
        \App\Support\CaseStudySource::ensureSchema();

        $columns = [
            'id', 'titulo', 'descripcion', 'url_demo', 'categoria',
            'metrica_valor', 'metrica_label', 'tags', 'gradient_inicio',
            'gradient_fin', 'imagen', 'activo', 'orden',
            'created_at', 'updated_at', 'deleted_at',
        ];

        try {
            if (\Illuminate\Support\Facades\Schema::hasColumn('case_studies', 'source_type')) {
                $columns[] = 'source_type';
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('case_studies', 'client_id')) {
                $columns[] = 'client_id';
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('case_studies', 'linked_case_study_id')) {
                $columns[] = 'linked_case_study_id';
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('case_studies', 'video_url')) {
                $columns[] = 'video_url';
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('case_studies', 'video_orientation')) {
                $columns[] = 'video_orientation';
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('case_studies', 'video_duration')) {
                $columns[] = 'video_duration';
            }
        } catch (\Throwable) {
            // Graceful fallback
        }

        return $query->select($columns);
    }
}
