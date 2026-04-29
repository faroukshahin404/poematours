<?php

namespace App\Http\Controllers\FronEnd;

use App\Http\Controllers\Controller;
use App\Services\Frontend\JourneyBlogService;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $journeyHighlights = array_slice(app(JourneyBlogService::class)->all(), 0, 3);

        return view('frontend.home.index', [
            'journeyHighlights' => $journeyHighlights,
        ]);
    }

    public function aboutUs(): View
    {
        return view('frontend.pages.aboutus.index');
    }

    public function ourJourneys(): View
    {
        $blogs = app(JourneyBlogService::class)->all();

        return view('frontend.our-journies.index', [
            'featuredBlog' => $blogs[0] ?? null,
            'journeyBlogs' => array_slice($blogs, 1),
            'categories' => ['All', 'Culture', 'Cruise', 'Family', 'Leisure'],
        ]);
    }

    public function ourJourneyDetails(string $slug): View
    {
        $blog = app(JourneyBlogService::class)->findBySlug($slug);
        abort_if(!$blog, 404);

        return view('frontend.our-journies.details', [
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

    public function login(): View
    {
        return view('frontend.auth.login');
    }

    public function register(): View
    {
        return view('frontend.auth.register');
    }

    public function forgotPassword(): View
    {
        return view('frontend.auth.forgot-password');
    }
}
