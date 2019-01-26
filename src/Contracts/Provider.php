<?php

namespace Lilessam\Git\Contracts;

use Illuminate\Support\Collection;

interface Provider
{
    /**
     * Get the email of the logged in user.
     *
     * @return string
     */
    public function email() : string;

    /**
     * Get list of repositories of the driver.
     *
     * @return \Illuminate\Support\Collection
     */
    public function repos() : Collection;

    /**
     * Get the link of a zip file for a branch of a repo.
     * @param string $id
     * @param string $format
     * @return Binary
     */
    public function download(string $id, string $format);
}
