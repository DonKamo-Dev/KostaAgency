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
            ->layout('layouts.landing');
    }
}
