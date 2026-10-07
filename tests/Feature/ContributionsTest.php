<?php

namespace Tests\Feature;

use App\Livewire\Gracias;
use App\Livewire\RealizarPago;
use App\Livewire\SaveAporte;
use App\Livewire\SaveConfirmacion;
use App\Livewire\SaveIdentificacion;
use App\Services\ContributionCache;
use App\Services\ContributionFlow;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Tests\TestCase;

class ContributionsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['cache.default' => 'array', 'contributions.demo' => true]);
        Cache::flush();
        session()->put('mdc_device', (string) Str::uuid());
        Http::preventStrayRequests();
        DB::shouldReceive('connection')->never();
    }

    private function readyToConfirm(): Testable
    {
        Livewire::test(SaveIdentificacion::class)
            ->set('firstName', 'María')->set('lastName', 'González')->set('identityNumber', '12345678')->set('birthDate', '1990-05-10')
            ->call('save')->assertRedirect(route('aportes.aporte'));
        Livewire::test(SaveAporte::class)->set('amount', '1500,25')->set('type', 'Ofrenda')
            ->call('save')->assertRedirect(route('aportes.pago'));
        Livewire::test(RealizarPago::class)->call('save')->assertRedirect(route('aportes.confirmar'));

        return Livewire::test(SaveConfirmacion::class)->set('reference', '123456789')->set('bank', 'Banesco');
    }

    public function test_home_hides_history_and_steps_cannot_be_skipped(): void
    {
        $this->get('/')->assertOk()->assertSee('Tu generosidad')->assertDontSee('Mis aportes');
        $this->get('/aportes/confirmar')->assertRedirect(route('aportes.identificacion'));
        $this->get('/aportes/pago')->assertRedirect(route('aportes.identificacion'));
    }

    public function test_copy_all_exports_plain_bank_data_and_decimal_amount(): void
    {
        $this->readyToConfirm();
        config([
            'contributions.holder' => 'Mansion de cristo',
            'contributions.bank_code' => '0105',
            'contributions.phone' => '04148848537',
            'contributions.account' => '01050000000000000001',
            'contributions.rif' => 'J-00000000',
        ]);
        Livewire::test(SaveAporte::class)->set('amount', '3500')->call('save')->assertHasNoErrors();
        Livewire::test(RealizarPago::class)
            ->assertViewHas('copyAll', "titular: Mansion de cristo\n0105\n04148848537\nJ-00000000\n3500.00")
            ->set('method', 'Transferencia')
            ->assertViewHas('copyAll', "titular: Mansion de cristo\n0105\n01050000000000000001\nJ-00000000\n3500.00");
        Livewire::test(SaveAporte::class)->set('amount', '1500,25')->call('save')->assertHasNoErrors();
        Livewire::test(RealizarPago::class)
            ->assertViewHas('copyAll', "titular: Mansion de cristo\n0105\n04148848537\nJ-00000000\n1500.25");
    }

    public function test_birth_date_blocks_minors_and_accepts_the_eighteenth_birthday(): void
    {
        $this->travelTo(\Illuminate\Support\Carbon::parse('2026-10-07 12:00:00'));
        $component = Livewire::test(SaveIdentificacion::class)
            ->set('firstName', 'María')->set('lastName', 'González')->set('identityNumber', '12345678');
        $component->set('birthDate', '2008-10-08')->call('save')
            ->assertHasErrors('birthDate')->assertNoRedirect()->assertDispatched('mdc-age-alert');
        $this->assertSame(0, app(ContributionFlow::class)->current()['stage']);
        $component->set('birthDate', '2027-01-01')->call('save')->assertHasErrors('birthDate')->assertNoRedirect();
        $component->set('birthDate', '2008-10-07')->call('save')->assertHasNoErrors()
            ->assertRedirect(route('aportes.aporte'));
        Livewire::test(SaveIdentificacion::class)->assertSet('birthDate', '2008-10-07');
        $this->travelBack();
    }

    public function test_identity_and_amount_validation(): void
    {
        Livewire::test(SaveIdentificacion::class)->call('save')->assertHasErrors(['firstName', 'lastName', 'identityNumber']);
        $this->readyToConfirm();
        Livewire::test(SaveAporte::class)->set('amount', '0')->call('save')->assertHasErrors('amount');
    }

    public function test_draft_survives_back_navigation_and_reload(): void
    {
        $this->readyToConfirm();
        Livewire::test(SaveIdentificacion::class)->assertSet('firstName', 'María');
        Livewire::test(SaveAporte::class)->assertSet('amount', '1500,25')->assertSet('type', 'Ofrenda');
        $this->get('/aportes/pago')->assertOk()->assertSee('1.500,25');
        $this->get('/aportes/confirmar')->assertOk()->assertSee('1.500,25');
    }

    public function test_demo_is_encrypted_and_confirmation_is_idempotent(): void
    {
        $component = $this->readyToConfirm();
        $id = $component->get('submissionToken');
        $component->call('save')->assertHasNoErrors()->assertRedirect(route('aportes.gracias', $id))->call('save');
        $owner = app(ContributionFlow::class)->owner();
        $store = app(ContributionCache::class);
        $record = $store->find($owner, $id);
        $this->assertSame(150025, $record->amount_cents);
        $this->assertSame('V-12345678', $record->identity);
        $this->assertSame('pending', $record->status);
        $this->assertCount(1, $store->all($owner));
        $this->assertStringNotContainsString('12345678', Cache::get($store->key($owner)));
        $this->assertStringNotContainsString('12345678', Cache::get('mdc:flow:'.$owner));
        Http::assertNothingSent();
    }

    public function test_duplicate_reference_is_blocked_after_another_contribution(): void
    {
        $component = $this->readyToConfirm();
        $id = $component->get('submissionToken');
        $component->call('save');
        Livewire::test(Gracias::class, ['id' => $id])->call('another')->assertRedirect(route('aportes.aporte'));
        Livewire::test(SaveAporte::class)->call('save');
        Livewire::test(RealizarPago::class)->call('save');
        Livewire::test(SaveConfirmacion::class)->set('bank', 'Banesco')->set('reference', '123456789')
            ->call('save')->assertHasErrors('reference');
    }

    public function test_confirmation_rejects_future_date_and_missing_fields(): void
    {
        $this->readyToConfirm()->set('otherDay', true)->set('paidAt', today()->addDay()->toDateString())
            ->call('save')->assertHasErrors('paidAt');
        Livewire::test(SaveConfirmacion::class)->call('save')->assertHasErrors(['bank', 'reference']);
    }

    public function test_history_detail_receipt_and_thanks_have_distinct_routes(): void
    {
        $component = $this->readyToConfirm();
        $id = $component->get('submissionToken');
        $component->call('save');
        $this->get('/aportes/historial')->assertOk()->assertSee('1.500,25');
        $this->get('/aportes/'.$id.'/detalle')->assertOk()->assertSee('123456789');
        $this->get('/aportes/'.$id.'/comprobante')->assertOk()->assertSee('Comprobante de aporte');
        $this->get('/aportes/gracias/'.$id)->assertOk()->assertSee('¡Gracias por tu aporte!');
    }

    public function test_other_device_cannot_read_receipts_and_expired_history_is_usable(): void
    {
        $component = $this->readyToConfirm();
        $id = $component->get('submissionToken');
        $component->call('save');
        session()->put('mdc_device', (string) Str::uuid());
        $this->get('/aportes/'.$id.'/detalle')->assertNotFound();
        $this->get('/aportes/'.$id.'/comprobante')->assertNotFound();
        $this->get('/aportes/gracias/'.$id)->assertNotFound();
        Cache::flush();
        $this->get('/aportes/historial')->assertOk()->assertSee('Tu historia empieza aquí');
    }

    public function test_missing_api_never_reports_success(): void
    {
        config(['contributions.demo' => false, 'contributions.api_url' => '']);
        $this->readyToConfirm()->call('save')->assertHasErrors('connection')->assertNoRedirect();
        $owner = app(ContributionFlow::class)->owner();
        $this->assertCount(0, app(ContributionCache::class)->all($owner));
        $this->assertNotNull(app(ContributionCache::class)->latestDraft($owner));
    }

    public function test_api_success_keeps_server_receipt_and_idempotency_key(): void
    {
        config(['contributions.demo' => false, 'contributions.api_url' => 'https://church.example/api/contributions']);
        Http::fake(['church.example/*' => Http::response(['data' => ['id' => 'server-123', 'number' => 'MC-2026-012345']], 201)]);
        $component = $this->readyToConfirm();
        $key = $component->get('submissionToken');
        $component->call('save')->assertHasNoErrors()->assertRedirect(route('aportes.gracias', $key))->call('save');
        Livewire::test(Gracias::class, ['id' => $key])->assertSee('MC-2026-012345');
        Http::assertSentCount(1);
        Http::assertSent(function ($request) use ($key) {
            return $request->hasHeader('Idempotency-Key', $key) && $request['amount_cents'] === 150025 && $request['currency'] === 'VES';
        });
    }

    public function test_api_failure_preserves_reference_and_key_after_reload(): void
    {
        config(['contributions.demo' => false, 'contributions.api_url' => 'https://church.example/api/contributions']);
        Http::fake(['church.example/*' => Http::sequence()->push([], 503)->push(['data' => ['id' => 'server-1', 'number' => 'MC-1']], 201)]);
        $component = $this->readyToConfirm();
        $key = $component->get('submissionToken');
        $component->call('save')->assertHasErrors('connection')->assertNoRedirect();
        Livewire::test(SaveConfirmacion::class)->assertSet('reference', '123456789')->assertSet('submissionToken', $key)
            ->call('save')->assertRedirect(route('aportes.gracias', $key));
        Http::assertSentCount(2);
    }

    public function test_api_invalid_response_and_conflict_do_not_confirm(): void
    {
        config(['contributions.demo' => false, 'contributions.api_url' => 'https://church.example/api/contributions']);
        Http::fake(['church.example/*' => Http::sequence()->push(['ok' => true], 200)->push([], 409)]);
        $component = $this->readyToConfirm()->call('save')->assertNoRedirect()->assertHasErrors('connection');
        $component->call('save')->assertNoRedirect()->assertHasErrors('connection');
    }

    public function test_start_after_success_uses_a_new_uuid_and_keeps_identity(): void
    {
        $component = $this->readyToConfirm();
        $id = $component->get('submissionToken');
        $component->call('save');
        $new = Livewire::test(SaveIdentificacion::class)->assertSet('firstName', 'María');
        $this->assertNotSame($id, $new->get('submissionToken'));
        Livewire::test(SaveAporte::class)->assertSet('amount', '1500')->assertSet('type', 'Diezmo');
    }

    public function test_saving_an_earlier_step_invalidates_later_routes(): void
    {
        $this->readyToConfirm();
        Livewire::test(SaveAporte::class)->set('amount', '500')->call('save');
        $this->get('/aportes/confirmar')->assertRedirect(route('aportes.pago'));
    }

    public function test_alternate_date_renders_the_shared_spanish_date_component(): void
    {
        $this->readyToConfirm()->set('otherDay', true)->assertSee('mdcDateInput', false)->assertSee('dd/mm/aaaa');
    }
}
