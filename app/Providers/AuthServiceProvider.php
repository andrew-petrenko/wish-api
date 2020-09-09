<?php

namespace App\Providers;

use App\Services\Contracts\TokenServiceInterface;
use App\Services\JwtService;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use WishApp\Service\Auth\AuthService;
use WishApp\Service\Auth\Contracts\AuthServiceInterface;
use WishApp\Service\Auth\Contracts\PasswordServiceInterface;
use WishApp\Service\Auth\PasswordService;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    public function register()
    {
        $this->app->singleton(AuthServiceInterface::class, AuthService::class);
        $this->app->singleton(PasswordServiceInterface::class, PasswordService::class);
        $this->app->singleton(TokenServiceInterface::class, JwtService::class);
    }

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
