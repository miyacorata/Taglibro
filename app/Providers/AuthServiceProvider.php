<?php

namespace App\Providers;

use App\Auth\Auth\CognitoUserProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Cognito カスタムガードを登録
        Auth::extend('cognito', function ($app, $name, array $config) {
            $guard = new \Illuminate\Auth\SessionGuard(
                $name,
                new CognitoUserProvider(),
                $app['session.store']
            );
            $guard->setCookieJar($app['cookie']);
            return $guard;
        });
    }
}
