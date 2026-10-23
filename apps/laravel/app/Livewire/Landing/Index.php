<?php

namespace App\Livewire\Landing;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.landing.index')
            ->layout('layouts.landing', [
                'title' => __('landing.seo.title'),
                'description' => __('landing.seo.description'),
            ]);
    }
}
