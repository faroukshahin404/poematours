<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\Admin\PageVisitAnalyticsService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PageVisitController extends Controller
{
    public function __construct(
        private readonly PageVisitAnalyticsService $pageVisitAnalyticsService
    ) {}

    public function index(Request $request): Response
    {
        return Inertia::render('Dashboard/Admin/PageVisits/Index', $this->pageVisitAnalyticsService->build($request));
    }
}
