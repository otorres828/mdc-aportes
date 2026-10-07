# SaveAporte

Ruta: `aportes.aporte` (`/aportes/aporte`). Clase: `app/Livewire/SaveAporte.php`. Vista: `resources/views/livewire/save-aporte.blade.php`.

## Entradas y presentación

Monto, tipo y nota opcional; requiere etapa 1. Las selecciones rápidas y la nota usan Alpine sobre propiedades diferidas.

## Acciones y resultados

save: valida monto positivo (hasta 8 dígitos y 2 decimales), tipo del catálogo y nota (500 caracteres); guarda etapa 2 y navega al pago.

## Acceso y validación

ContributionAccess valida la entrada a la ruta. Es una aplicación pública: no se copian guards ni permisos administrativos del CRM. El propietario proviene de la cookie cifrada `mdc_device`, con continuidad en sesión; no puede enviarse como una propiedad editable de Livewire. El UUID del borrador o del registro se protege con Locked. Las escrituras comprueban de nuevo el contexto del borrador y los comprobantes consultan únicamente la Cache del propietario. Las rutas con ID exigen UUID. Un registro inexistente o expirado devuelve 404.

## Verificación

`php artisan test`: validaciones, rutas distintas, pasos requeridos, restauración al volver, Cache cifrada, pertenencia, duplicados, idempotencia y API simulada. La navegación con `wire:navigate` se revisa además en navegador.

[Volver al índice](../README.md).
