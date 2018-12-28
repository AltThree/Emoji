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

use AltThree\Emoji\Repositories\GitHubRepository;
use AltThree\Emoji\Repositories\RepositoryInterface;
use Exception;
use GrahamCampbell\TestBenchCore\MockeryTrait;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * This is the github repository test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class GitHubRepositoryTest extends TestCase
{
    use MockeryTrait;

    public function testConstruct()
    {
        $r = new GitHubRepository(Mockery::mock(ClientInterface::class));

        $this->assertInstanceOf(RepositoryInterface::class, $r);
    }

    public function testWithoutToken()
    {
        $c = Mockery::mock(ClientInterface::class);
        $r = new GitHubRepository($c);

        $d = Mockery::mock(ResponseInterface::class);
        $d->shouldReceive('getBody')->once()->with()->andReturn('{":emoji:": "https://url"}');

        $c->shouldReceive('request')->once()
            ->with('get', 'https://api.github.com/emojis', ['headers' => ['Accept' => 'application/vnd.github.v3+json']])
            ->andReturn($d);

        $this->assertSame([':emoji:' => 'https://url'], $r->get());
    }

    public function testWithToken()
    {
        $c = Mockery::mock(ClientInterface::class);
        $r = new GitHubRepository($c, 'ABCDEFGH');

        $d = Mockery::mock(ResponseInterface::class);
        $d->shouldReceive('getBody')->once()->with()->andReturn('{":emoji:": "https://url"}');

        $c->shouldReceive('request')->once()
            ->with('get', 'https://api.github.com/emojis', ['headers' => ['Accept' => 'application/vnd.github.v3+json', 'Authorization' => 'token ABCDEFGH']])
            ->andReturn($d);

        $this->assertSame([':emoji:' => 'https://url'], $r->get());
    }

    /**
     * @expectedException \AltThree\Emoji\Exceptions\FetchException
     * @expectedExceptionMessage Failed to fetch the emoji map.
     */
    public function testWithoutTokenFail()
    {
        $c = Mockery::mock(ClientInterface::class);
        $r = new GitHubRepository($c);

        $c->shouldReceive('request')->once()
            ->with('get', 'https://api.github.com/emojis', ['headers' => ['Accept' => 'application/vnd.github.v3+json']])
            ->andThrow(new class() extends Exception implements GuzzleException {
            });

        $r->get();
    }

    /**
     * @expectedException \AltThree\Emoji\Exceptions\FetchException
     * @expectedExceptionMessage Failed to fetch the emoji map.
     */
    public function testWithTokenFail()
    {
        $c = Mockery::mock(ClientInterface::class);
        $r = new GitHubRepository($c, 'ABCDEFGH');

        $c->shouldReceive('request')->once()
            ->with('get', 'https://api.github.com/emojis', ['headers' => ['Accept' => 'application/vnd.github.v3+json', 'Authorization' => 'token ABCDEFGH']])
            ->andThrow(new class() extends Exception implements GuzzleException {
            });

        $r->get();
    }
}
