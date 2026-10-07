<?php

namespace App\Livewire;

use App\Services\ContributionFlow;
use App\Support\ContributionOptions;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;

class Gracias extends AportePage
{
    #[Locked]
    public string $id;

    public function mount(string $id): void
    {
        $this->id = $id;
    }

    public function render(): View
    {
        return view('livewire.gracias', [
            'selected' => $this->record($this->id),
            'types' => ContributionOptions::TYPES,
        ]);
    }

    public function another(): void
    {
        $record = $this->record($this->id);
        [$prefix, $number] = explode('-', $record->identity, 2);
        app(ContributionFlow::class)->restart([
            'firstName' => $record->first_name,
            'lastName' => $record->last_name,
            'identityPrefix' => $prefix,
            'identityNumber' => $number,
            'birthDate' => app(ContributionFlow::class)->current()['birthDate'],
        ]);
        $this->redirectRoute('aportes.aporte', navigate: true);
    }
}
