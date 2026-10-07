{{--
    GRACIAS
    Pantalla independiente del flujo de aportes.
    Componentes utilizados:
    - <x-aportes.shell />: Marco móvil, navegación y progreso.
    - <x-icon />: Iconografía de acciones y datos.
    - <x-aportes.thanks-card />: Resumen del aporte registrado.
--}}

@section('title', 'Gracias')

<x-aportes.shell screen="thanks">
    <section class="thanks-heading">

        <div class="success-mark">

            <x-icon name="check"/>

        </div>

        <span class="eyebrow">
            TU GENEROSIDAD DEJA HUELLA
        </span>
        <h1>
            ¡Gracias por tu aporte!
        </h1>
        <p>
            {{ $selected->is_demo ? 'Guardamos tu ejemplo temporalmente en este dispositivo.' : 'La iglesia recibió la información de tu pago.' }}
            <br>
            Gracias por ser parte de esta comunidad.
        </p>
    </section>
    <x-aportes.thanks-card
        :selected="$selected"
        :types="$types"
    />

    <div class="info-note">

        <x-icon name="info"/>
        <p>
            {{ $selected->is_demo ? 'Este ejemplo no fue enviado a la iglesia.' : 'El servidor recibió el aporte y lo conciliará con la operación bancaria.' }}
        </p>

    </div>

    <a class="button navy" href="{{ route('aportes.comprobante', ['id' => $selected->id]) }}" wire:navigate>
        <x-icon name="receipt"/>
        Ver comprobante
    </a>
    <button class="button cream" wire:click="another">
        <x-icon name="plus"/>
        Realizar otro aporte
    </button>
    <a class="text-link history-link" href="{{ route('aportes.historial') }}" wire:navigate>
        Consultar mis aportes
    </a>

    <div class="verse">

        “Dios ama al dador alegre.”
        <small>
            2 Corintios 9:7
        </small>

    </div>

</x-aportes.shell>
