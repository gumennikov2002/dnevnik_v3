<?php

namespace App\Providers;

use App\Http\Controllers\ProfileController;
use Illuminate\Pagination\Paginator as PaginationPaginator;
use Illuminate\Support\Facades\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function ($view)
        {
            $profile = new ProfileController();
            $view->with('theme', $profile->detect_theme());
        });
        PaginationPaginator::useBootstrap();
    }
}
