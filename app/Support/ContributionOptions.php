<?php

namespace App\Support;

final class ContributionOptions
{
    public const TYPES = [
        'Diezmo' => ['sprout', 'Honrando a Dios con fidelidad.'],
        'Ofrenda' => ['heart', 'Un gesto de amor que transforma vidas.'],
        'Primicias' => ['gift', 'Lo primero de nuestra cosecha, con gratitud.'],
        'Pacto' => ['hands', 'Un compromiso de fe y esperanza.'],
        'Otro' => ['dots', 'Cada acto de generosidad deja una huella.'],
    ];

    public static function cents(string $amount): int
    {
        if (! preg_match('/^[0-9]{1,8}([.,][0-9]{1,2})?$/', $amount)) {
            return 0;
        }
        $parts = explode('.', str_replace(',', '.', $amount));

        return ((int) $parts[0] * 100) + (int) str_pad($parts[1] ?? '', 2, '0');
    }

    public static function amountLabel(string $amount): string
    {
        return number_format(self::cents($amount) / 100, 2, ',', '.');
    }
}
