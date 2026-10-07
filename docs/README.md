# Módulo público de aportes

Cada pantalla tiene ruta, clase y vista propias. Clases en `app/Livewire`, vistas en `resources/views/livewire` y componentes de presentación en `resources/views/components/aportes`.

- [Bienvenida](modulos/bienvenida.md): `/`.
- [SaveIdentificacion](modulos/save-identificacion.md): `/aportes/identificacion`.
- [SaveAporte](modulos/save-aporte.md): `/aportes/aporte`.
- [RealizarPago](modulos/realizar-pago.md): `/aportes/pago`.
- [SaveConfirmacion](modulos/save-confirmacion.md): `/aportes/confirmar`.
- [Gracias](modulos/gracias.md): `/aportes/gracias/{id}`.
- [ListAporte](modulos/list-aporte.md): `/aportes/historial`.
- [DetailAporte](modulos/detail-aporte.md): `/aportes/{id}/detalle`.
- [ComprobanteAporte](modulos/comprobante-aporte.md): `/aportes/{id}/comprobante`.

- [AportePage](modulos/aporte-page.md): base compartida mínima, sin pantalla.

## Servicios y reglas

- [ContributionFlow](servicios/servicio-contribution-flow.md).
- [ContributionCache](servicios/servicio-contribution-cache.md).
- [ContributionSubmission](servicios/servicio-contribution-submission.md).
- [ContributionApi](servicios/servicio-contribution-api.md).
- [Acceso, navegación y compatibilidad](logica-flujo-aportes.md).
- [Interacción móvil y arranque Android](logica-interaccion-mobile.md).
