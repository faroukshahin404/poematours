<?php

namespace App\Providers;

use App\Contracts\Repositories\Dashboard\Admin\ActivityRepositoryInterface;
use App\Contracts\Repositories\Dashboard\Admin\AdminAuthRepositoryInterface;
use App\Contracts\Repositories\Dashboard\Admin\BoatRepositoryInterface;
use App\Contracts\Repositories\Dashboard\Admin\CurrencyRepositoryInterface;
use App\Contracts\Repositories\Dashboard\Admin\DestinationRepositoryInterface;
use App\Contracts\Repositories\Dashboard\Admin\HotelRepositoryInterface;
use App\Contracts\Repositories\Dashboard\Admin\HotelRoomRepositoryInterface;
use App\Contracts\Repositories\Dashboard\Admin\LanguageRepositoryInterface;
use App\Contracts\Repositories\Dashboard\Admin\PackageCategoryRepositoryInterface;
use App\Contracts\Repositories\Dashboard\Admin\PackageLabelGroupRepositoryInterface;
use App\Contracts\Repositories\Dashboard\Admin\PackageLabelRepositoryInterface;
use App\Contracts\Repositories\Dashboard\Admin\ReelRepositoryInterface;
use App\Contracts\Repositories\Dashboard\Admin\TravelPackageRepositoryInterface;
use App\Contracts\Repositories\Front\PackageRepositoryInterface;
use App\Repositories\Dashboard\Admin\ActivityRepository;
use App\Repositories\Dashboard\Admin\AdminAuthRepository;
use App\Repositories\Dashboard\Admin\BoatRepository;
use App\Repositories\Dashboard\Admin\CurrencyRepository;
use App\Repositories\Dashboard\Admin\DestinationRepository;
use App\Repositories\Dashboard\Admin\HotelRepository;
use App\Repositories\Dashboard\Admin\HotelRoomRepository;
use App\Repositories\Dashboard\Admin\LanguageRepository;
use App\Repositories\Dashboard\Admin\PackageCategoryRepository;
use App\Repositories\Dashboard\Admin\PackageLabelGroupRepository;
use App\Repositories\Dashboard\Admin\PackageLabelRepository;
use App\Repositories\Dashboard\Admin\ReelRepository;
use App\Repositories\Dashboard\Admin\TravelPackageRepository;
use App\Repositories\Front\PackageRepository;
use App\Services\Frontend\DestinationViewService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
        $this->app->bind(LanguageRepositoryInterface::class, LanguageRepository::class);
        $this->app->bind(CurrencyRepositoryInterface::class, CurrencyRepository::class);
        $this->app->bind(DestinationRepositoryInterface::class, DestinationRepository::class);
        $this->app->bind(PackageCategoryRepositoryInterface::class, PackageCategoryRepository::class);
        $this->app->bind(PackageLabelGroupRepositoryInterface::class, PackageLabelGroupRepository::class);
        $this->app->bind(PackageLabelRepositoryInterface::class, PackageLabelRepository::class);
        $this->app->bind(ReelRepositoryInterface::class, ReelRepository::class);
        $this->app->bind(HotelRepositoryInterface::class, HotelRepository::class);
        $this->app->bind(HotelRoomRepositoryInterface::class, HotelRoomRepository::class);
        $this->app->bind(TravelPackageRepositoryInterface::class, TravelPackageRepository::class);
        $this->app->bind(PackageRepositoryInterface::class, PackageRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['frontend.layouts.header', 'frontend.layouts.app'], function ($view): void {
            $view->with('headerDestinations', app(DestinationViewService::class)->all());
        });
    }
}
