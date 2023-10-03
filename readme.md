## Laravel Git Repos
This package provides simplified API to access users Github & Bitbucket public and private repositories and download their contents as zip files.

## Support Me
[Buy Me A Coffee](https://www.buymeacoffee.com/a7med3essam)

### Requirements
- PHP >= 7.1
- `illuminate/support` ~5.0
- `bitbucket/client` ^1.1
- `knplabs/github-api`  ^2.10

### Installation
1- `composer require lilessam/git-repos dev-master`

2- Add `Lilessam\Git\GitServiceProvider::class` to `config/app.php`

3- Run `php artisan vendor:publish --provider='Lilessam\Git\GitServiceProvider`


The package assumes you have already your mechanism to authenticate the users with Bitbucket and Github.

I'd highly recommend using `laravel/socialite` package as it's pretty easy and already have Github and Bitbucket drivers.

Please remember when you authenticate the users using Bitbucket to store the refresh token. You'll receive this as an attribute of Laravel Socialte User.

```PHP
/**
 * Obtain the user information from Bitbucket.
 *
 * @return \Illuminate\Http\RedirectResponse
 */
public function handleProviderCallback()
{
    $socialiteUser = Socialite::driver('bitbucket')->user();

    $user = User::registerUsingBitbucket($socialiteUser);

    $user->setBitbucketTokens($socialiteUser->token, $socialiteUser->refreshToken);

    Auth::login($user);

    return redirect($this->redirectTo);
}
```

So, You'll need to write four static functions somewhere  and specify them in `config/git.php`

1- `github.get_token_closure`
A callback that will return the authenticated user Github access token.

```PHP
/**
 * Get Github token for the needed session.
 *
 * @return string
 */
public static function getGithubToken()
{
    return Auth::user()->{static::GITHUB_TOKEN_COLUMN};
}
```

2- `bitbucket.get_token_closure`
A callback that will return the authenticated user Bitbucket access token.

```PHP
/**
 * Get Bitbucket token for the needed session.
 *
 * @return string
 */
public static function getBitbucketToken()
{
    return Auth::user()->{static::BITBUCKET_TOKEN_COLUMN};
}
```

3- `bitbucket.refresh_token_closure`
A callback that returns the Bitbucket refresh token of the authenticated user.

```PHP
/**
 * Get Bitbucket refresh token and return it for Git service provider.
 *
 * @return string
 */
public static function getBitbucketRefreshToken()
{
    return Auth::user()->{static::BITBUCKET_REFRESH_TOKEN_COLUMN};
}
```

4- `bitbucket.update_token_closure`
A callback that will be used to update the Bitbcuket access token. It will receive one parameter.

```PHP
/**
 * Update the Bitbucket access token in storage.
 *
 * @param string $token
 * @return void
 */
public static function updateBitbucketToken($token)
{
    Auth::user()->update([static::BITBUCKET_TOKEN_COLUMN => $token]);
}

```

### Usage

1- Get user Github or Bitbucket email.
```PHP
// Default driver | Can be changed from config/git.php
git()->email();

// Github email
git()->driver('github')->email();

// Bitbucket email
git()->driver('bitbucket')->email();
```

2- Get user repositories.
```PHP
// Default driver | Can be changed from config/git.php
git()->repos();

// Github email
git()->driver('github')->repos();

// Bitbucket email
git()->driver('bitbucket')->repos();
```

3- Get specific repository info.
```PHP
$repo = git()->repos()->first(function  ($repo)  {
    return  $repo->id  ==  'lilessam/git-repos';
});

echo $repo->id; // 'lilessam/git-repos'
echo $repo->name; // 'git-repos'
echo $repo->url; // 'https://github.com/lilessam/git-repos'
```

4- Download repository.
```PHP
$repo = git()->repos()->first(function  ($repo)  {
    return  $repo->id  ==  'lilessam/git-repos';
});
$repo->download('zip');
// OR
$repo->download('tar');
```
I'm planning to add features to get more details for each repository but the main feature meant was to download the repository contents.

Please feel free to create PR to add more features.
