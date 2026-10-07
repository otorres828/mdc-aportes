{{--
    REALIZAR PAGO
    Pantalla independiente del flujo de aportes.
    Componentes utilizados:
    - <x-aportes.shell />: Marco móvil, navegación y progreso.
    - <x-icon />: Iconografía de acciones y datos.
--}}

@section('title', 'Realizar pago')

<x-aportes.shell
    screen="payment"
    :back-url="route('aportes.aporte')"
    :step="3"
>

    <div class="section-heading compact">

        <span class="eyebrow">
            UN PASO MÁS
        </span>
        <h1>
            Realiza tu pago
        </h1>
        <p>
            Desde la aplicación de tu banco, utiliza estos datos.
        </p>

    </div>

    {{-- <div class="segmented" role="group" aria-label="Método de pago">

        @foreach (['Pago Móvil' => 'phone', 'Transferencia' => 'bank'] as $label => $icon)
            <button wire:click="$set('method', '{{ $label }}')" class="{{ $method === $label ? 'active' : '' }}" aria-pressed="{{ $method === $label ? 'true' : 'false' }}">
                <x-icon :name="$icon"/>
                {{ $label }}
            </button>
        @endforeach

    </div> --}}

    <div class="payment-amount">

        <div>

            <span>
                Monto a aportar
            </span>
            <strong>
                <small>
                    Bs.
                </small>
                {{ $amountLabel }}
            </strong>

        </div>

        <button class="icon-button copy-square" data-copy="{{ str_replace('.', ',', $amount) }}" aria-label="Copiar monto">
            <x-icon name="copy"/>
        </button>

    </div>

    @if (config('contributions.demo'))

        <div class="demo-notice">

            <x-icon name="info"/>
            Datos de ejemplo. No realices un pago a estos datos.

        </div>

    @endif
    <div class="bank-heading">

        <h2 class="small-heading">Datos para {{ $method }}</h2>
        <button type="button" class="copy-button" data-copy="{{ $copyAll }}" data-copy-message="Todos los datos copiados">
            <x-icon name="copy"/>
            <span>Copiar todo</span>
        </button>

    </div>

    <div class="bank-card">

        @foreach ($bankData as $label => $value)

            <div class="bank-row">

                <div>

                    <small>
                        {{ $label }}
                    </small>
                    <strong>
                        {{ $value }}
                    </strong>

                </div>

                <button class="copy-button" data-copy="{{ $label === 'Banco' ? config('contributions.bank_code') : $value }}" aria-label="Copiar {{ $label }}">
                    <x-icon name="copy"/>
                    <span>
                        Copiar
                    </span>
                </button>

            </div>

        @endforeach

    </div>

    <div class="info-note">

        <x-icon name="info"/>
        <p>
            Cuando termines en tu banco, vuelve aquí para registrar la referencia.
        </p>

    </div>

    <button class="button gold" wire:click="save">
        Ya realicé el pago
        <x-icon name="arrow"/>
    </button>
</x-aportes.shell>
