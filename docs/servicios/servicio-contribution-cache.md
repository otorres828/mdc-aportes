# ContributionCache

Clase: `app/Services/ContributionCache.php`.

Almacenamiento temporal cifrado por propietario. records/all/find consultan Cache; save usa bloqueo por propietario y añade el comprobante. draft/latestDraft/forgetDraft conservan intentos fallidos para recuperación. Los registros anteriores siguen siendo compatibles. No es Eloquent ni realiza consultas SQL.

[Reglas del flujo](../logica-flujo-aportes.md). [Índice](../README.md).
