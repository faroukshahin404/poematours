<?php

namespace App\Providers;

use App\Contracts\Repositories\Dashboard\Admin\ActivityRepositoryInterface;
use App\Contracts\Repositories\Dashboard\Admin\AdminAuthRepositoryInterface;
use App\Contracts\Repositories\Dashboard\Admin\BlogCategoryRepositoryInterface;
use App\Contracts\Repositories\Dashboard\Admin\BlogRepositoryInterface;
use App\Contracts\Repositories\Dashboard\Admin\BoatRepositoryInterface;
use App\Contracts\Repositories\Dashboard\Admin\CountryRepositoryInterface;
use App\Contracts\Repositories\Dashboard\Admin\CrmContactRepositoryInterface;
use App\Contracts\Repositories\Dashboard\Admin\CrmServiceRepositoryInterface;
use App\Contracts\Repositories\Dashboard\Admin\CurrencyRepositoryInterface;
use App\Contracts\Repositories\Dashboard\Admin\DestinationRepositoryInterface;
use App\Contracts\Repositories\Dashboard\Admin\HotelRepositoryInterface;
use App\Contracts\Repositories\Dashboard\Admin\HotelRoomRepositoryInterface;
use App\Contracts\Repositories\Dashboard\Admin\LanguageRepositoryInterface;
use App\Contracts\Repositories\Dashboard\Admin\PackageCategoryRepositoryInterface;
use App\Contracts\Repositories\Dashboard\Admin\PackageInclusionRepositoryInterface;
use App\Contracts\Repositories\Dashboard\Admin\PackageLabelGroupRepositoryInterface;
use App\Contracts\Repositories\Dashboard\Admin\PackageLabelRepositoryInterface;
use App\Contracts\Repositories\Dashboard\Admin\ReelRepositoryInterface;
use App\Contracts\Repositories\Dashboard\Admin\ReservationQuestionRepositoryInterface;
use App\Contracts\Repositories\Dashboard\Admin\ReservationRepositoryInterface as AdminReservationRepositoryInterface;
use App\Contracts\Repositories\Dashboard\Admin\TravelPackageRepositoryInterface;
use App\Contracts\Repositories\Front\PackageRepositoryInterface;
use App\Contracts\Repositories\Front\ReservationRepositoryInterface as FrontReservationRepositoryInterface;
use App\Listeners\QueueNotificationMailListener;
use App\Models\Page;
use App\Repositories\Dashboard\Admin\ActivityRepository;
use App\Repositories\Dashboard\Admin\AdminAuthRepository;
use App\Repositories\Dashboard\Admin\BlogCategoryRepository;
use App\Repositories\Dashboard\Admin\BlogRepository;
use App\Repositories\Dashboard\Admin\BoatRepository;
use App\Repositories\Dashboard\Admin\CountryRepository;
use App\Repositories\Dashboard\Admin\CrmContactRepository;
use App\Repositories\Dashboard\Admin\CrmServiceRepository;
use App\Repositories\Dashboard\Admin\CurrencyRepository;
use App\Repositories\Dashboard\Admin\DestinationRepository;
use App\Repositories\Dashboard\Admin\HotelRepository;
use App\Repositories\Dashboard\Admin\HotelRoomRepository;
use App\Repositories\Dashboard\Admin\LanguageRepository;
use App\Repositories\Dashboard\Admin\PackageCategoryRepository;
use App\Repositories\Dashboard\Admin\PackageInclusionRepository;
use App\Repositories\Dashboard\Admin\PackageLabelGroupRepository;
use App\Repositories\Dashboard\Admin\PackageLabelRepository;
use App\Repositories\Dashboard\Admin\ReelRepository;
use App\Repositories\Dashboard\Admin\ReservationQuestionRepository;
use App\Repositories\Dashboard\Admin\ReservationRepository as AdminReservationRepository;
use App\Repositories\Dashboard\Admin\TravelPackageRepository;
use App\Repositories\Front\PackageRepository;
use App\Repositories\Front\ReservationRepository as FrontReservationRepository;
use App\Services\Dashboard\Admin\ContactSettingsService;
use App\Services\Dashboard\Admin\SeoSettingsService;
use App\Services\Frontend\DestinationViewService;
use App\View\Composers\FrontendLayoutSeoComposer;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AdminAuthRepositoryInterface::class, AdminAuthRepository::class);
        $this->app->bind(ActivityRepositoryInterface::class, ActivityRepository::class);
        $this->app->bind(BoatRepositoryInterface::class, BoatRepository::class);
        $this->app->bind(BlogCategoryRepositoryInterface::class, BlogCategoryRepository::class);
        $this->app->bind(BlogRepositoryInterface::class, BlogRepository::class);
        $this->app->bind(LanguageRepositoryInterface::class, LanguageRepository::class);
        $this->app->bind(CurrencyRepositoryInterface::class, CurrencyRepository::class);
        $this->app->bind(CountryRepositoryInterface::class, CountryRepository::class);
        $this->app->bind(DestinationRepositoryInterface::class, DestinationRepository::class);
        $this->app->bind(PackageCategoryRepositoryInterface::class, PackageCategoryRepository::class);
        $this->app->bind(PackageLabelGroupRepositoryInterface::class, PackageLabelGroupRepository::class);
        $this->app->bind(PackageLabelRepositoryInterface::class, PackageLabelRepository::class);
        $this->app->bind(PackageInclusionRepositoryInterface::class, PackageInclusionRepository::class);
        $this->app->bind(CrmContactRepositoryInterface::class, CrmContactRepository::class);
        $this->app->bind(CrmServiceRepositoryInterface::class, CrmServiceRepository::class);
        $this->app->bind(ReservationQuestionRepositoryInterface::class, ReservationQuestionRepository::class);
        $this->app->bind(ReelRepositoryInterface::class, ReelRepository::class);
        $this->app->bind(AdminReservationRepositoryInterface::class, AdminReservationRepository::class);
        $this->app->bind(HotelRepositoryInterface::class, HotelRepository::class);
        $this->app->bind(HotelRoomRepositoryInterface::class, HotelRoomRepository::class);
        $this->app->bind(TravelPackageRepositoryInterface::class, TravelPackageRepository::class);
        $this->app->bind(PackageRepositoryInterface::class, PackageRepository::class);
        $this->app->bind(FrontReservationRepositoryInterface::class, FrontReservationRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(NotificationSent::class, QueueNotificationMailListener::class);

        View::composer(['frontend.layouts.header', 'frontend.layouts.footer', 'frontend.layouts.app'], function ($view): void {
            $view->with('headerDestinations', app(DestinationViewService::class)->all());
            $view->with('contactSettings', app(ContactSettingsService::class)->contactSettingsForForm());
            $view->with('googleSeoScript', app(SeoSettingsService::class)->googleSeoScriptForPublic());
            $view->with(
                'footerLegalPages',
                Page::query()
                    ->where('show_in_footer', true)
                    ->whereNotNull('body')
                    ->where('body', '!=', '')
                    ->orderBy('footer_sort_order')
                    ->orderBy('name')
                    ->get(['slug', 'footer_label', 'name'])
            );
        });

        View::composer('frontend.layouts.app', FrontendLayoutSeoComposer::class);
    }
}
