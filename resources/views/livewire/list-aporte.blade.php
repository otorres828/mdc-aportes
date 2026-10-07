{{--
    MIS APORTES
    Pantalla independiente del flujo de aportes.
    Componentes utilizados:
    - <x-aportes.shell />: Marco móvil, navegación y progreso.
    - <x-icon />: Iconografía de acciones y datos.
    - <x-status />: Estado de verificación.
--}}

@section('title', 'Mis aportes')

<x-aportes.shell
    screen="history"
    :back-url="route('home')"
>

    <div class="section-heading compact">

        <span class="eyebrow">
            TU HUELLA DE GENEROSIDAD
        </span>
        <h1>
            Mis aportes
        </h1>
        <p>
            Un pequeño gesto. Un impacto que permanece.
        </p>

    </div>

    <div class="history-filters" role="group" aria-label="Filtrar por tipo">

        @foreach (['Todos', ...array_keys($types)] as $name)
            <button wire:click="$set('filter', '{{ $name }}')" class="{{ $filter === $name ? 'active' : '' }}" aria-pressed="{{ $filter === $name ? 'true' : 'false' }}">
                {{ $name }}
            </button>
        @endforeach

    </div>

    @forelse ($groups as $month => $items)
        <section class="month-group">
            <h2>
                {{ ucfirst($month) }}
                <span>
                    {{ $items->count() }}
                    {{ $items->count() === 1 ? 'aporte' : 'aportes' }}
                </span>
            </h2>

            <div class="history-list">

                @foreach ($items as $item)
                    <a class="history-row" href="{{ route('aportes.detalle', ['id' => $item->id]) }}" wire:navigate wire:key="aporte-{{ $item->id }}">
                        <span class="history-icon">
                            <x-icon :name="$types[$item->type][0]"/>
                        </span>
                        <span class="history-name">
                            <strong>
                                {{ $item->type }}
                            </strong>
                            <small>
                                {{ $item->paid_at->translatedFormat('d M Y') }}
                                {{ $item->is_demo ? ' · Demo' : '' }}
                            </small>
                        </span>
                        <span class="history-value">
                            <strong>
                                <small>
                                    Bs.
                                </small>
                                {{ $item->formatted_amount }}
                            </strong>
                            <x-status :status="$item->status"/>
                        </span>
                        <x-icon name="chevron"/>
                    </a>
                @endforeach

            </div>

        </section>
    @empty

        <div class="empty-state">

            <span class="empty-icon">
                <x-icon name="heart"/>
            </span>
            <h2>
                {{ $filter === 'Todos' ? 'Tu historia empieza aquí' : 'Todavía no hay aportes de este tipo' }}
            </h2>
            <p>
                {{ $filter === 'Todos' ? 'Cuando registres un aporte, podrás consultar aquí sus detalles y comprobante.' : 'Selecciona otro tipo para consultar tus aportes.' }}
            </p>
            <a class="button navy" href="{{ route('aportes.identificacion') }}" wire:navigate>
                Realizar un aporte
                <x-icon name="arrow"/>
            </a>

        </div>

    @endforelse
    @if ($hasMore)
        <button class="button cream" wire:click="loadMore">
            Ver más aportes
        </button>
    @endif
    @if (config('contributions.demo'))
        <button class="text-link example-toggle" wire:click="$toggle('showExamples')">
            {{ $showExamples ? 'Ocultar ejemplos de estados' : 'Ver ejemplos de estados' }}
        </button>
        @if ($showExamples)
            <section class="example-list">
                <small>
                    EJEMPLOS · NO SON TUS APORTES
                </small>
                @foreach (['pending' => ['Diezmo','1.500,00'], 'verified' => ['Ofrenda','500,00'], 'rejected' => ['Pacto','1.000,00']] as $state => $example)

                    <div class="example-row">

                        <span>
                            {{ $example[0] }}
                            <small>
                                Bs.
                                {{ $example[1] }}
                            </small>
                        </span>
                        <x-status :status="$state"/>

                    </div>

                @endforeach
            </section>
        @endif
    @endif

    <div class="device-note">

        <x-icon name="info"/>
        Este historial es temporal y corresponde a la caché de este dispositivo. Puede expirar o borrarse.

    </div>

    @if ($groups->isNotEmpty())
        <a class="button gold" href="{{ route('aportes.identificacion') }}" wire:navigate>
            <x-icon name="plus"/>
            Realizar un aporte
        </a>
    @endif
</x-aportes.shell>
