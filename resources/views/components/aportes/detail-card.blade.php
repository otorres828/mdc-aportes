@props(['selected', 'types'])

<div class="detail-card">

    <div class="detail-top">

        <span class="history-icon">
            <x-icon :name="$types[$selected->type][0]"/>
        </span>

        <div>

            <small>
                {{ $selected->type }}
            </small>
            <strong>
                <span>
                    Bs.
                </span>
                {{ $selected->formatted_amount }}
            </strong>

        </div>

        <x-status :status="$selected->status"/>

    </div>

    <div class="detail-table">

        @foreach ([
            'Fecha del pago' => $selected->paid_at->translatedFormat('d \d\e F \d\e Y'),
            'Método de pago' => $selected->method,
            'Banco emisor' => $selected->bank,
            'Número de referencia' => $selected->reference,
            'Número de aporte' => $selected->number,
            'Registrado por' => $selected->first_name.' '.$selected->last_name,
            'Nota' => $selected->note ?: '—',
        ] as $label => $value)

            <div>

                <span>
                    {{ $label }}
                </span>
                <strong>
                    {{ $value }}
                </strong>

            </div>

        @endforeach

    </div>

    @if($selected->is_demo)

        <div class="demo-notice">

            Ejemplo local · No enviado

        </div>

    @endif

</div>
