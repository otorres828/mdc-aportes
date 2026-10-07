<?php

namespace App\Livewire;

use App\Data\Contribution;
use App\Services\ContributionCache;
use App\Services\ContributionFlow;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

#[Layout('components.layouts.app')]
abstract class AportePage extends Component
{
    #[Locked]
    public string $submissionToken = '';

    protected function restore(array $properties): void
    {
        $flow = app(ContributionFlow::class)->current();
        $this->submissionToken = $flow['id'];
        foreach ($properties as $property) {
            $this->{$property} = $flow[$property];
        }
    }

    protected function record(string $id): Contribution
    {
        $record = app(ContributionCache::class)->find(app(ContributionFlow::class)->owner(), $id);
        abort_unless($record, 404);

        return $record;
    }

    protected function messages(): array
    {
        return [
            'required' => 'Este campo es obligatorio.',
            'max' => 'El campo supera el máximo permitido.',
            'identityNumber.regex' => 'Ingresa entre 5 y 10 números, sin puntos.',
            'amount.regex' => 'Ingresa un monto válido, con hasta dos decimales.',
            'reference.regex' => 'Ingresa de 6 a 30 números de la referencia.',
            'bank.in' => 'Selecciona tu banco emisor.',
            'paidAt.before_or_equal' => 'La fecha no puede ser futura.',
            'paidAt.after_or_equal' => 'Revisa la fecha del pago.',
            'paidAt.date' => 'Selecciona una fecha válida.',
        ];
    }
}
