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

use GuzzleHttp\Client;
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
        $source = realpath(__DIR__.'/../config/emoji.php');

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
        $this->registerParser();
    }

    /**
     * Register the parser class.
     *
     * @return void
     */
    protected function registerParser()
    {
        $this->app->singleton(EmojiParser::class, function (Container $app) {
            $map = $app->cache->remember('emoji', 10080, function () use ($app) {
                $headers = ['Accept' => 'application/vnd.github.v3+json'];

                if ($token = $app->config->get('emoji.token')) {
                    $headers['OAUTH-TOKEN'] = $token;
                }
                
                $response = (new Client())->get('https://api.github.com/emojis', ['headers' => $headers]);

                return json_decode((string) $response->getBody(), true);
            });

            return new EmojiParser($map);
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
