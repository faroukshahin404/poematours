<?php

namespace App\Http\Controllers\FronEnd;

use App\Http\Controllers\Controller;
use App\Services\Frontend\SitemapService;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __construct(
        private readonly SitemapService $sitemapService
    ) {}

    public function index(): Response
    {
        $entries = $this->sitemapService->entries();

        return response()
            ->view('frontend.sitemap.index', ['entries' => $entries])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
