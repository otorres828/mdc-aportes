{{--
    CONFIRMAR APORTE
    Pantalla independiente del flujo de aportes.
    Componentes utilizados:
    - <x-aportes.shell />: Marco móvil, navegación y progreso.
    - <x-icon />: Iconografía de acciones y datos.
    - <x-form.date-input />: Fecha en español y valor ISO para Livewire.
--}}

@section('title', 'Confirmar aporte')

<x-aportes.shell
    screen="confirm"
    :back-url="route('aportes.pago')"
    :step="4"
>

    <div class="section-heading compact">

        <span class="eyebrow">
            CASI LISTO
        </span>
        <h1>
            Confirma tu aporte
        </h1>
        <p>
            Solo necesitamos los datos de tu operación.
        </p>

    </div>

    <div class="summary-card">

        <small>
            Resumen de tu aporte
        </small>
        <strong class="summary-amount">
            <span>
                Bs.
            </span>
            {{ $amountLabel }}
        </strong>

        <div class="summary-meta">

            <span>
                <x-icon :name="$types[$type][0] ?? 'heart'"/>
                {{ $type }}
            </span>
            <span>
                <x-icon :name="$method === 'Pago Móvil' ? 'phone' : 'bank'"/>
                {{ $method }}
            </span>

        </div>

    </div>

    <form wire:submit="save" class="form-stack confirm-form">

        <div class="field">

            <label for="reference">
                Número de referencia
            </label>

            <div class="input-wrap">

                <x-icon name="hash"/>
                <input id="reference" wire:model="reference" inputmode="numeric" placeholder="Ej. 123456" maxlength="30" @error ('reference') aria-invalid="true" @enderror>

            </div>

            <small class="field-help">
                Lo encontrarás en el comprobante de tu banco.
            </small>
            @error ('reference')
                <span class="error">
                    {{ $message }}
                </span>
            @enderror

        </div>

        <div class="field">

            <label for="bank">
                Banco emisor
            </label>

            <div class="input-wrap">

                <x-icon name="bank"/>
                <select id="bank" wire:model="bank" @error ('bank') aria-invalid="true" @enderror>
                    <option value="">
                        Selecciona tu banco
                    </option>
                    @foreach ($banks as $bankName)
                        <option value="{{ $bankName }}">
                            {{ $bankName }}
                        </option>
                    @endforeach
                </select>

            </div>

            @error ('bank')
                <span class="error">
                    {{ $message }}
                </span>
            @enderror

        </div>

        <div class="field date-field">

            <label for="paid-at">
                Fecha del pago
            </label>
            @if ($otherDay)

                <x-form.date-input
                    id="paid-at"
                    wire:model="paidAt"
                    :min="today()->subYears(5)->toDateString()"
                    :max="today()->toDateString()"
                />

                <button type="button" class="text-link" wire:click="$set('otherDay', false)">
                    El pago fue hoy
                </button>
            @else

                <div class="date-display">

                    <x-icon name="calendar"/>
                    <span>
                        Hoy,
                        {{ today()->translatedFormat('j \d\e F \d\e Y') }}
                    </span>

                </div>

                <button type="button" class="text-link" wire:click="$set('otherDay', true)">
                    El pago fue realizado otro día
                </button>
            @endif
            @error ('paidAt')
                <span class="error">
                    {{ $message }}
                </span>
            @enderror

        </div>

        @error ('connection')

            <div class="error-alert" role="alert">

                {{ $message }}

            </div>

        @enderror
        <button type="submit" class="button navy" wire:loading.attr="disabled" wire:target="save">
            <span wire:loading.remove wire:target="save">
                Confirmar aporte
            </span>
            <x-icon
                name="arrow"
                wire:loading.remove
                wire:target="save"
            />
            <span wire:loading wire:target="save" class="loading-label">
                <span class="spinner">
                </span>
                Confirmando…
            </span>
        </button>
    </form>
</x-aportes.shell>
