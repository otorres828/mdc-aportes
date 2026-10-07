<?php

namespace App\Livewire;

use App\Services\ContributionCache;
use App\Services\ContributionFlow;
use App\Support\ContributionOptions;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Url;

class ListAporte extends AportePage
{
    #[Url(except: 'Todos')]
    public string $filter = 'Todos';

    public int $historyLimit = 20;

    public bool $showExamples = false;

    public function mount(): void
    {
        app(ContributionFlow::class)->owner();
    }

    public function render(): View
    {
        $records = app(ContributionCache::class)->all(app(ContributionFlow::class)->owner());
        if (in_array($this->filter, array_keys(ContributionOptions::TYPES), true)) {
            $records = $records->filter(function ($record) {
                return $record->type === $this->filter;
            });
        }

        return view('livewire.list-aporte', [
            'groups' => $records->take($this->historyLimit)->groupBy(function ($record) {
                return $record->paid_at->translatedFormat('F Y');
            }),
            'hasMore' => $records->count() > $this->historyLimit,
            'types' => ContributionOptions::TYPES,
        ]);
    }

    public function loadMore(): void
    {
        $this->historyLimit = min($this->historyLimit + 20, 500);
    }

    public function updatedFilter(): void
    {
        $this->historyLimit = 20;
    }
}
