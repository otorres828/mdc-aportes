<?php

namespace App\Livewire;

use App\Services\ContributionFlow;
use App\Support\ContributionOptions;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;

class SaveAporte extends AportePage
{
    public string $amount = '1500';

    public string $type = 'Diezmo';

    public string $note = '';

    public bool $showNote = false;

    public function mount(): void
    {
        $this->restore(['amount', 'type', 'note']);
        $this->showNote = $this->note !== '';
    }

    public function render(): View
    {
        return view('livewire.save-aporte', [
            'types' => ContributionOptions::TYPES,
        ]);
    }

    public function save(): void
    {
        $data = $this->validate([
            'amount' => [
                'required',
                'regex:/^[0-9]{1,8}([.,][0-9]{1,2})?$/',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (ContributionOptions::cents((string) $value) < 1) {
                        $fail('Ingresa un monto mayor a cero.');
                    }
                },
            ],
            'type' => Rule::in(array_keys(ContributionOptions::TYPES)),
            'note' => ['nullable', 'string', 'max:500'],
        ]);
        app(ContributionFlow::class)->update($this->submissionToken, $data, 2);
        $this->redirectRoute('aportes.pago', navigate: true);
    }
}
