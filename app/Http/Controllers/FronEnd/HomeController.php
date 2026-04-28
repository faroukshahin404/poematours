<?php

namespace App\Http\Controllers\FronEnd;

use App\Http\Controllers\Controller;
use App\Services\Frontend\JourneyBlogService;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('frontend.home.index');
    }

    public function aboutUs(): View
    {
        return view('frontend.pages.aboutus.index');
    }

    public function ourJourneys(): View
    {
        $blogs = app(JourneyBlogService::class)->all();

        return view('frontend.pages.aboutus.our-journies.index', [
            'featuredBlog' => $blogs[0] ?? null,
            'journeyBlogs' => array_slice($blogs, 1),
            'categories' => ['All', 'Culture', 'Cruise', 'Family', 'Leisure'],
        ]);
    }

    public function ourJourneyDetails(string $slug): View
    {
        $blog = app(JourneyBlogService::class)->findBySlug($slug);
        abort_if(!$blog, 404);

        return view('frontend.pages.aboutus.our-journies.details', [
            'blog' => $blog,
        ]);
    }

    public function termsOfUse(): View
    {
        return view('frontend.pages.legal.terms-of-use');
    }

    public function privacyPolicy(): View
    {
        return view('frontend.pages.legal.privacy-policy');
    }
}
