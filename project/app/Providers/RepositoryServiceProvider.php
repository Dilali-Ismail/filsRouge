<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Eloquent\MakeupRepository;
use App\Repositories\Eloquent\NegafaRepository;
use App\Repositories\Eloquent\AmariyaRepository;
use App\Repositories\Eloquent\ServiceRepository;
use App\Repositories\Eloquent\ClothingRepository;
use App\Repositories\Eloquent\MenuItemRepository;
use App\Repositories\Eloquent\PhotographerRepository;
use App\Repositories\Interfaces\MakeupRepositoryInterface;
use App\Repositories\Interfaces\NegafaRepositoryInterface;
use App\Repositories\Interfaces\AmariyaRepositoryInterface;
use App\Repositories\Interfaces\ServiceRepositoryInterface;
use App\Repositories\Interfaces\ClothingRepositoryInterface;
use App\Repositories\Interfaces\MenuItemRepositoryInterface;
use App\Repositories\Interfaces\PhotographerRepositoryInterface;

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
        $this->app->bind(
            MakeupRepositoryInterface::class,
            MakeupRepository::class
        );

        $this->app->bind(
            PhotographerRepositoryInterface::class,
            PhotographerRepository::class
        );
        $this->app->bind(
            AmariyaRepositoryInterface::class,
            AmariyaRepository::class
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
