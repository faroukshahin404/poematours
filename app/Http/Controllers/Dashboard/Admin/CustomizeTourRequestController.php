<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomizeTourRequest;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class CustomizeTourRequestController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Dashboard/Admin/CustomizeRequests/Index', [
            'requests' => CustomizeTourRequest::query()
                ->with('package:id,title,slug')
                ->latest()
                ->paginate(20)
                ->through(function (CustomizeTourRequest $request): array {
                    return [
                        'id' => $request->id,
                        'package_id' => $request->package_id,
                        'package_title' => $request->package?->title,
                        'package_admin_url' => $request->package_id
                            ? route('admin.packages.edit', $request->package_id)
                            : null,
                        'package_public_url' => $request->package?->slug
                            ? route('packages.show', $request->package->slug)
                            : null,
                        'full_name' => $request->full_name,
                        'contact_summary' => $request->contact_summary,
                        'travelers' => $this->travelersSummary($request),
                        'travel_window' => $this->travelWindow($request),
                        'destinations' => $request->destinations,
                        'interests' => $request->interests ?? [],
                        'budget_range' => $request->budget_range,
                        'status' => $request->status,
                        'notes_preview' => Str::limit((string) $request->notes, 120),
                        'created_at' => optional($request->created_at)?->format('Y-m-d H:i'),
                    ];
                }),
        ]);
    }

    private function travelersSummary(CustomizeTourRequest $request): string
    {
        $adults = $request->adults;
        $children = $request->children;

        if ($adults === null && $children === null) {
            return 'Not specified';
        }

        $parts = [];
        if ($adults !== null) {
            $parts[] = $adults.' adult'.($adults === 1 ? '' : 's');
        }
        if ($children !== null) {
            $parts[] = $children.' child'.($children === 1 ? '' : 'ren');
        }

        return implode(', ', $parts);
    }

    private function travelWindow(CustomizeTourRequest $request): string
    {
        if ($request->arrival_date && $request->departure_date) {
            return $request->arrival_date->format('Y-m-d').' to '.$request->departure_date->format('Y-m-d');
        }

        if ($request->arrival_date) {
            return 'From '.$request->arrival_date->format('Y-m-d');
        }

        if ($request->departure_date) {
            return 'Until '.$request->departure_date->format('Y-m-d');
        }

        return 'Flexible / not specified';
    }
}
