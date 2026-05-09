<?php

namespace App\Services\Dashboard\Admin;

use App\Models\Booking;
use App\Models\CrmContact;
use App\Models\CustomizeTourRequest;
use App\Models\PageVisit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardMetricsService
{
    /**
     * @return array<string, mixed>
     */
    public function build(Request $request): array
    {
        [$period, $from, $to] = $this->resolvePeriod($request);

        $reservationsQuery = Booking::query()
            ->whereBetween('created_at', [$from, $to]);

        $customRequestsQuery = CustomizeTourRequest::query()
            ->whereBetween('created_at', [$from, $to]);

        $crmContactsQuery = CrmContact::query()
            ->whereBetween('created_at', [$from, $to]);

        $pageVisitsQuery = PageVisit::query()
            ->whereBetween('visited_at', [$from, $to]);

        $latestReservations = (clone $reservationsQuery)
            ->latest('created_at')
            ->limit(6)
            ->get()
            ->map(function (Booking $booking): array {
                return [
                    'id' => $booking->id,
                    'guest_name' => trim($booking->first_name.' '.$booking->last_name),
                    'email' => $booking->email,
                    'booking_status' => $booking->booking_status,
                    'payment_status' => $booking->payment_status,
                    'paid_amount' => (float) ($booking->paid_amount ?? 0),
                    'total_amount' => (float) ($booking->total_amount ?? 0),
                    'created_at' => $booking->created_at?->format('Y-m-d H:i'),
                ];
            })
            ->values()
            ->all();

        return [
            'filters' => [
                'period' => $period,
                'from' => $from->toDateString(),
                'to' => $to->toDateString(),
                'available_periods' => [
                    ['value' => 'this_week', 'label' => 'This Week'],
                    ['value' => 'previous_week', 'label' => 'Previous Week'],
                    ['value' => 'custom', 'label' => 'Custom Period'],
                ],
            ],
            'stats' => [
                'reservations_count' => (clone $reservationsQuery)->count(),
                'custom_requests_count' => (clone $customRequestsQuery)->count(),
                'total_paid_amount' => (float) ((clone $reservationsQuery)->sum('paid_amount') ?? 0),
                'total_reservations_amount' => (float) ((clone $reservationsQuery)->sum('total_amount') ?? 0),
            ],
            'charts' => [
                'most_visited_pages' => $this->mostVisitedPages($pageVisitsQuery),
                'crm_contacts_by_source' => $this->crmContactsBySource($crmContactsQuery),
            ],
            'latest_reservations' => $latestReservations,
        ];
    }

    /**
     * @return array{0: string, 1: Carbon, 2: Carbon}
     */
    private function resolvePeriod(Request $request): array
    {
        $period = (string) $request->query('period', 'this_week');
        $today = Carbon::today();

        if ($period === 'previous_week') {
            $from = $today->copy()->subWeek()->startOfWeek();
            $to = $today->copy()->subWeek()->endOfWeek();

            return [$period, $from, $to];
        }

        if ($period === 'custom') {
            $fromInput = (string) $request->query('from', '');
            $toInput = (string) $request->query('to', '');

            $from = $this->safeDateOrFallback($fromInput, $today->copy()->startOfWeek())->startOfDay();
            $to = $this->safeDateOrFallback($toInput, $today->copy()->endOfDay())->endOfDay();

            if ($from->greaterThan($to)) {
                [$from, $to] = [$to->copy()->startOfDay(), $from->copy()->endOfDay()];
            }

            return [$period, $from, $to];
        }

        $from = $today->copy()->startOfWeek();
        $to = $today->copy()->endOfWeek();

        return ['this_week', $from, $to];
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

    /**
     * @return array<int, array<string, mixed>>
     */
    private function mostVisitedPages($query): array
    {
        return (clone $query)
            ->selectRaw("
                COALESCE(
                    CASE
                        WHEN package_slug IS NOT NULL AND package_slug <> ''
                            THEN CONCAT(COALESCE(route_name, path), ' (', package_slug, ')')
                        ELSE NULL
                    END,
                    COALESCE(route_name, path)
                ) as page_key,
                COUNT(*) as visits_count,
                COUNT(DISTINCT COALESCE(country_code, ip_address)) as countries_count
            ")
            ->groupBy('page_key')
            ->orderByDesc('visits_count')
            ->limit(6)
            ->get()
            ->map(function ($row): array {
                return [
                    'page' => (string) $row->page_key,
                    'visits_count' => (int) $row->visits_count,
                    'countries_count' => (int) $row->countries_count,
                ];
            })
            ->values()
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function crmContactsBySource($query): array
    {
        $labels = CrmContact::sourceLabels();

        return (clone $query)
            ->selectRaw("COALESCE(source, 'unknown') as source_key, COUNT(*) as total")
            ->groupBy('source_key')
            ->orderByDesc('total')
            ->get()
            ->map(function ($row) use ($labels): array {
                $sourceKey = (string) $row->source_key;

                return [
                    'source' => $sourceKey,
                    'label' => $labels[$sourceKey] ?? ucfirst(str_replace('_', ' ', $sourceKey)),
                    'total' => (int) $row->total,
                ];
            })
            ->values()
            ->all();
    }
}
