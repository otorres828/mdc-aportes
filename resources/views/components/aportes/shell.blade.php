@props([
    'screen',
    'step' => 0,
    'backUrl' => null,
    'title' => null,
    'pdfUrl' => null,
])
<main class="app-shell screen-{{ $screen }} {{ $screen === 'welcome' ? 'welcome-shell' : '' }} {{ $screen === 'receipt' ? 'receipt-shell' : '' }}" x-data="mdcShell">

    <div class="toast" role="status" x-show="toast" x-cloak x-text="toast" x-transition>

    </div>

    <div class="alert-backdrop" x-show="alertVisible" x-cloak x-transition.opacity>

        <section class="custom-alert" role="alertdialog" aria-modal="true" aria-labelledby="custom-alert-title" aria-describedby="custom-alert-message">

            <div class="custom-alert-icon">
                <x-icon name="info"/>
            </div>
            <div class="custom-alert-content">
                <h2 id="custom-alert-title">Revisa tu edad</h2>
                <p id="custom-alert-message" x-text="alertMessage"></p>
            </div>
            <button type="button" class="button navy custom-alert-button" @click="closeAlert()">
                Entendido
            </button>

        </section>

    </div>

    <div class="offline-banner" x-show="offline" x-cloak>

        Sin conexión. Conserva esta pantalla y vuelve a intentarlo al conectarte.

    </div>

    @if ($screen === 'welcome')
        {{ $slot }}
    @else
        <header class="app-header">
            @if ($backUrl)
                <a class="icon-button" href="{{ $backUrl }}" wire:navigate aria-label="Volver">
                    <x-icon name="back"/>
                </a>
            @else

                <div class="header-spacer">

                </div>

            @endif
            @if ($title)
                <span class="header-title">
                    {{ $title }}
                </span>
            @else
                <x-brand/>
            @endif
            @if ($screen === 'receipt')
                @if ($pdfUrl)
                    <a class="icon-button" href="{{ $pdfUrl }}" download aria-label="Compartir o guardar PDF">
                        <x-icon name="share"/>
                    </a>
                @else
                <button class="icon-button" wire:click="download" wire:loading.attr="disabled" wire:target="download" aria-label="Compartir o guardar PDF">
                    <x-icon name="share"/>
                </button>
                @endif
            @elseif (! in_array($screen, ['detail', 'thanks']))
                <a class="icon-button subtle" href="{{ route('aportes.historial') }}" wire:navigate aria-label="Mis aportes">
                    <x-icon name="history"/>
                </a>
            @else

                <div class="header-spacer">

                </div>

            @endif
        </header>
        @if ($step)

            <div class="progress-wrap" aria-label="Paso {{ $step }} de 4">

                <div class="progress-label">

                    Paso
                    {{ $step }}
                    de 4
                    <span>
                        {{ [1 => 'Tus datos', 2 => 'Tu aporte', 3 => 'El pago', 4 => 'Confirmación'][$step] }}
                    </span>

                </div>

                <div class="progress">

                    <span style="width: {{ $step * 25 }}%">
                    </span>
                    @foreach ([1, 2, 3, 4] as $point)
                        <i class="{{ $step >= $point ? 'active' : '' }}" style="left:calc({{ $point * 25 }}% - 5px)">
                        </i>
                    @endforeach

                </div>

            </div>

        @endif

        <div class="screen-content">

            {{ $slot }}

        </div>

        @if (! in_array($screen, ['receipt', 'thanks']))
            <footer class="app-footer">
                <span>
                    Mansion de Cristo
                </span>
                <small>
                    Juntos, sembramos esperanza.
                </small>
            </footer>
        @endif
    @endif
</main>
