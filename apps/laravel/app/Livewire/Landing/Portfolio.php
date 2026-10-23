<?php

namespace App\Livewire\Landing;

use App\Support\PublicCaseStudies;
use Livewire\Component;

class Portfolio extends Component
{
    public function render(PublicCaseStudies $caseStudies)
    {
        $estudios = $caseStudies->active();

        return view('livewire.landing.portfolio', compact('estudios'))
            ->layout('layouts.landing', [
                'title' => __('landing.portfolio.seo_title') ?? 'Proyectos de web, contenido y marca | Kosta Studio',
                'description' => __('landing.portfolio.seo_description') ?? 'Explora proyectos de Kosta Studio y conoce el contexto, nuestra participación y las entregas de cada trabajo.',
            ]);
    }
}
