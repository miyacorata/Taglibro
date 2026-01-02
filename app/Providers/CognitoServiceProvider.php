<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use League\OAuth2\Client\Provider\GenericProvider;

final class CognitoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->singleton('oauth2.cognito', function ($app) {
            return new GenericProvider([
                'clientId' => config('services.cognito.client_id'),
                'clientSecret' => config('services.cognito.client_secret'),
                'redirectUri' => config('services.cognito.redirect'),
                'urlAuthorize' => config('services.cognito.domain') . '/oauth2/authorize',
                'urlAccessToken' => config('services.cognito.domain') . '/oauth2/token',
                'urlResourceOwnerDetails' => config('services.cognito.domain') . '/oauth2/userInfo',
                'scopes' => ['openid', 'email', 'profile'],
                'scopeSeparator' => ' ',
            ]);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
