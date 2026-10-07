<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class ContributionApi
{
    public function send(array $data, string $idempotencyKey): array
    {
        $url = (string) config('contributions.api_url');
        if (! filter_var($url, FILTER_VALIDATE_URL) || ! in_array(parse_url($url, PHP_URL_SCHEME), ['http', 'https'], true)) {
            throw new RuntimeException('El envío aún no está disponible. Falta configurar la API de la iglesia.');
        }
        $request = Http::acceptJson()->asJson()->connectTimeout(3)->timeout(10)->withHeaders(['Idempotency-Key' => $idempotencyKey]);
        // Authentication belongs to the signed-in user, not a secret embedded in the APK.
        if ($token = session('api_token')) {
            $request = $request->withToken($token);
        }
        try {
            $response = $request->post($url, array_intersect_key($data, array_flip(['first_name', 'last_name', 'identity', 'amount_cents', 'type', 'note', 'method', 'reference', 'bank', 'paid_at'])) + ['currency' => 'VES', 'client_id' => $idempotencyKey]);
        } catch (ConnectionException $e) {
            throw new RuntimeException('No pudimos conectar con la iglesia. Conservamos tus datos temporalmente; vuelve a intentar.', previous: $e);
        }
        if ($response->status() === 409) {
            throw new RuntimeException('Esta referencia ya fue registrada en el servidor. Revisa los datos de la operación.');
        }
        if (! $response->successful()) {
            throw new RuntimeException('El servidor no pudo recibir el aporte. Tus datos siguen aquí; vuelve a intentar.');
        }
        $remote = $response->json('data');
        if (! is_array($remote) || ! isset($remote['id']) || ! is_scalar($remote['id']) || trim((string) $remote['id']) === '' || strlen((string) $remote['id']) > 100 || ! isset($remote['number']) || ! is_string($remote['number']) || trim($remote['number']) === '' || strlen($remote['number']) > 80) {
            throw new RuntimeException('El servidor respondió sin un comprobante válido. Vuelve a intentar con los mismos datos.');
        }

        return ['remote_id' => (string) $remote['id'], 'number' => $remote['number']];
    }
}
