{{--
    BIENVENIDA
    Pantalla independiente del flujo de aportes.
    Componentes utilizados:
    - <x-aportes.shell />: Marco móvil, navegación y progreso.
    - <x-brand />: Identidad de la iglesia.
    - <x-icon />: Iconografía de acciones y datos.
--}}

@section('title', 'Bienvenida')

<x-aportes.shell screen="welcome">
    <section class="welcome" wire:key="welcome">
        <x-brand class="brand-light"/>
        @if (config('contributions.demo'))
            <span class="demo-pill">
                VISTA DE DEMOSTRACIÓN
            </span>
        @endif

        <div class="welcome-content">

            <div class="eyebrow light">

                UN ACTO DE FE. UNA HUELLA DE AMOR.

            </div>

            <h1>
                Tu generosidad
                <br>
                transforma
                <em>
                    vidas.
                </em>
            </h1>
            <p>
                Sé parte de lo que Dios está haciendo. Tu aporte nos ayuda a servir, compartir esperanza y crecer juntos.
            </p>

            <div class="welcome-benefits">

                <div>

                    <span>
                        <x-icon name="people"/>
                    </span>
                    <p>
                        Apoya a la iglesia
                        <br>
                        y sus ministerios
                    </p>

                </div>

                <div>

                    <span>
                        <x-icon name="heart"/>
                    </span>
                    <p>
                        Sirve a nuestra
                        <br>
                        comunidad
                    </p>

                </div>

                <div>

                    <span>
                        <x-icon name="book"/>
                    </span>
                    <p>
                        Comparte fe
                        <br>
                        y esperanza
                    </p>

                </div>

            </div>

            <a class="button gold" href="{{ route('aportes.identificacion') }}" wire:navigate>
                Realizar un aporte
                <x-icon name="arrow"/>
            </a>
{{-- Opción Mis aportes oculta temporalmente en el inicio. La pantalla se conserva. --}}

            <div class="welcome-verse">

                “Dios ama al dador alegre.”
                <span>
                    2 Corintios 9:7
                </span>

            </div>

        </div>

    </section>
</x-aportes.shell>
