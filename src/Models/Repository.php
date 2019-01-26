<?php

namespace Lilessam\Git\Models;

use Lilessam\Git\Git;
use Lilessam\Git\Contracts\Repository as RepositoryInterface;

class Repository implements RepositoryInterface
{
    /**
     * Provider of repository.
     * @var string
     */
    public $provider;

    /**
     * ID of repository.
     * @var string
     */
    public $id;

    /**
     * Name of repository.
     * @var string
     */
    public $name;

    /**
     * Full URL of repository.
     * @var string
     */
    public $url;

    /**
     * Create a new repository instance.
     * @param string $provider
     * @param string $id
     * @param string $name
     * @param string $url
     * @return void
     */
    public function __construct(string $provider, string $id, string $name, string $url)
    {
        $this->provider = $provider;
        $this->id = $id;
        $this->name = $name;
        $this->url = $url;
    }

    /**
     * Get the provider of the repository.
     *
     * @return string
     */
    public function getProvider() : string
    {
        return $this->provider;
    }

    /**
     * Get the ID of the repository.
     *
     * @return string
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * Get the full name of the repository.
     *
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Get the URL of the repository.
     *
     * @return string
     */
    public function getUrl() : string
    {
        return $this->url;
    }

    /**
     * Download the repository content.
     * @param string $format
     * @return Binary
     */
    public function download(string $format)
    {
        return git()->driver($this->provider)->download($this->id, $format);
    }
}
