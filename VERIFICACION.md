# Verificación actual

Laravel + Livewire + NativePHP. Nueve rutas, clases y vistas independientes. Aportes y borradores en Cache cifrada; sin SQL ni carpeta database.

- 17 pruebas, 198 aserciones pasan tras separar las pantallas. Las pruebas impiden DB::connection.
- Validaciones, navegación por rutas, restauración del borrador, aislamiento entre dispositivos, cifrado, duplicados e idempotencia.
- API probada mediante HTTP simulado: éxito con número del servidor, errores, cuerpo inválido, conflicto y recuperación del intento con la misma UUID.
- Ruta de comprobante inexistente o expirada devuelve 404; historial vacío permanece operativo.
- Flujo de demostración verificado en navegador: avanzar y volver conserva monto, tipo y nota; comprobante y detalle tienen URL propia.
- Calendario español probado al abrir, salir y volver con wire:navigate, sin errores de consola. Recursos y límites de fecha locales.
- Sin endpoint real configurado. El modo de demostración no envía datos.
- NativePHP conserva su infraestructura nativa interna. No se ha compilado ni probado un APK.

Detalles de estructura y estándar: [docs/README.md](docs/README.md).
