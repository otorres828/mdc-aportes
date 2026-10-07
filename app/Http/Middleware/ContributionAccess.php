<?php

namespace App\Http\Middleware;

use App\Services\ContributionCache;
use App\Services\ContributionFlow;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContributionAccess
{
    public function handle(Request $request, Closure $next, string $stage = '0'): Response
    {
        $flow = app(ContributionFlow::class);
        if ($stage === 'record') {
            abort_unless(app(ContributionCache::class)->find($flow->owner(), (string) $request->route('id')), 404);
        } elseif ((int) $stage > 0) {
            $current = $flow->current();
            if ($current['stage'] < (int) $stage) {
                $route = match ($current['stage']) {
                    1 => 'aportes.aporte',
                    2 => 'aportes.pago',
                    default => 'aportes.identificacion',
                };

                return redirect()->route($route);
            }
        } else {
            $flow->owner();
        }

        return $next($request);
    }
}
