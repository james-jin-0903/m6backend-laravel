<?php
namespace App\Providers;

use Aws\Credentials\Credentials;
use Illuminate\Support\ServiceProvider;
use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;

class CognitoAuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // ---------------DOCUMENTATION----------------
        // creates a singleton that can be called like this
        // in a controller
        // $user = app()->make('CognitoSingleton')->getUser([
        //     'AccessToken' => "your_toke"
        // ]);
        // replace getUser with whatever function front the congito library
        // Please do NOT change this, unless you know what your doing
        // -------------END DOCUMENTATION--------------

        $this->app->singleton('CognitoSingleton', function ($app) {
            $credentials = new Credentials(getenv('AWS_ACCESS_KEY_ID') , getenv('AWS_SECRET_ACCESS_KEY'));
            $config = [
                'credentials' => $credentials,
                'version' => getenv('COGNITO_VERSION'),
                'region' => getenv('COGNITO_REGION'),
                'app_client_id' => getenv('COGNITO_CLIENT_ID'),
                'user_pool_id' => getenv('COGNITO_USER_POOL_ID'),
            ];

            return new CognitoIdentityProviderClient($config);
        });
    }
}
