<?php

namespace App\Providers;

use App\Repositories\Mappers\WishMapper;
use App\Repositories\WishRepository;
use Illuminate\Support\ServiceProvider;
use WishApp\Repository\Contracts\WishRepositoryInterface;
use WishApp\Service\Wish\Contracts\WishServiceInterface;
use WishApp\Service\Wish\WishService;

class WishServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(WishServiceInterface::class, WishService::class);
        $this->app->singleton(WishRepositoryInterface::class, WishRepository::class);
        $this->app->singleton(WishMapper::class);
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
