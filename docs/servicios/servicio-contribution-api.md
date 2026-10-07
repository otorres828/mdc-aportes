# ContributionApi

Clase: `app/Services/ContributionApi.php`.

POST JSON al endpoint configurado, con Idempotency-Key estable y token de sesión opcional. Tiempo de conexión 3 segundos y timeout 10 segundos. Valida respuesta 2xx con data.id y data.number; rechaza 409, fallos de conexión y cuerpos inválidos. Sin endpoint real configurado; el contrato continúa siendo provisional. No hay descarga remota de historial ni conciliación de estados.

[Reglas del flujo](../logica-flujo-aportes.md). [Índice](../README.md).
