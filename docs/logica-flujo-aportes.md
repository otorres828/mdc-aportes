# Flujo y acceso a aportes

Bienvenida → Identificación → Aporte → Pago → Confirmación → Gracias. Historial, detalle y comprobante tienen sus propias rutas. El acceso a Mis aportes permanece oculto en el home.

Route::livewire se registra como macro de compatibilidad en AppServiceProvider, únicamente si el router no la ofrece. Livewire 3.8 usa internamente rutas GET a componentes de página. No se ha actualizado a Livewire 4 ni se ha modificado vendor. Las rutas se agrupan con prefix(aportes) y name(aportes.), en el orden del flujo. Los enlaces y redirecciones usan wire:navigate.

ContributionAccess valida la entrada. El progreso validado vive en Cache cifrada, por dispositivo. Se requiere etapa 1 para aporte, 2 para pago y 3 para confirmar. Una URL directa a una etapa no alcanzada redirige a la etapa pendiente. Guardar de nuevo un paso anterior reduce la etapa para impedir confirmar datos no revisados. Cada acción de escritura comprueba su UUID Locked contra el borrador del propietario. El middleware de registros y cada render consultan Cache por propietario; UUID Locked no sustituye esa comprobación.

El origen del propietario es la cookie HTTP-only cifrada por Laravel. La sesión cifrada conserva continuidad en la primera navegación. Los registros existentes no cambian de clave y siguen disponibles para su dispositivo. Una UUID de otro dispositivo o un comprobante expirado devuelve 404.

No hay SQL, modelo Eloquent de aportes ni carpeta database. Los datos temporales usan Cache file; sesión file cifrada y cola sync. El runtime NativePHP conserva su infraestructura interna.

mdcShell registra sus listeners al montar y los elimina en destroy. app.js y Flatpickr 4.6.13 se cargan una sola vez; los assets son locales para NativePHP. El componente x-form.date-input usa español y dd/mm/aaaa, manteniendo Y-m-d en Livewire. Su instancia se destruye al salir. La validación PHP sigue siendo obligatoria; los límites del calendario son presentación. Los importes, tipo y nota se seleccionan localmente en Alpine y se envían al continuar.

Al confirmar se mantiene el estado de carga dentro del botón. Fallos API conservan referencia y UUID para reintento; únicamente una respuesta válida permite anunciar recepción remota. Modo demo conserva un ejemplo local claramente identificado. La API central debe implementar conciliación, autenticación e idempotencia global.

[Índice](README.md).
