<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogRequestInLocal
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! app()->environment('local')) {
            return $next($request);
        }

        $startedAt = microtime(true);
        $response = $next($request);

        $route = $request->route();

        Log::info('Incoming request', [
            'method' => $request->getMethod(),
            'path' => $request->path(),
            'url' => $request->fullUrl(),
            'route_name' => $route?->getName(),
            'route_action' => $route?->getActionName(),
            'status' => $response->getStatusCode(),
            'ip' => $request->ip(),
            'duration_ms' => (int) round((microtime(true) - $startedAt) * 1000),
        ]);

        return $response;
    }
}
