<?php

namespace App\Providers;

use App\Repo\StreetRepository;
use App\Repo\StreetModelRepository;
use App\Repo\StreetRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(StreetRepository::class, function ($app) {
            return new StreetRepository($app['db']->connection());
        });

        $this->app->alias(StreetRepository::class, StreetRepositoryInterface::class);
        $this->app->alias(StreetRepositoryInterface::class, 'streets');
    }
}
