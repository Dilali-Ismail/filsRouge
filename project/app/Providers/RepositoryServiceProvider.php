<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Eloquent\NegafaRepository;
use App\Repositories\Eloquent\ServiceRepository;
use App\Repositories\Eloquent\ClothingRepository;
use App\Repositories\Eloquent\MenuItemRepository;
use App\Repositories\Interfaces\NegafaRepositoryInterface;
use App\Repositories\Interfaces\ServiceRepositoryInterface;
use App\Repositories\Interfaces\ClothingRepositoryInterface;
use App\Repositories\Interfaces\MenuItemRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            ServiceRepositoryInterface::class,
            ServiceRepository::class
        );

        $this->app->bind(
            MenuItemRepositoryInterface::class,
            MenuItemRepository::class
        );
        $this->app->bind(
            ClothingRepositoryInterface::class,
            ClothingRepository::class
        );
        $this->app->bind(
            NegafaRepositoryInterface::class,
            NegafaRepository::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
