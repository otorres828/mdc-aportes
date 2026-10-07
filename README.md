# MDC Aportes

Laravel 12 + Livewire 3 + NativePHP Mobile 3. Aplicación de aportes sin base de datos propia: los datos temporales usan Cache de Laravel con driver de archivos.

## Ejecutar

Desde C:\laragon\www\mdc-aportes:

```powershell
powershell -ExecutionPolicy Bypass -File .\start.ps1
```

URL: http://127.0.0.1:8017. No requiere migraciones ni SQLite para ejecutar el flujo. Usa PHP 8.3 para NativePHP.

## Almacenamiento temporal

CACHE_STORE=file, SESSION_DRIVER=file, SESSION_ENCRYPT=true, QUEUE_CONNECTION=sync, QUEUE_FAILED_DRIVER=null. Los aportes y los borradores de envío se cifran con Crypt antes de guardarlos en caché. Mantén APP_KEY para poder recuperarlos.

CONTRIBUTIONS_CACHE_DAYS=30 controla la duración. cache:clear, la expiración, borrar los datos de la aplicación o cambiar su clave pueden eliminar el historial temporal. La API central será la fuente permanente de los aportes. El índice y los detalles están aislados por la cookie cifrada del dispositivo. El historial solo se consulta al abrirlo; su acceso en el home continúa oculto.

Los tres registros anteriores de SQLite se trasladaron a la caché antes de eliminar la carpeta database. No se usan Eloquent ni migraciones para aportes. Las sesiones y la cola tampoco usan una base de datos.

## Envío a la API

Por defecto CONTRIBUTIONS_DEMO=true: guarda un ejemplo local, sin enviar información ni afirmar que la iglesia lo recibió.

Para conectar un servidor:

```dotenv
CONTRIBUTIONS_DEMO=false
CONTRIBUTIONS_API_URL=https://tu-servidor.example/api/contributions
CONTRIBUTIONS_CACHE_DAYS=30
```

Ejecuta php artisan config:clear y reinicia Jump. Usa una URL HTTPS real. La ruta del ejemplo no es una API existente.

App\Services\ContributionApi hace un POST JSON con first_name, last_name, identity, amount_cents (entero), currency (VES), type, note, method, reference, bank, paid_at (YYYY-MM-DD) y client_id (UUID). Envía la misma UUID en Idempotency-Key en los reintentos. El servidor debe implementar idempotencia y detección de referencias duplicadas entre dispositivos.

Contrato provisional de respuesta 2xx:

```json
{"data":{"id":"server-uuid","number":"MC-2026-001284"}}
```

Con una respuesta válida muestra Gracias y guarda una copia temporal con el número del servidor, en verificación. Una respuesta inválida, 409, error de red o fallo del servidor conserva el borrador y muestra un error; no anuncia éxito. La persona puede reintentar. El borrador del intento fallido se recupera al volver a abrir el formulario mientras exista en caché.

Si hay un token de usuario en session('api_token'), el cliente lo envía como Bearer. No hay flujo de autenticación implementado todavía; se ajustará al contrato de la API real. No incluyas secretos de administración en el APK. Por ahora no existe una API configurada, tampoco descarga o conciliación remota de estados.

## Datos bancarios

Configura CHURCH_BANK, CHURCH_BANK_CODE, CHURCH_ACCOUNT_HOLDER, CHURCH_RIF, CHURCH_PHONE, CHURCH_ACCOUNT, CHURCH_ACCOUNT_TYPE con los datos reales de la iglesia. La instalación trae datos de demostración. La app registra información de una operación hecha en el banco; no transfiere dinero.

## NativePHP

```powershell
php artisan native:jump
php artisan native:run android --build=debug
```

En web/Jump la caché está en la computadora; en el APK está dentro del teléfono. NativePHP 3 genera por su cuenta una SQLite interna y ejecuta su preparación nativa; es comportamiento del runtime, no almacenamiento de aportes de esta aplicación. No se modificó el proveedor de NativePHP para eliminar esa infraestructura. La carpeta database del proyecto se eliminó.

Android requiere SDK, Java y espacio libre para compilar. No hay APK verificado por falta de espacio en C:. iOS requiere macOS.

## Verificación

```powershell
powershell -ExecutionPolicy Bypass -File .\tools\artisan.ps1 test
```

Pruebas de caché cifrada, flujo sin conexiones SQL, validaciones, aislamiento, expiración, comprobantes, duplicados, cliente API con HTTP simulado, respuestas inválidas, reintentos y borradores.

PWA: solo almacena recursos visuales y una página sin conexión; los pagos no se envían offline automáticamente.
## Estructura de pantallas

Nueve rutas con Route::livewire, cada una con clase y vista independientes. Código en app/Livewire y resources/views/livewire; componentes compartidos en resources/views/components/aportes. No se conserva el componente monolítico Contributions. El borrador entre rutas usa Cache cifrada.

Livewire 3 requiere una macro de compatibilidad para Route::livewire, registrada en AppServiceProvider. El estándar de .skill/estandar-programacion-crm se adapta al panel público sin inventar permisos administrativos.

[Documentación por clase y servicio](docs/README.md).
