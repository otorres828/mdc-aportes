<?php

use App\Livewire\Bienvenida;
use App\Livewire\SaveIdentificacion;
use App\Livewire\SaveAporte;
use App\Livewire\RealizarPago;
use App\Livewire\SaveConfirmacion;
use App\Livewire\Gracias;
use App\Livewire\ListAporte;
use App\Livewire\DetailAporte;
use App\Livewire\ComprobanteAporte;
use App\Http\Middleware\ContributionAccess;
use App\Http\Controllers\ContributionPdfController;
use Illuminate\Support\Facades\Route;

Route::livewire('/', Bienvenida::class)->middleware(ContributionAccess::class)->name('home');

Route::prefix('aportes')->name('aportes.')->group(function (): void {
    Route::livewire('/identificacion', SaveIdentificacion::class)->middleware(ContributionAccess::class)->name('identificacion');
    Route::livewire('/aporte', SaveAporte::class)->middleware(ContributionAccess::class.':1')->name('aporte');
    Route::livewire('/pago', RealizarPago::class)->middleware(ContributionAccess::class.':2')->name('pago');
    Route::livewire('/confirmar', SaveConfirmacion::class)->middleware(ContributionAccess::class.':3')->name('confirmar');
    Route::livewire('/gracias/{id}', Gracias::class)->middleware(ContributionAccess::class.':record')->whereUuid('id')->name('gracias');
    Route::livewire('/historial', ListAporte::class)->middleware(ContributionAccess::class)->name('historial');
    Route::livewire('/{id}/detalle', DetailAporte::class)->middleware(ContributionAccess::class.':record')->whereUuid('id')->name('detalle');
    Route::livewire('/{id}/comprobante', ComprobanteAporte::class)->middleware(ContributionAccess::class.':record')->whereUuid('id')->name('comprobante');
    Route::get('/{id}/pdf', ContributionPdfController::class)->middleware(ContributionAccess::class.':record')->whereUuid('id')->name('pdf');
});
