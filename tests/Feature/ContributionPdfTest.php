<?php

namespace Tests\Feature;

use App\Livewire\ComprobanteAporte;
use App\Services\ContributionCache;
use App\Services\ContributionFlow;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class ContributionPdfTest extends TestCase
{
    public function test_receipt_download_returns_a_pdf_and_rechecks_ownership(): void
    {
        config(['cache.default' => 'array']);
        Cache::flush();
        session()->put('mdc_device', (string) Str::uuid());
        $id = (string) Str::uuid();
        $record = app(ContributionCache::class)->save(app(ContributionFlow::class)->owner(), [
            'id' => $id,
            'number' => 'DEMO-2026-TEST',
            'first_name' => 'María',
            'last_name' => 'Prueba',
            'identity' => 'V-12345678',
            'amount_cents' => 150025,
            'type' => 'Ofrenda',
            'note' => str_repeat('Nota con acentos y paréntesis (prueba). ', 12),
            'method' => 'Pago Móvil',
            'reference' => '123456789',
            'bank' => 'Banesco',
            'paid_at' => today()->toDateString(),
            'created_at' => now()->toIso8601String(),
            'status' => 'pending',
            'is_demo' => true,
        ]);
        $pdf = app(\App\Services\ContributionPdf::class)->generate($record);
        $this->assertStringStartsWith('%PDF-', $pdf);
        $this->assertStringContainsString('%%EOF', $pdf);
        $response = $this->get('/aportes/'.$id.'/pdf')->assertOk()->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringStartsWith('%PDF-', $response->streamedContent());
        Livewire::test(ComprobanteAporte::class, ['id' => $id])->call('download')->assertFileDownloaded('aporte-'.$id.'.pdf');
        session()->put('mdc_device', (string) Str::uuid());
        $this->get('/aportes/'.$id.'/comprobante')->assertNotFound();
        $this->get('/aportes/'.$id.'/pdf')->assertNotFound();
    }
}
