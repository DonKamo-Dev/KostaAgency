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
        $proyectos = $caseStudies->active(limit: 3);

        return view('livewire.landing.bento', [
            'proyectos' => $proyectos,
        ]);
    }
}
