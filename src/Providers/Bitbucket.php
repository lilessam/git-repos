<?php

namespace Lilessam\Git\Providers;

use Bitbucket\Client;
use Illuminate\Support\Collection;
use Lilessam\Git\Models\Repository;
use Lilessam\Git\Contracts\Provider;

class Bitbucket implements Provider
{
    /**
     * Bitbucket API wrapper instrance.
     * @var \Bitbucket\Client
     */
    private $client;

    /**
    * Bitbucket archive formats.
    * @var array
    */
    private $archiveFormats = [
        'zip' => 'zip',
        'tar' => 'tar.gz'
    ];

    /**
     * Create a new Git driver instance.
     *
     * @param  \Bitbucket\Client  $client
     *
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Refresh the access token.
     *
     * @return string
     */
    public static function refreshToken()
    {
        $options = [
            'auth' => [config('services.bitbucket.client_id'), config('services.bitbucket.client_secret')],
            'form_params' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => config('git.bitbucket.refresh_token_closure')()
            ]
        ];

        $response = (new \GuzzleHttp\Client)
            ->post('https://bitbucket.org/site/oauth2/access_token', $options)
            ->getBody()->getContents();

        $response = json_decode($response, true);

        return $response['access_token'];
    }

    /**
     * Get the email of the logged in user.
     *
     * @return string
     */
    public function email() : string
    {
        $emails = collect($this->client->currentUser()->listEmails()['values']);
        $primaryEmail = $emails->first(function ($email) {
            return $email['is_primary'] == true;
        });

        return $primaryEmail ? $primaryEmail['email'] : $emails[0]['email'];
    }

    /**
     * Get list of repositories of the driver.
     *
     * @return \Illuminate\Support\Collection
     */
    public function repos() : Collection
    {
        // First page repositories
        $response = $this->client->currentUser()->listRepositoryPermissions();
        // The number of pages
        $pages = round($response['size'] / $response['pagelen']);
        // Pages got
        $pagesFetched = 1;
        // Repositories
        $repos = [];

        do {
            if ($pagesFetched > 1) {
                $response = $this->client->currentUser()->listRepositoryPermissions(['page' => $pagesFetched]);
            }
            $repos[] = collect($response['values'])->map(function ($repo) {
                return new Repository('bitbucket', $repo['repository']['full_name'], $repo['repository']['name'], $repo['repository']['links']['html']['href']);
            });
            $pagesFetched += 1;
        } while ($pagesFetched <= $pages);

        return collect($repos)->flatten(1);
    }

    /**
     * Get the link of a zip file for a branch of a repo.
     * @param string $id
     * @param string $format
     * @return Binary
     */
    public function download(string $id, string $format)
    {
        return file_get_contents('https://bitbucket.org/' . $id . '/get/master.' . $this->archiveFormats[$format] . '?access_token=' . config('git.bitbucket.get_token_closure')());
    }
}
