{{--
    DETALLE DE APORTE
    Pantalla independiente del flujo de aportes.
    Componentes utilizados:
    - <x-aportes.shell />: Marco móvil, navegación y progreso.
    - <x-aportes.detail-card />: Descripción del aporte.
    - <x-icon />: Iconografía de acciones y datos.
--}}

@section('title', 'Detalle de aporte')

<x-aportes.shell
    screen="detail"
    :back-url="route('aportes.historial')"
    title="Detalle de aporte"
>
    <x-aportes.detail-card
        :selected="$selected"
        :types="$types"
    />

    <div class="info-note {{ $selected->status === 'rejected' ? 'rejection-note' : '' }}">

        <x-icon name="info"/>
        <p>
            {{ ['pending' => 'Tu aporte está en verificación con la operación bancaria.', 'verified' => 'El aporte fue conciliado con la operación bancaria. Gracias por tu generosidad.', 'rejected' => 'No fue posible conciliar el aporte. Consulta con la administración de la iglesia para revisar los datos.'][$selected->status] ?? '' }}
        </p>

    </div>

    <a class="button navy" href="{{ route('aportes.comprobante', ['id' => $selected->id]) }}" wire:navigate>
        <x-icon name="receipt"/>
        Ver comprobante
    </a>
</x-aportes.shell>
