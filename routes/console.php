<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('app:prepare-android', function () {
    $path = base_path('nativephp/android/app/src/main/java/com/nativephp/mobile/ui/MainActivity.kt');
    if (! is_file($path)) {
        $this->error('Instala primero el proyecto Android con native:install.');

        return 1;
    }
    $source = file_get_contents($path);
    $template = file_get_contents(resource_path('native/android-startup.kt'));
    $pattern = '/    @Composable\R    private fun SplashScreen\(\) \{.*?(?=    \/\*\*\R     \* Bottom navigation composable)/s';
    $updated = preg_replace_callback($pattern, function () use ($template) {
        return $template."\n";
    }, $source, 1, $count);
    if ($count !== 1 || $updated === null) {
        $this->error('La estructura de NativePHP cambió; revisa el bloque SplashScreen antes de modificarlo.');

        return 1;
    }
    file_put_contents($path, $updated);
    $this->info('Arranque Android personalizado. Ya puedes compilar el APK.');

    return 0;
})->purpose('Aplicar el arranque de Mansion de Cristo al proyecto Android generado');
