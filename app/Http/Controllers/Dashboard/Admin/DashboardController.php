<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\Admin\DashboardMetricsService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardMetricsService $dashboardMetricsService
    ) {}

    public function __invoke(Request $request): Response
    {
        return Inertia::render('Dashboard/Admin/Dashboard', $this->dashboardMetricsService->build($request));
    }
}
