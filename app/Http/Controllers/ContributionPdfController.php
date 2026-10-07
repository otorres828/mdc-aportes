<?php

namespace App\Http\Controllers;

use App\Services\ContributionCache;
use App\Services\ContributionFlow;
use App\Services\ContributionPdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ContributionPdfController extends Controller
{
    public function __invoke(string $id): StreamedResponse
    {
        $record = app(ContributionCache::class)->find(app(ContributionFlow::class)->owner(), $id);
        abort_unless($record, 404);
        $content = app(ContributionPdf::class)->generate($record);

        return response()->streamDownload(function () use ($content): void {
            echo $content;
        }, 'aporte-'.$id.'.pdf', [
            'Content-Type' => 'application/pdf',
            'Cache-Control' => 'private, no-store',
        ]);
    }
}
