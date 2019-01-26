<?php

namespace Lilessam\Git\Providers;

use Github\Client;
use Illuminate\Support\Collection;
use Lilessam\Git\Models\Repository;
use Lilessam\Git\Contracts\Provider;

class Github implements Provider
{
    /**
     * Github API wrapper instrance.
     * @var \Github\Client
     */
    private $client;

    /**
     * Github archive formats.
     * @var array
     */
    private $archiveFormats = [
        'zip' => 'zipball',
        'tar' => 'tarball'
    ];

    /**
     * Set the client once the instrance is initiated.
     *
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get the email of the user.
     *
     * @return string
     */
    public function email() : string
    {
        $emails = collect($this->client->current_user()->emails()->all());

        $primaryEmail = $emails->first(function ($email) {
            return $email['primary'];
        });

        return $primaryEmail ? $primaryEmail['email'] : $emails[0]['email'];
    }

    /**
     * Get the repositories of the user.
     *
     * @return \Illuminate\Support\Collection
     */
    public function repos() : Collection
    {
        $repos = collect($this->client->current_user()->repositories('all', 'updated', 'desc'));

        $repos = $repos->map(function ($repo) {
            return new Repository('github', $repo['full_name'], $repo['name'], $repo['html_url']);
        });

        return $repos;
    }

    /**
     * Get the binary data of an archive of the repository.
     * @param string $id
     * @param string $format
     * @return Binary
     */
    public function download(string $id, string $format)
    {
        list($username, $name) = explode('/', $id);

        return $this->client->repo()->contents()
                ->archive($username, $name, $this->archiveFormats[$format]);
    }
}
