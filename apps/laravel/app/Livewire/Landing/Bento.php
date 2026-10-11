<?php

namespace App\Livewire\Landing;

use App\Support\PublicCaseStudies;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.bento')]
class Bento extends Component
{
    public function render(PublicCaseStudies $caseStudies)
    {
        $proyectosWeb = $caseStudies->activeWeb(limit: 3);
        $proyectosFilms = $caseStudies->activeFilms(limit: 3);

        return view('livewire.landing.bento', [
            'proyectosWeb' => $proyectosWeb,
            'proyectosFilms' => $proyectosFilms,
            'proyectos' => $proyectosWeb,
        ]);
    }
}
