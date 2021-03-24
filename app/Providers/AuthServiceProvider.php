<?php

namespace App\Providers;

use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\CognitoJWT;
class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */

    public function boot(){
        $this->app['auth']->viaRequest('cognito', function ($request) {
            $jwt = $request->bearerToken();
            if ($jwt) {
                return CognitoJWT::verifyToken($jwt);
            }
                return null;
        });
    }
}