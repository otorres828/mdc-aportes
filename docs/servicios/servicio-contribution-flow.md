# ContributionFlow

Clase: `app/Services/ContributionFlow.php`.

Contexto del dispositivo y avance del borrador. owner obtiene la UUID cifrada y asegura continuidad entre rutas. current recupera la Cache cifrada o un intento fallido antiguo; defaults crea una nueva UUID. update verifica UUID y etapa mínima antes de guardar. Retroceder y guardar invalida las etapas posteriores. begin crea un nuevo borrador cuando el anterior ya fue registrado. restart conserva únicamente los datos de identificación enviados por la acción autorizada. Expira según CONTRIBUTIONS_CACHE_DAYS.

[Reglas del flujo](../logica-flujo-aportes.md). [Índice](../README.md).
