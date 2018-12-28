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
use Illuminate\Contracts\Cache\Repository;

/**
 * This is the emoji caching repository class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class CachingRepository implements RepositoryInterface
{
    /**
     * The inner repo.
     *
     * @var \AltThree\Emoji\Repositories\RepositoryInterface
     */
    protected $repo;

    /**
     * The cache instance.
     *
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * The cache key.
     *
     * @var string
     */
    protected $key;

    /**
     * The cache life.
     *
     * @var int
     */
    protected $life;

    /**
     * Create a new emoji caching repository instance.
     *
     * @param \AltThree\Emoji\Repositories\RepositoryInterface $repo
     * @param \Illuminate\Contracts\Cache\Repository           $cache
     * @param string                                           $key
     * @param int                                              $life
     *
     * @return void
     */
    public function __construct(RepositoryInterface $repo, Repository $cache, string $key, int $life)
    {
        $this->repo = $repo;
        $this->cache = $cache;
        $this->key = $key;
        $this->life = $life;
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
            return $this->cache->remember($this->key, $this->life, function () {
                return $this->repo->get();
            });
        } catch (FetchException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new FetchException($e);
        }
    }
}
