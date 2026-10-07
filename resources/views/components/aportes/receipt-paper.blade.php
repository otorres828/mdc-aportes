@props(['selected'])

<article class="receipt-paper">
    <x-brand/>
    <h1>
        Comprobante de aporte
    </h1>
    <p class="receipt-subtitle">
        Gracias por tu generosidad
    </p>
    @if($selected->is_demo)

        <div class="receipt-demo">

            DEMOSTRACIÓN · SIN VALIDEZ BANCARIA

        </div>

    @endif

    <div class="receipt-total">

        <small>
            {{ $selected->type }}
        </small>
        <strong>
            <span>
                Bs.
            </span>
            {{ $selected->formatted_amount }}
        </strong>
        <x-status :status="$selected->status"/>

    </div>

    <dl class="receipt-details">
        @foreach ([
            'N.º de aporte' => $selected->number,
            'Nombre' => $selected->first_name.' '.$selected->last_name,
            'Cédula' => $selected->identity,
            'Fecha del pago' => $selected->paid_at->translatedFormat('d/m/Y'),
            'Registrado' => $selected->created_at->format('d/m/Y · H:i'),
            'Método de pago' => $selected->method,
            'Banco emisor' => $selected->bank,
            'N.º de referencia' => $selected->reference,
        ] as $label => $value)

            <div>

                <dt>
                    {{ $label }}
                </dt>
                <dd>
                    {{ $value }}
                </dd>

            </div>

        @endforeach
        @if($selected->note)

            <div>

                <dt>
                    Nota
                </dt>
                <dd>
                    {{ $selected->note }}
                </dd>

            </div>

        @endif
    </dl>
    <p class="receipt-disclaimer">
        Este documento acredita el registro de la información. La recepción del dinero está sujeta a conciliación bancaria.
    </p>

    <div class="verse">

        “Dios ama al dador alegre.”
        <small>
            2 Corintios 9:7
        </small>

    </div>

</article>
