<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard shell (placeholder).
     */
    public function __invoke(): Response
    {
        return Inertia::render('Dashboard/Admin/Dashboard');
    }
}
