<?php

namespace App\Support;

use App\Models\CaseStudy;
use Illuminate\Database\Eloquent\Collection;

final class PublicCaseStudies
{
    /** @return Collection<int, CaseStudy> */
    public function active(?int $limit = null): Collection
    {
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
}
