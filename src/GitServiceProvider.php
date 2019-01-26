<?php

namespace Lilessam\Git;

use Illuminate\Support\ServiceProvider;

class GitServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/git.php' => config_path('git.php'),
        ]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/git.php',
            'git'
        );

        $this->app->singleton('Github\Client', function () {
            $client = new \Github\Client();
            $token = config('git.github.get_token_closure')();
            $client->authenticate($token, null, \Github\Client::AUTH_HTTP_TOKEN);

            return $client;
        });

        $this->app->singleton('Bitbucket\Client', function () {
            // Refresh the token.
            $token = \Lilessam\Git\Providers\Bitbucket::refreshToken();
            config('git.bitbucket.update_token_closure')($token);

            //
            $client = new \Bitbucket\Client;
            $client->authenticate(\Bitbucket\Client::AUTH_OAUTH_TOKEN, $token);

            return $client;
        });
    }
}
