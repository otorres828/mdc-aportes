<?php

namespace App\Livewire;

use App\Services\ContributionFlow;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;

class SaveIdentificacion extends AportePage
{
    public string $firstName = '';

    public string $lastName = '';

    public string $identityPrefix = 'V';

    public string $identityNumber = '';

    public string $birthDate = '';

    public function mount(): void
    {
        app(ContributionFlow::class)->begin();
        $this->restore(['firstName', 'lastName', 'identityPrefix', 'identityNumber', 'birthDate']);
    }

    public function render(): View
    {
        return view('livewire.save-identificacion');
    }

    public function save(): void
    {
        $this->firstName = trim($this->firstName);
        $this->lastName = trim($this->lastName);
        $data = $this->validate([
            'firstName' => ['required', 'string', 'max:80'],
            'lastName' => ['required', 'string', 'max:80'],
            'identityPrefix' => Rule::in(['V', 'E']),
            'identityNumber' => ['required', 'regex:/^[0-9]{5,10}$/'],
            'birthDate' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
        ]);
        if ($data['birthDate'] > today()->subYears(18)->toDateString()) {
            $message = 'Debes tener al menos 18 años para continuar.';
            $this->addError('birthDate', $message);
            $this->dispatch('mdc-age-alert', message: $message);

            return;
        }
        app(ContributionFlow::class)->update($this->submissionToken, $data, 1);
        $this->redirectRoute('aportes.aporte', navigate: true);
    }
}
