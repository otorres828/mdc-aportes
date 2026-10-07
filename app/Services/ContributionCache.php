<?php

namespace App\Services;

use App\Data\Contribution;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class ContributionCache
{
    public function key(string $owner): string
    {
        return 'mdc:contributions:'.$owner;
    }

    public function records(string $owner): Collection
    {
        $encrypted = Cache::get($this->key($owner));

        return collect($encrypted ? Crypt::decrypt($encrypted) : []);
    }

    public function all(string $owner): Collection
    {
        return $this->records($owner)->map(fn ($data) => new Contribution($data))->sortByDesc(fn ($item) => $item->paid_at->format('Y-m-d').$item->created_at->format('Y-m-d H:i:s'));
    }

    public function find(string $owner, string $id): ?Contribution
    {
        $data = $this->records($owner)->get($id);

        return $data ? new Contribution($data) : null;
    }

    public function save(string $owner, array $data): Contribution
    {
        return Cache::lock('mdc:contributions-lock:'.$owner, 15)->block(3, function () use ($owner, $data) {
            $records = $this->records($owner);
            $records->put($data['id'], $data);
            Cache::put($this->key($owner), Crypt::encrypt($records->all()), now()->addDays((int) config('contributions.cache_days', 30)));

            return new Contribution($data);
        });
    }

    public function draft(string $owner, string $id, array $data): void
    {
        Cache::put('mdc:draft:'.$owner.':'.$id, Crypt::encrypt($data), now()->addDays((int) config('contributions.cache_days', 30)));
        Cache::put('mdc:latest-draft:'.$owner, $id, now()->addDays((int) config('contributions.cache_days', 30)));
    }

    public function latestDraft(string $owner): ?array
    {
        $id = Cache::get('mdc:latest-draft:'.$owner);
        $encrypted = $id ? Cache::get('mdc:draft:'.$owner.':'.$id) : null;

        return $encrypted ? ['id' => $id, 'data' => Crypt::decrypt($encrypted)] : null;
    }

    public function forgetDraft(string $owner, string $id): void
    {
        Cache::forget('mdc:draft:'.$owner.':'.$id);
        if (Cache::get('mdc:latest-draft:'.$owner) === $id) {
            Cache::forget('mdc:latest-draft:'.$owner);
        }
    }
}
