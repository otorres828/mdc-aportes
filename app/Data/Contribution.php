<?php

namespace App\Data;

use Carbon\Carbon;

/** Presentation data; no Eloquent model or database connection. */
final class Contribution
{
    public string $id;

    public string $number;

    public string $formatted_amount;

    public Carbon $paid_at;

    public Carbon $created_at;

    public string $first_name;

    public string $last_name;

    public string $identity;

    public int $amount_cents;

    public string $type;

    public ?string $note;

    public string $method;

    public string $reference;

    public string $bank;

    public string $status;

    public bool $is_demo;

    public function __construct(array $data)
    {
        foreach (['id', 'number', 'first_name', 'last_name', 'identity', 'type', 'method', 'reference', 'bank', 'status'] as $field) {
            $this->{$field} = (string) $data[$field];
        }
        $this->note = $data['note'] ?? null;
        $this->amount_cents = (int) $data['amount_cents'];
        $this->is_demo = (bool) $data['is_demo'];
        $this->paid_at = Carbon::parse($data['paid_at']);
        $this->created_at = Carbon::parse($data['created_at']);
        $this->formatted_amount = number_format($this->amount_cents / 100, 2, ',', '.');
    }
}
