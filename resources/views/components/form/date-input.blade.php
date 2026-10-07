@props([
    'id',
    'min' => null,
    'max' => null,
])

@php
    $model = $attributes->wire('model')->value();
@endphp

<div
    class="input-wrap"
    x-data="mdcDateInput"
    data-model="{{ $model }}"
    data-min="{{ $min }}"
    data-max="{{ $max }}"
    wire:ignore
>

    <x-icon name="calendar"/>

    <input
        type="text"
        id="{{ $id }}"
        x-ref="input"
        placeholder="dd/mm/aaaa"
        aria-label="Fecha del pago"
        autocomplete="off"
    >

</div>
