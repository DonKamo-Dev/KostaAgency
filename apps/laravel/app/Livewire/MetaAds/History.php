<?php

namespace App\Livewire\MetaAds;

use App\Models\AiMetaQuote;
use Livewire\Component;
use Livewire\WithPagination;

class History extends Component
{
    use WithPagination;

    public string $search = '';

    public function delete(int $id): void
    {
        AiMetaQuote::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Cotización eliminada');
    }

    public function render()
    {
        $quotes = AiMetaQuote::query()
            ->completed()
            ->when($this->search, fn($q) => $q->where(function($q2) {
                $q2->where('client_name', 'like', "%{$this->search}%")
                   ->orWhere('industry', 'like', "%{$this->search}%");
            }))
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.meta-ads.history', ['quotes' => $quotes]);
    }
}
