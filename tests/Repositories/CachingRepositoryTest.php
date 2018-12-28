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

namespace AltThree\Tests\Emoji\Exceptions;

use AltThree\Emoji\Exceptions\FetchException;
use AltThree\Emoji\Repositories\CachingRepository;
use AltThree\Emoji\Repositories\RepositoryInterface;
use Exception;
use GrahamCampbell\TestBenchCore\MockeryTrait;
use Illuminate\Cache\Repository as Cache;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Cache\Store;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * This is the caching repository test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class CachingRepositoryTest extends TestCase
{
    use MockeryTrait;

    public function testConstruct()
    {
        $r = new CachingRepository(
            Mockery::mock(RepositoryInterface::class),
            Mockery::mock(Repository::class),
            'emoji',
            60
        );

        $this->assertInstanceOf(RepositoryInterface::class, $r);
    }

    public function testCacheHit()
    {
        $i = Mockery::mock(RepositoryInterface::class);
        $c = Mockery::mock(Store::class);
        $r = new CachingRepository($i, new Cache($c), 'emoji', 60);

        $c->shouldReceive('get')->once()->with('emoji')->andReturn([':emoji:' => 'https://url']);

        $this->assertSame([':emoji:' => 'https://url'], $r->get());
    }

    public function testCacheMiss()
    {
        $i = Mockery::mock(RepositoryInterface::class);
        $c = Mockery::mock(Store::class);
        $r = new CachingRepository($i, new Cache($c), 'emoji', 60);

        $i->shouldReceive('get')->once()->with()->andReturn([':emoji:' => 'https://url']);
        $c->shouldReceive('get')->once()->with('emoji')->andReturn(null);
        $c->shouldReceive('put')->once()->with('emoji', [':emoji:' => 'https://url'], 60)->andReturn(null);

        $this->assertSame([':emoji:' => 'https://url'], $r->get());
    }

    /**
     * @expectedException \AltThree\Emoji\Exceptions\FetchException
     * @expectedExceptionMessage Failed to fetch the emoji map.
     */
    public function testCacheError()
    {
        $i = Mockery::mock(RepositoryInterface::class);
        $c = Mockery::mock(Store::class);
        $r = new CachingRepository($i, new Cache($c), 'emoji', 60);

        $c->shouldReceive('get')->once()->with('emoji')->andThrow(new Exception('Failed to read cache!'));

        $r->get();
    }

    /**
     * @expectedException \AltThree\Emoji\Exceptions\FetchException
     * @expectedExceptionMessage Failed to fetch the emoji map.
     */
    public function testInnerError()
    {
        $i = Mockery::mock(RepositoryInterface::class);
        $c = Mockery::mock(Store::class);
        $r = new CachingRepository($i, new Cache($c), 'emoji', 60);

        $i->shouldReceive('get')->once()->with()->andThrow(new FetchException(new Exception('Failed to get!')));
        $c->shouldReceive('get')->once()->with('emoji')->andReturn(null);

        $r->get();
    }
}
