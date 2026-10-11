<?php

namespace App\Support;

use App\Models\CaseStudy;
use Illuminate\Database\Eloquent\Collection;

final class PublicCaseStudies
{
    /** @return Collection<int, CaseStudy> */
    public function active(?int $limit = null): Collection
    {
        CaseStudySource::ensureSchema();

        $query = CaseStudy::query()
            ->forDisplay()
            ->where('activo', true)
            ->orderBy('orden')
            ->orderBy('created_at', 'desc');

        if ($limit !== null) {
            $query->limit($limit);
        }

        return $query->get();
    }

    /** @return Collection<int, CaseStudy> */
    public function activeWeb(?int $limit = null): Collection
    {
        CaseStudySource::ensureSchema();

        $query = CaseStudy::query()
            ->forDisplay()
            ->where('activo', true)
            ->where(function ($q) {
                $q->whereNull('categoria')
                    ->orWhere('categoria', '!=', 'films');
            })
            ->orderBy('orden')
            ->orderBy('created_at', 'desc');

        if ($limit !== null) {
            $query->limit($limit);
        }

        return $query->get();
    }

    /** @return Collection<int, CaseStudy> */
    public function activeFilms(?int $limit = null): Collection
    {
        CaseStudySource::ensureSchema();

        $query = CaseStudy::query()
            ->forDisplay()
            ->where('activo', true)
            ->where('categoria', 'films')
            ->orderBy('orden')
            ->orderBy('created_at', 'desc');

        if ($limit !== null) {
            $query->limit($limit);
        }

        return $query->get();
    }
}
