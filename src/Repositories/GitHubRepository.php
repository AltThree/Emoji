<?php

declare(strict_types=1);

/*
 * This file is part of Alt Three Emoji.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AltThree\Emoji\Repositories;

use AltThree\Emoji\Exceptions\FetchException;
use Exception;
use GuzzleHttp\ClientInterface;

/**
 * This is the emoji github repository class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class GitHubRepository implements RepositoryInterface
{
    /**
     * The accept header.
     *
     * @var string
     */
    const ACCEPT_HEADER = 'application/vnd.github.v3+json';

    /**
     * The emoji api url.
     *
     * @var string
     */
    const API_URL = 'https://api.github.com/emojis';

    /**
     * The guzzle client instance.
     *
     * @var \GuzzleHttp\ClientInterface
     */
    protected $client;

    /**
     * The optional api token.
     *
     * @var string|null
     */
    protected $token;

    /**
     * Create a new emoji github repository instance.
     *
     * @param \GuzzleHttp\ClientInterface $client
     * @param string|null                 $token
     *
     * @return void
     */
    public function __construct(ClientInterface $client, string $token = null)
    {
        $this->client = $client;
        $this->token = $token;
    }

    /**
     * Fetch the emoji map.
     *
     * @throws \AltThree\Emoji\Exceptions\FetchException
     *
     * @return array
     */
    public function get()
    {
        try {
            $headers = ['Accept' => self::ACCEPT_HEADER];

            if ($this->token) {
                $headers['Authorization'] = "token {$this->token}";
            }

            $response = $this->client->request('get', self::API_URL, ['headers' => $headers]);

            return (array) json_decode((string) $response->getBody(), true);
        } catch (Exception $e) {
            throw new FetchException($e);
        }
    }
}
