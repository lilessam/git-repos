<?php

namespace Lilessam\Git;

use Illuminate\Support\Manager;
use Lilessam\Git\Providers\Github;
use Lilessam\Git\Providers\Bitbucket;

class Git extends Manager
{
    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app['config']['git.default'] ?? 'github';
    }

    /**
     * Create an instance of Github driver.
     *
     * @return \Lilessam\Git\Providers\Github
     */
    public function createGithubDriver()
    {
        return app(Github::class);
    }

    /**
     * Create an instance of Bitbucket driver.
     *
     * @return \Lilessam\Git\Providers\Bitbucket
     */
    public function createBitbucketDriver()
    {
        return app(Bitbucket::class);
    }
}
