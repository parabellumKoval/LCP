<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Landing;
use App\Observers\LandingObserver;

use App\Models\Page;
use App\Observers\PageObserver;

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
      Landing::observe(LandingObserver::class);
      Page::observe(PageObserver::class);
    }
}
