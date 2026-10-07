# SaveIdentificacion

Ruta: `aportes.identificacion` (`/aportes/identificacion`). Clase: `app/Livewire/SaveIdentificacion.php`. Vista: `resources/views/livewire/save-identificacion.blade.php`.

## Entradas y presentación

Nombre, apellido, cédula V/E y fecha de nacimiento obligatoria. Restaura el borrador; al iniciar después de un aporte registrado crea un nuevo UUID. La fecha se conserva en Cache cifrada y se envía como `birth_date` a la API.

## Acciones y resultados

save: valida nombres (80 caracteres), cédula (5–10 dígitos) y fecha válida no futura. Menores de 18 años reciben una alerta y un error junto al campo; no se guarda etapa 1 ni se navega. Se permite continuar desde el día del cumpleaños número 18. La validación se ejecuta en PHP. La navegación no asigna foco a los títulos.

## Acceso y validación

ContributionAccess valida la entrada a la ruta. Es una aplicación pública: no se copian guards ni permisos administrativos del CRM. El propietario proviene de la cookie cifrada `mdc_device`, con continuidad en sesión; no puede enviarse como una propiedad editable de Livewire. El UUID del borrador o del registro se protege con Locked. Las escrituras comprueban de nuevo el contexto del borrador y los comprobantes consultan únicamente la Cache del propietario. Las rutas con ID exigen UUID. Un registro inexistente o expirado devuelve 404.

## Verificación

`php artisan test`: validaciones, rutas distintas, pasos requeridos, restauración al volver, Cache cifrada, pertenencia, duplicados, idempotencia y API simulada. La navegación con `wire:navigate` se revisa además en navegador.

[Volver al índice](../README.md).
