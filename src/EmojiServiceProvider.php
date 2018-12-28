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

namespace AltThree\Emoji;

use AltThree\Emoji\Repositories\CachingRepository;
use AltThree\Emoji\Repositories\GitHubRepository;
use AltThree\Emoji\Repositories\RepositoryInterface;
use GrahamCampbell\GuzzleFactory\GuzzleFactory;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

/**
 * This is the emoji service provider class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class EmojiServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupConfig();
    }

    /**
     * Setup the config.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $source = realpath($raw = __DIR__.'/../config/emoji.php') ?: $raw;

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('emoji.php')]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('emoji');
        }

        $this->mergeConfigFrom($source, 'emoji');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRepository();
        $this->registerParser();
    }

    /**
     * Register the repository class.
     *
     * @return void
     */
    protected function registerRepository()
    {
        $this->app->singleton(CachingRepository::class, function (Container $app) {
            $repo = new GitHubRepository(
                GuzzleFactory::make(),
                $app->config->get('emoji.token')
            );

            $cache = $app->cache->store($app->config->get('emoji.connection'));
            $key = $app->config->get('emoji.key', 'emoji');
            $life = (int) $app->config->get('emoji.life', 10080);

            return new CachingRepository($repo, $cache, $key, $life);
        });

        $this->app->alias(CachingRepository::class, RepositoryInterface::class);
    }

    /**
     * Register the parser class.
     *
     * @return void
     */
    protected function registerParser()
    {
        $this->app->singleton(EmojiParser::class, function (Container $app) {
            return new EmojiParser($app->make(RepositoryInterface::class));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            //
        ];
    }
}
