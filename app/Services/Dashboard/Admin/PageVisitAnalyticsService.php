<?php

namespace App\Services\Dashboard\Admin;

use App\Models\PageVisit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PageVisitAnalyticsService
{
    /**
     * @return array<string, mixed>
     */
    public function build(Request $request): array
    {
        $filters = [
            'search' => trim((string) $request->query('search', '')),
            'country_code' => strtoupper(trim((string) $request->query('country_code', ''))),
            'from' => (string) $request->query('from', ''),
            'to' => (string) $request->query('to', ''),
            'only_packages' => $request->boolean('only_packages', false),
        ];

        [$from, $to] = $this->resolveRange($filters['from'], $filters['to']);

        $query = PageVisit::query()
            ->when($filters['search'] !== '', function ($builder) use ($filters): void {
                $builder->where(function ($nested) use ($filters): void {
                    $nested->where('route_name', 'like', '%'.$filters['search'].'%')
                        ->orWhere('path', 'like', '%'.$filters['search'].'%')
                        ->orWhere('package_slug', 'like', '%'.$filters['search'].'%');
                });
            })
            ->when($filters['country_code'] !== '', fn ($builder) => $builder->where('country_code', $filters['country_code']))
            ->when($filters['only_packages'], fn ($builder) => $builder->whereNotNull('package_slug')->where('package_slug', '!=', ''))
            ->whereBetween('visited_at', [$from, $to]);

        $visits = (clone $query)
            ->latest('visited_at')
            ->paginate(20)
            ->through(function (PageVisit $visit): array {
                return [
                    'id' => $visit->id,
                    'route_name' => $visit->route_name,
                    'path' => $visit->path,
                    'package_slug' => $visit->package_slug,
                    'route_parameters' => $visit->route_parameters,
                    'country_code' => $visit->country_code,
                    'country_name' => $visit->country_name,
                    'ip_address' => $visit->ip_address,
                    'visited_at' => $visit->visited_at?->format('Y-m-d H:i:s'),
                ];
            })
            ->withQueryString();

        $topPackages = (clone $query)
            ->whereNotNull('package_slug')
            ->where('package_slug', '!=', '')
            ->selectRaw('package_slug, COUNT(*) as visits_count')
            ->groupBy('package_slug')
            ->orderByDesc('visits_count')
            ->limit(10)
            ->get()
            ->map(fn ($row): array => [
                'slug' => (string) $row->package_slug,
                'visits_count' => (int) $row->visits_count,
            ])
            ->values()
            ->all();

        $topPages = (clone $query)
            ->selectRaw('COALESCE(route_name, path) as page_key, COUNT(*) as visits_count')
            ->groupBy('page_key')
            ->orderByDesc('visits_count')
            ->limit(10)
            ->get()
            ->map(fn ($row): array => [
                'page' => (string) $row->page_key,
                'visits_count' => (int) $row->visits_count,
            ])
            ->values()
            ->all();

        return [
            'filters' => [
                ...$filters,
                'from' => $from->toDateString(),
                'to' => $to->toDateString(),
            ],
            'stats' => [
                'total_visits' => (clone $query)->count(),
                'unique_pages' => (clone $query)->distinct('path')->count('path'),
                'unique_countries' => (clone $query)->distinct('country_code')->count('country_code'),
                'package_page_visits' => (clone $query)->whereNotNull('package_slug')->where('package_slug', '!=', '')->count(),
            ],
            'top_packages' => $topPackages,
            'top_pages' => $topPages,
            'visits' => $visits,
        ];
    }

    /**
     * @return array{0: Carbon, 1: Carbon}
     */
    private function resolveRange(string $fromInput, string $toInput): array
    {
        $today = Carbon::today();
        $fallbackFrom = $today->copy()->startOfWeek();
        $fallbackTo = $today->copy()->endOfDay();

        $from = $this->safeDateOrFallback($fromInput, $fallbackFrom)->startOfDay();
        $to = $this->safeDateOrFallback($toInput, $fallbackTo)->endOfDay();

        if ($from->greaterThan($to)) {
            [$from, $to] = [$to->copy()->startOfDay(), $from->copy()->endOfDay()];
        }

        return [$from, $to];
    }

    private function safeDateOrFallback(string $value, Carbon $fallback): Carbon
    {
        if ($value === '') {
            return $fallback;
        }

        try {
            return Carbon::parse($value);
        } catch (\Throwable) {
            return $fallback;
        }
    }
}
