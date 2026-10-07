<?php

namespace App\Livewire;

use App\Services\ContributionFlow;
use App\Support\ContributionOptions;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;

class RealizarPago extends AportePage
{
    public string $method = 'Pago Móvil';

    public function mount(): void
    {
        $this->restore(['method']);
    }

    public function render(): View
    {
        $flow = app(ContributionFlow::class)->current();
        $bankData = [
            'Banco' => config('contributions.bank').' · '.config('contributions.bank_code'),
        ];
        if ($this->method === 'Pago Móvil') {
            $bankData['Número de teléfono'] = config('contributions.phone');
        } else {
            $bankData['Número de cuenta'] = config('contributions.account');
            $bankData['Tipo de cuenta'] = config('contributions.account_type');
        }
        $bankData['Cédula / RIF'] = config('contributions.rif');
        $bankData['Titular'] = config('contributions.holder');
        $copyLines = [
            'titular: '.config('contributions.holder'),
            config('contributions.bank_code'),
            config($this->method === 'Pago Móvil' ? 'contributions.phone' : 'contributions.account'),
            config('contributions.rif'),
            number_format(ContributionOptions::cents($flow['amount']) / 100, 2, '.', ''),
        ];

        return view('livewire.realizar-pago', [
            'amount' => $flow['amount'],
            'amountLabel' => ContributionOptions::amountLabel($flow['amount']),
            'bankData' => $bankData,
            'copyAll' => implode("\n", $copyLines),
        ]);
    }

    public function save(): void
    {
        $data = $this->validate([
            'method' => Rule::in(['Pago Móvil', 'Transferencia']),
        ]);
        app(ContributionFlow::class)->update($this->submissionToken, $data, 3);
        $this->redirectRoute('aportes.confirmar', navigate: true);
    }
}
