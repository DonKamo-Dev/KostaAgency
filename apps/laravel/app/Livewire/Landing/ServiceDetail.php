<?php

namespace App\Livewire\Landing;

use App\Models\CaseStudy;
use Livewire\Component;

class ServiceDetail extends Component
{
    public string $slug = '';

    public array $service = [];

    public $relatedStudies;

    public function mount(string $slug): void
    {
        $all = self::allServices();
        abort_unless(isset($all[$slug]), 404);

        $this->slug = $slug;
        $this->service = $all[$slug];

        $cat = $this->service['category_key'] ?? null;
        try {
            $this->relatedStudies = $cat
                ? CaseStudy::where('activo', true)
                    ->forDisplay()
                    ->where('categoria', $cat)
                    ->orderBy('orden')
                    ->take(3)
                    ->get()
                : collect();
        } catch (\Throwable) {
            $this->relatedStudies = collect();
        }
    }

    public static function allServices(): array
    {
        $services = trans('services.items');

        return is_array($services) ? $services : [];
    }

    public function render()
    {
        return view('livewire.landing.service-detail', [
            'otherServices' => collect(self::allServices())->except($this->slug)->values(),
        ])->layout('layouts.landing');
    }
}
