<?php

namespace App\Livewire;

use App\Services\ContributionFlow;
use Illuminate\Contracts\View\View;

class Bienvenida extends AportePage
{
    public function mount(): void
    {
        app(ContributionFlow::class)->owner();
    }

    public function render(): View
    {
        return view('livewire.bienvenida');
    }
}
