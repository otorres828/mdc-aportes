# Interacción móvil y arranque

Los textos de las vistas no permiten selección ni menú contextual. Los inputs, textarea y elementos editables conservan selección, copia y pegado. Los botones de copiar mantienen el acceso al portapapeles. Esto es una preferencia de interacción, no una protección de los datos.

El APK Android muestra un arranque centrado con fondo crema claro, cruz sobre azul marino, spinner dorado y «Cargando aplicación…». Conserva el estado y la salida de la pantalla de arranque de NativePHP, sin tiempos artificiales.

La plantilla está en `resources/native/android-startup.kt`. Ejecutar `php artisan app:prepare-android` después de instalar o regenerar el proyecto Android y antes de compilar el APK. El comando modifica únicamente el bloque de arranque en el proyecto generado, sin modificar vendor, y falla si cambia su estructura. Ejecutarlo nuevamente es seguro.

Recompilar e instalar el APK para ver el cambio nativo. Jump usa su propio contenedor y esta personalización no cambia el arranque del cliente Jump. La web recibe las reglas de selección de texto. La compilación y la visualización en un teléfono deben verificarse con el SDK Android disponible.
