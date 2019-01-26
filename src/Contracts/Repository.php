<?php

namespace Lilessam\Git\Contracts;

interface Repository
{
    /**
     * Get the provider of the repository.
     *
     * @return string
     */
    public function getProvider() : string;

    /**
     * Get the ID of the repository.
     *
     * @return string
     */
    public function getId() : string;

    /**
     * Get the full name of the repository.
     *
     * @return string
     */
    public function getName() : string;

    /**
     * Get the URL of the repository.
     *
     * @return string
     */
    public function getUrl() : string;

    /**
     * Download the repository content.
     * @param string $format
     * @return Binary
     */
    public function download(string $format);
}
