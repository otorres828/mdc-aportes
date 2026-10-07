<?php

namespace App\Livewire;

use App\Support\ContributionOptions;
use App\Services\ContributionPdf;
use Illuminate\Support\Facades\File;
use Native\Mobile\Facades\Share;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;

class ComprobanteAporte extends AportePage
{
    #[Locked]
    public string $id;

    public function mount(string $id): void
    {
        $this->id = $id;
    }

    public function render(): View
    {
        return view('livewire.comprobante-aporte', [
            'selected' => $this->record($this->id),
            'types' => ContributionOptions::TYPES,
            'isNative' => (bool) config('nativephp-internal.running'),
        ]);
    }

    public function download(): ?StreamedResponse
    {
        // Comprobar pertenencia también al descargar, no solo al abrir la ruta.
        $record = $this->record($this->id);
        $content = app(ContributionPdf::class)->generate($record);
        $filename = 'aporte-'.$record->id.'.pdf';
        if (config('nativephp-internal.running') && function_exists('nativephp_call')) {
            $directory = storage_path('app/private/receipts');
            File::ensureDirectoryExists($directory);
            foreach (File::files($directory) as $file) {
                if ($file->getMTime() < now()->subDay()->timestamp) {
                    File::delete($file->getPathname());
                }
            }
            $path = $directory.DIRECTORY_SEPARATOR.$filename;
            File::put($path, $content);
            Share::file('Comprobante de aporte', 'Mansion de Cristo', $path);

            return null;
        }

        return response()->streamDownload(function () use ($content): void {
            echo $content;
        }, $filename, [
            'Content-Type' => 'application/pdf',
            'Cache-Control' => 'private, no-store',
        ]);
    }
}
