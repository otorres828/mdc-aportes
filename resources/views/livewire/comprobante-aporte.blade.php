{{--
    COMPROBANTE
    Pantalla independiente del flujo de aportes.
    Componentes utilizados:
    - <x-aportes.shell />: Marco móvil, navegación y progreso.
    - <x-aportes.receipt-paper />: Comprobante imprimible.
    - <x-icon />: Iconografía de acciones y datos.
--}}

@section('title', 'Comprobante')

<x-aportes.shell
    screen="receipt"
    :back-url="route('aportes.detalle', ['id' => $selected->id])"
    title="Comprobante"
    :pdf-url="$isNative ? null : route('aportes.pdf', ['id' => $selected->id])"
>
    <x-aportes.receipt-paper :selected="$selected"/>
    @if ($isNative)
        <button class="button navy print-button" wire:click="download" wire:loading.attr="disabled" wire:target="download">
        <x-icon name="share"/>
        <span wire:loading.remove wire:target="download">Guardar comprobante PDF</span>
        <span wire:loading wire:target="download">Preparando PDF…</span>
        </button>
    @else
        <a class="button navy print-button" href="{{ route('aportes.pdf', ['id' => $selected->id]) }}" download>
            <x-icon name="share"/>
            Guardar comprobante PDF
        </a>
    @endif
    @error('download')
        <span class="error" role="alert">{{ $message }}</span>
    @enderror
</x-aportes.shell>
