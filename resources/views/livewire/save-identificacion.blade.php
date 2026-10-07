{{--
    IDENTIFICACIÓN
    Pantalla independiente del flujo de aportes.
    Componentes utilizados:
    - <x-aportes.shell />: Marco móvil, navegación y progreso.
    - <x-icon />: Iconografía de acciones y datos.
--}}

@section('title', 'Identificación')

<x-aportes.shell
    screen="identity"
    :back-url="route('home')"
    :step="1"
>

    <div class="section-heading">

        <span class="eyebrow">
            BIENVENIDO A CASA
        </span>
        <h1>
            Antes de comenzar
        </h1>
        <p>
            Cuéntanos quién eres para identificar tu aporte.
        </p>

    </div>

    <form wire:submit="save" class="form-stack">

        <div class="field">

            <label for="first-name">
                Nombre
            </label>

            <div class="input-wrap">

                <x-icon name="user"/>
                <input id="first-name" wire:model="firstName" placeholder="Ingresa tu nombre" autocomplete="given-name" maxlength="80" @error ('firstName') aria-invalid="true" aria-describedby="firstName-error" @enderror>

            </div>

            @error ('firstName')
                <span class="error" id="firstName-error">
                    {{ $message }}
                </span>
            @enderror

        </div>

        <div class="field">

            <label for="last-name">
                Apellido
            </label>

            <div class="input-wrap">

                <x-icon name="user"/>
                <input id="last-name" wire:model="lastName" placeholder="Ingresa tu apellido" autocomplete="family-name" maxlength="80" @error ('lastName') aria-invalid="true" @enderror>

            </div>

            @error ('lastName')
                <span class="error">
                    {{ $message }}
                </span>
            @enderror

        </div>

        <div class="field">

            <label for="identity">
                Cédula de identidad
            </label>

            <div class="input-wrap identity-input">

                <select wire:model="identityPrefix" aria-label="Tipo de cédula">
                    <option>
                        V
                    </option>
                    <option>
                        E
                    </option>
                </select>
                <input id="identity" wire:model="identityNumber" placeholder="Ej. 12345678" inputmode="numeric" maxlength="10" @error ('identityNumber') aria-invalid="true" @enderror>

            </div>

            @error ('identityNumber')
                <span class="error">
                    {{ $message }}
                </span>
            @enderror

        </div>

        <div class="field">

            <label for="birth-date">Fecha de nacimiento</label>

            <x-form.date-input
                id="birth-date"
                wire:model="birthDate"
                :max="today()->toDateString()"
            />

            @error ('birthDate')
                <span class="error" id="birthDate-error" role="alert">{{ $message }}</span>
            @enderror

        </div>

        <button type="submit" class="button navy" wire:loading.attr="disabled">
            Continuar
            <x-icon name="arrow"/>
        </button>
    </form>

    <div class="security">

        <x-icon name="lock"/>
        <span>
            Tus datos son tratados de forma
            <br>
            segura y confidencial.
        </span>

    </div>

</x-aportes.shell>
