<?php

use Lilessam\Git\Git;

if (!function_exists('git')) {
    /**
     * Create a new Git instance.
     *
     * @return \Lilessam\Git\Git
     */
    function git()
    {
        return app(Git::class);
    }
}
