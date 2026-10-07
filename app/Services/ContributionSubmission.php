<?php

namespace App\Services;

use App\Data\Contribution;
use App\Support\ContributionOptions;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

/** Registro idempotente del pago; conserva el borrador ante un fallo de la API. */
class ContributionSubmission
{
    public function send(string $id, array $payment): Contribution
    {
        $flowService = app(ContributionFlow::class);
        $owner = $flowService->owner();
        $store = app(ContributionCache::class);

        return Cache::lock('mdc:send:'.$owner.':'.$id, 30)->block(2, function () use ($id, $payment, $flowService, $owner, $store) {
            if ($existing = $store->find($owner, $id)) {
                return $existing;
            }
            $flow = $flowService->requireStage($id, 3);
            if ($store->all($owner)->contains(function ($item) use ($payment) {
                return $item->bank === $payment['bank']
                    && $item->reference === $payment['reference']
                    && $item->paid_at->toDateString() === $payment['paidAt'];
            })) {
                throw ValidationException::withMessages([
                    'reference' => 'Esta referencia ya fue registrada anteriormente para ese banco y fecha.',
                ]);
            }
            $flowService->update($id, $payment, 3);
            $data = [
                'id' => $id,
                'number' => 'DEMO-'.now()->format('Y').'-'.strtoupper(substr($id, 0, 8)),
                'created_at' => now()->toIso8601String(),
                'first_name' => $flow['firstName'],
                'last_name' => $flow['lastName'],
                'identity' => $flow['identityPrefix'].'-'.$flow['identityNumber'],
                'birth_date' => $flow['birthDate'],
                'amount_cents' => ContributionOptions::cents($flow['amount']),
                'type' => $flow['type'],
                'note' => $flow['note'] ?: null,
                'method' => $flow['method'],
                'reference' => $payment['reference'],
                'bank' => $payment['bank'],
                'paid_at' => $payment['paidAt'],
                'status' => 'pending',
                'is_demo' => config('contributions.demo'),
            ];
            $store->draft($owner, $id, $data);
            if (! $data['is_demo']) {
                $data = array_merge($data, app(ContributionApi::class)->send($data, $id));
            }
            $contribution = $store->save($owner, $data);
            $store->forgetDraft($owner, $id);

            return $contribution;
        });
    }
}
