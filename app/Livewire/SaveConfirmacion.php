<?php

namespace App\Livewire;

use App\Services\ContributionFlow;
use App\Services\ContributionSubmission;
use App\Support\ContributionOptions;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SaveConfirmacion extends AportePage
{
    public string $reference = '';

    public string $bank = '';

    public string $paidAt = '';

    public bool $otherDay = false;

    public function mount(): void
    {
        $this->restore(['reference', 'bank', 'paidAt', 'otherDay']);
    }

    public function render(): View
    {
        $flow = app(ContributionFlow::class)->current();

        return view('livewire.save-confirmacion', [
            'amountLabel' => ContributionOptions::amountLabel($flow['amount']),
            'type' => $flow['type'],
            'method' => $flow['method'],
            'types' => ContributionOptions::TYPES,
            'banks' => config('contributions.banks'),
        ]);
    }

    public function save(): void
    {
        $this->reference = trim($this->reference);
        if (! $this->otherDay) {
            $this->paidAt = today()->toDateString();
        }
        $data = $this->validate([
            'reference' => ['required', 'regex:/^[0-9]{6,30}$/'],
            'bank' => ['required', Rule::in(config('contributions.banks'))],
            'paidAt' => ['required', 'date', 'before_or_equal:today', 'after_or_equal:'.today()->subYears(5)->toDateString()],
            'otherDay' => ['boolean'],
        ]);
        try {
            $contribution = app(ContributionSubmission::class)->send($this->submissionToken, $data);
            $this->redirectRoute('aportes.gracias', ['id' => $contribution->id], navigate: true);
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\RuntimeException $exception) {
            $this->addError('connection', $exception->getMessage());
        } catch (\Throwable $exception) {
            report($exception);
            $this->addError('connection', 'No pudimos completar el registro. Tus datos siguen aquí; vuelve a intentar.');
        }
    }
}
