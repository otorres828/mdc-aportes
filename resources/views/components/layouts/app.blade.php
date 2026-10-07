<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
        <meta name="theme-color" content="#071e30">
        <meta name="description" content="Tu generosidad transforma vidas. Registra tus aportes a Mansion de Cristo.">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/icon-192.png') }}">
        <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
        <link rel="stylesheet" href="{{ asset('vendor/flatpickr/flatpickr.min.css') }}">
        <title>
            @yield('title', 'Aportes') · Mansion de Cristo
        </title>
        @livewireStyles
    </head>
    <body>

        <div class="desktop-label">

            <span class="mini-cross">
                ✝
            </span>
            Mansion de Cristo
            <span>
                IGLESIA & COMUNIDAD
            </span>

        </div>

        {{ $slot }}

        <div class="desktop-footer">

            Un corazón generoso. Una comunidad que crece.

        </div>

        <script src="{{ asset('vendor/flatpickr/flatpickr.min.js') }}" data-navigate-once></script>
        <script src="{{ asset('vendor/flatpickr/es.js') }}" data-navigate-once></script>
        <script src="{{ asset('js/app.js') }}?v={{ filemtime(public_path('js/app.js')) }}" data-navigate-once>
        </script>
        @livewireScripts
    </body>
</html>
