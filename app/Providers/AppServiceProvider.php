<?php

namespace App\Providers;

use App\Services\Product\ProductService;
use App\Services\Product\ProductServiceImpl;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use App\Currency;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (env("FORCED_HTTPS_SCHEME", true)) {
            URL::forceScheme('https');
        }

        view()->composer('layouts.app', function ($view) {
            $currencies = Currency::all();
            $view->with(['currencies' => $currencies]);
        });
    }

    /**d
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ProductService::class, function () {
            return new ProductServiceImpl();
        });
    }
}
