# Gracias

Ruta: `aportes.gracias` (`/aportes/gracias/{id}`). Clase: `app/Livewire/Gracias.php`. Vista: `resources/views/livewire/gracias.blade.php`.

## Entradas y presentación

UUID y comprobante de este dispositivo. Muestra monto, fecha, número y estado; distingue ejemplo local de recepción remota.

## Acciones y resultados

another: verifica pertenencia nuevamente, inicia un borrador con nueva UUID y conserva identificación; navega al aporte.

## Acceso y validación

ContributionAccess valida la entrada a la ruta. Es una aplicación pública: no se copian guards ni permisos administrativos del CRM. El propietario proviene de la cookie cifrada `mdc_device`, con continuidad en sesión; no puede enviarse como una propiedad editable de Livewire. El UUID del borrador o del registro se protege con Locked. Las escrituras comprueban de nuevo el contexto del borrador y los comprobantes consultan únicamente la Cache del propietario. Las rutas con ID exigen UUID. Un registro inexistente o expirado devuelve 404.

## Verificación

`php artisan test`: validaciones, rutas distintas, pasos requeridos, restauración al volver, Cache cifrada, pertenencia, duplicados, idempotencia y API simulada. La navegación con `wire:navigate` se revisa además en navegador.

[Volver al índice](../README.md).
