<?php

namespace App\Http\Middleware;

use App\Models\PageVisit;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackPageVisit
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $request->isMethod('GET')) {
            return $response;
        }

        if ($request->expectsJson() || $request->ajax()) {
            return $response;
        }

        if ($request->is('admin') || $request->is('admin/*')) {
            return $response;
        }

        $ipAddress = $request->ip();
        [$countryCode, $countryName] = $this->resolveCountry($request);
        $routeParameters = $this->normalizeRouteParameters($request);
        $packageSlug = $this->extractPackageSlug($routeParameters);

        PageVisit::query()->create([
            'route_name' => $request->route()?->getName(),
            'path' => '/'.ltrim($request->path(), '/'),
            'route_parameters' => $routeParameters !== [] ? $routeParameters : null,
            'package_slug' => $packageSlug,
            'country_code' => $countryCode,
            'country_name' => $countryName,
            'ip_address' => $ipAddress,
            'visited_at' => now(),
        ]);

        return $response;
    }

    /**
     * Resolve country information from common proxy/CDN headers.
     *
     * @return array{0: string|null, 1: string|null}
     */
    private function resolveCountry(Request $request): array
    {
        $code = strtoupper((string) $request->header('CF-IPCountry', ''));

        if ($code === '' || $code === 'XX' || $code === 'T1') {
            $code = strtoupper((string) $request->header('X-Country-Code', ''));
        }

        if ($code === '') {
            return [null, null];
        }

        if (! function_exists('locale_get_display_region')) {
            return [$code, null];
        }

        $countryName = locale_get_display_region('-'.$code, 'en');

        if (! is_string($countryName) || $countryName === '') {
            return [$code, null];
        }

        return [$code, $countryName];
    }

    /**
     * Normalize route parameters into scalar values only.
     *
     * @return array<string, scalar|null>
     */
    private function normalizeRouteParameters(Request $request): array
    {
        $parameters = $request->route()?->parameters() ?? [];
        $normalized = [];

        foreach ($parameters as $key => $value) {
            if (is_scalar($value) || $value === null) {
                $normalized[(string) $key] = $value;
            }
        }

        return $normalized;
    }

    /**
     * Extract package slug from route params when available.
     */
    private function extractPackageSlug(array $routeParameters): ?string
    {
        $slug = $routeParameters['slug'] ?? $routeParameters['package'] ?? null;

        if (! is_string($slug) || trim($slug) === '') {
            return null;
        }

        return trim($slug);
    }
}
