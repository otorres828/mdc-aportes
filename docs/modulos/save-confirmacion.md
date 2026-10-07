# SaveConfirmacion

Ruta: `aportes.confirmar` (`/aportes/confirmar`). Clase: `app/Livewire/SaveConfirmacion.php`. Vista: `resources/views/livewire/save-confirmacion.blade.php`.

## Entradas y presentación

Referencia, banco y fecha; requiere etapa 3. Monto, identidad y tipo vienen del borrador validado, no del formulario de confirmación.

## Acciones y resultados

save: referencia de 6–30 dígitos, banco del catálogo y fecha entre hace cinco años y hoy. ContributionSubmission registra o envía el aporte. Solo redirige a Gracias tras éxito; conserva datos y UUID si falla la API.

## Acceso y validación

ContributionAccess valida la entrada a la ruta. Es una aplicación pública: no se copian guards ni permisos administrativos del CRM. El propietario proviene de la cookie cifrada `mdc_device`, con continuidad en sesión; no puede enviarse como una propiedad editable de Livewire. El UUID del borrador o del registro se protege con Locked. Las escrituras comprueban de nuevo el contexto del borrador y los comprobantes consultan únicamente la Cache del propietario. Las rutas con ID exigen UUID. Un registro inexistente o expirado devuelve 404.

## Verificación

`php artisan test`: validaciones, rutas distintas, pasos requeridos, restauración al volver, Cache cifrada, pertenencia, duplicados, idempotencia y API simulada. La navegación con `wire:navigate` se revisa además en navegador.

[Volver al índice](../README.md).
