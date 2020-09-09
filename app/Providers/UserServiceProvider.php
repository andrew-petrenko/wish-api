<?php

namespace App\Providers;

use App\Repositories\Mappers\UserMapper;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;
use WishApp\Repository\Contracts\UserRepositoryInterface;
use WishApp\Service\User\Contracts\UserServiceInterface;
use WishApp\Service\User\UserService;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(UserServiceInterface::class, UserService::class);
        $this->app->singleton(UserRepositoryInterface::class, UserRepository::class);
        $this->app->singleton(UserMapper::class);
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
