{{--
    REALIZAR APORTE
    Pantalla independiente del flujo de aportes.
    Componentes utilizados:
    - <x-aportes.shell />: Marco móvil, navegación y progreso.
    - <x-icon />: Iconografía de acciones y datos.
--}}

@section('title', 'Realizar aporte')

<x-aportes.shell
    screen="contribution"
    :back-url="route('aportes.identificacion')"
    :step="2"
>

    <div class="section-heading compact">

        <span class="eyebrow">
            DA CON EL CORAZÓN
        </span>
        <h1>
            ¿Cuánto deseas aportar?
        </h1>
        <p>
            Cada aporte cuenta y nos permite seguir sirviendo.
        </p>

    </div>

    <form wire:submit="save">

        <div class="amount-card">

            <label for="amount">
                Monto de tu aporte
            </label>

            <div class="amount-input">

                <span>
                    Bs.
                </span>
                <input id="amount" x-model="$wire.amount" inputmode="decimal" maxlength="11" aria-label="Monto en bolívares" @error ('amount') aria-invalid="true" @enderror>

            </div>

            <div class="quick-amounts">

                @foreach (['500','1000','1500','2500'] as $quick)
                    <button type="button" @click="$wire.amount = @js($quick)" :class="{ selected: $wire.amount === @js($quick) }" :aria-pressed="$wire.amount === @js($quick)">
                        {{ number_format((int)$quick, 0, ',', '.') }}
                    </button>
                @endforeach
                <button type="button" @click="document.getElementById('amount').focus(); document.getElementById('amount').select()">
                    Otro
                </button>

            </div>

        </div>

        @error ('amount')
            <span class="error">
                {{ $message }}
            </span>
        @enderror
        <fieldset class="type-fieldset">
            <legend>
                ¿Cómo deseas aportar?
            </legend>

            <div class="type-grid">

                @foreach ($types as $name => $meta)
                    <button type="button" class="type-option" :class="{ selected: $wire.type === @js($name) }" @click="$wire.type = @js($name)" :aria-pressed="$wire.type === @js($name)">
                        <span class="type-icon">
                            <x-icon :name="$meta[0]"/>
                        </span>
                        <span>
                            {{ $name }}
                        </span>
                        <span class="selection-dot" x-show="$wire.type === @js($name)">
                        </span>
                    </button>
                @endforeach

            </div>

        </fieldset>
        @foreach ($types as $name => $meta)

            <div class="type-message" x-show="$wire.type === @js($name)">

                <span class="small-icon">
                    <x-icon :name="$meta[0]"/>
                </span>

                <div>

                    <strong>
                        {{ $name }}
                    </strong>
                    <span>
                        {{ $meta[1] }}
                    </span>

                </div>

            </div>

        @endforeach

        <div class="note-section">

            <button type="button" class="note-toggle" @click="$wire.showNote = !$wire.showNote">
                <x-icon name="plus"/>
                <span x-text="$wire.showNote ? 'Ocultar nota' : 'Agregar una nota'">
                </span>
                <span>
                    (opcional)
                </span>
            </button>

            <div x-show="$wire.showNote">

                <label for="note" class="sr-only">
                    Nota opcional
                </label>
                <textarea id="note" wire:model="note" placeholder="Algo que quieras compartir con nosotros…" maxlength="500" rows="3">
                </textarea>

            </div>

            @error ('note')
                <span class="error">
                    {{ $message }}
                </span>
            @enderror

        </div>

        <button class="button navy" type="submit" wire:loading.attr="disabled">
            Continuar
            <x-icon name="arrow"/>
        </button>
    </form>
</x-aportes.shell>
