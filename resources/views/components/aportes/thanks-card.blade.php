@props(['selected', 'types'])

<div class="thanks-card">

    <span class="muted">
        Monto de tu aporte
    </span>
    <strong class="thanks-amount">
        <small>
            Bs.
        </small>
        {{ $selected->formatted_amount }}
    </strong>

    <div class="detail-line">

        <span class="detail-icon warm">
            <x-icon :name="$types[$selected->type][0]"/>
        </span>

        <div>

            <small>
                Tipo de aporte
            </small>
            <strong>
                {{ $selected->type }}
            </strong>

        </div>

    </div>

    <div class="detail-line">

        <span class="detail-icon">
            <x-icon name="calendar"/>
        </span>

        <div>

            <small>
                Fecha del pago
            </small>
            <strong>
                {{ $selected->paid_at->translatedFormat('d \d\e F \d\e Y') }}
            </strong>

        </div>

    </div>

    <div class="detail-line">

        <span class="detail-icon">
            <x-icon name="receipt"/>
        </span>

        <div>

            <small>
                Número de aporte
            </small>
            <strong>
                {{ $selected->number }}
            </strong>

        </div>

    </div>

    <div class="thanks-status">

        <x-status :status="$selected->status"/>

    </div>

    @if($selected->is_demo)
        <span class="demo-record">
            Ejemplo local · No enviado
        </span>
    @endif

</div>
