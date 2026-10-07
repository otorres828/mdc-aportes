# ContributionSubmission

Clase: `app/Services/ContributionSubmission.php`.

Proceso de confirmación idempotente. 1: bloqueo por propietario y UUID. 2: devolver registro existente si se reintenta. 3: comprobar etapa 3 y descartar referencia duplicada por banco/fecha. 4: guardar el borrador. 5: construir payload desde datos validados. 6: guardar ejemplo local o enviar mediante ContributionApi. 7: guardar comprobante en Cache y quitar borrador fallido. Una excepción impide la redirección de éxito. El servidor debe aplicar idempotencia y unicidad entre dispositivos.

[Reglas del flujo](../logica-flujo-aportes.md). [Índice](../README.md).
