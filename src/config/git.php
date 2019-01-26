<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Git Access Providers.
    |--------------------------------------------------------------------------
    |
    | This file will contain all the configurations for lilessam/git-repos.
    |
    */
    'default' => 'github',

    /*
    |--------------------------------------------------------------------------
    | Github Configuration.
    |--------------------------------------------------------------------------
    |
    | In this array you'll need to specify the function that will be used to
    | get the access token of the user.
    */
    'github' => [
        'get_token_closure' => 'App\User::getGithubToken'
    ],

    /*
    |--------------------------------------------------------------------------
    | Bitbucket Configuration.
    |--------------------------------------------------------------------------
    |
    | In this array you'll need to specify the function that will be used to
    | get the access token of the user. Another function to get the refresh
    | token, and last one to update the token in your database.
    */
    'bitbucket' => [
        'get_token_closure' => 'App\User::getBitbucketToken',
        'refresh_token_closure' => 'App\User::getBitbucketRefreshToken',
        'update_token_closure' => 'App\User::updateBitbucketToken',
    ],
];
