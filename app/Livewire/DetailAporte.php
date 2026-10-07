<?php

namespace App\Livewire;

use App\Support\ContributionOptions;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;

class DetailAporte extends AportePage
{
    #[Locked]
    public string $id;

    public function mount(string $id): void
    {
        $this->id = $id;
    }

    public function render(): View
    {
        return view('livewire.detail-aporte', [
            'selected' => $this->record($this->id),
            'types' => ContributionOptions::TYPES,
        ]);
    }
}
