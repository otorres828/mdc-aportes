<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

/** Contexto del dispositivo y borrador compartido entre las rutas de aportes. */
class ContributionFlow
{
    public function owner(): string
    {
        $token = request()->cookie('mdc_device') ?? session('mdc_device');
        if (! is_string($token) || ! Str::isUuid($token)) {
            $token = (string) Str::uuid();
        }
        session()->put('mdc_device', $token);
        Cookie::queue(cookie('mdc_device', $token, 525600, '/', null, request()->secure(), true, false, 'lax'));

        return $token;
    }

    public function begin(): void
    {
        $flow = $this->current();
        if (app(ContributionCache::class)->find($this->owner(), $flow['id'])) {
            $this->restart(array_intersect_key($flow, array_flip([
                'firstName',
                'lastName',
                'identityPrefix',
                'identityNumber',
                'birthDate',
            ])));
        }
    }

    public function current(): array
    {
        $owner = $this->owner();
        if ($encrypted = Cache::get('mdc:flow:'.$owner)) {
            return array_merge($this->defaults(), Crypt::decrypt($encrypted));
        }
        $flow = $this->defaults();
        if ($draft = app(ContributionCache::class)->latestDraft($owner)) {
            $data = $draft['data'];
            [$prefix, $number] = explode('-', $data['identity'], 2);
            $flow = array_merge($flow, [
                'id' => $draft['id'],
                'stage' => 3,
                'firstName' => $data['first_name'],
                'lastName' => $data['last_name'],
                'identityPrefix' => $prefix,
                'identityNumber' => $number,
                'birthDate' => $data['birth_date'] ?? '',
                'amount' => number_format($data['amount_cents'] / 100, 2, '.', ''),
                'type' => $data['type'],
                'note' => $data['note'] ?? '',
                'method' => $data['method'],
                'reference' => $data['reference'],
                'bank' => $data['bank'],
                'paidAt' => $data['paid_at'],
                'otherDay' => $data['paid_at'] !== today()->toDateString(),
            ]);
        }
        $this->persist($flow);

        return $flow;
    }

    public function update(string $id, array $data, int $stage): array
    {
        $flow = $this->requireStage($id, max(0, $stage - 1));
        $flow = array_merge($flow, $data, ['stage' => $stage]);
        $this->persist($flow);

        return $flow;
    }

    public function requireStage(string $id, int $stage): array
    {
        $flow = $this->current();
        abort_unless(hash_equals($flow['id'], $id) && $flow['stage'] >= $stage, 409, 'El borrador cambió o expiró. Vuelve a comenzar.');

        return $flow;
    }

    public function restart(array $identity = []): void
    {
        $previous = $this->current();
        app(ContributionCache::class)->forgetDraft($this->owner(), $previous['id']);
        $this->persist(array_merge($this->defaults(), $identity, ['stage' => $identity ? 1 : 0]));
    }

    private function persist(array $flow): void
    {
        Cache::put('mdc:flow:'.$this->owner(), Crypt::encrypt($flow), now()->addDays((int) config('contributions.cache_days', 30)));
    }

    private function defaults(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'stage' => 0,
            'firstName' => '',
            'lastName' => '',
            'identityPrefix' => 'V',
            'identityNumber' => '',
            'birthDate' => '',
            'amount' => '1500',
            'type' => 'Diezmo',
            'note' => '',
            'method' => 'Pago Móvil',
            'reference' => '',
            'bank' => '',
            'paidAt' => today()->toDateString(),
            'otherDay' => false,
        ];
    }
}
