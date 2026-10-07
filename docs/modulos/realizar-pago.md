# RealizarPago

Ruta: `aportes.pago` (`/aportes/pago`). Clase: `app/Livewire/RealizarPago.php`. Vista: `resources/views/livewire/realizar-pago.blade.php`.

## Entradas y presentación

Monto y datos bancarios de Pago Móvil o transferencia; requiere etapa 2. Botones individuales para copiar.

## Acciones y resultados

save: valida el método, guarda etapa 3 y navega a confirmación. Copiar todo copia cinco líneas: `titular: {nombre}`, código del banco, teléfono (Pago Móvil) o cuenta (transferencia), RIF y monto. El monto usa dos decimales con punto, sin separadores de miles ni prefijo Bs.; por ejemplo, `3500.00`. No incluye encabezado del método ni etiquetas adicionales. Se conserva la copia individual y una confirmación breve.

## Acceso y validación

ContributionAccess valida la entrada a la ruta. Es una aplicación pública: no se copian guards ni permisos administrativos del CRM. El propietario proviene de la cookie cifrada `mdc_device`, con continuidad en sesión; no puede enviarse como una propiedad editable de Livewire. El UUID del borrador o del registro se protege con Locked. Las escrituras comprueban de nuevo el contexto del borrador y los comprobantes consultan únicamente la Cache del propietario. Las rutas con ID exigen UUID. Un registro inexistente o expirado devuelve 404.

## Verificación

`php artisan test`: validaciones, rutas distintas, pasos requeridos, restauración al volver, Cache cifrada, pertenencia, duplicados, idempotencia y API simulada. La navegación con `wire:navigate` se revisa además en navegador.

[Volver al índice](../README.md).
