<?php

namespace App\Providers;

use App\Repositories\PropertyMetaData\PropertyMetaDataRepository;
use App\Repositories\PropertyMetaData\PropertyMetaDataRepositoryInterface;
use App\Repositories\PropertyOption\PropertyOptionRepository;
use App\Repositories\PropertyOption\PropertyOptionRepositoryInterface;
use Illuminate\Support\Facades\Schema;
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
        if(config('app.env') === 'production') {
            \URL::forceScheme('https');
        }
        Schema::defaultStringLength(191);

        $this->bindings();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    protected function bindings()
    {
        $this->app->bind(PropertyMetaDataRepositoryInterface::class, PropertyMetaDataRepository::class);
        $this->app->bind(PropertyOptionRepositoryInterface::class, PropertyOptionRepository::class);
    }
}
