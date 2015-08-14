<?php

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
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\ServiceProvider;
use League\CommonMark\Environment;

/**
 * This is the emoji service provider class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class EmojiServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->setupConfig();
        $this->registerEmojiParser();
        $this->registerEnvironment();
    }

    /**
     * Setup the config.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__.'/../config/emoji.php');

        if (class_exists('Illuminate\Foundation\Application', false)) {
            $this->publishes([$source => config_path('emoji.php')]);
        }

        $this->mergeConfigFrom($source, 'emoji');
    }

    /**
     * Register the emoji parser class.
     *
     * @return void
     */
    protected function registerEmojiParser()
    {
        $app = $this->app;

        $app->singleton('emoji', function ($app) {
            $map = $app->cache->remember('emoji', 10080, function () use ($app) {
                $headers = ['Accept' => 'application/vnd.github.v3+json'];

                if ($token = $app->config->get('emoji.token')) {
                    $headers['OAUTH-TOKEN'] = $token;
                }

                return json_decode((new Client())->get('https://api.github.com/emojis', [
                    'headers' => $headers,
                ])->getBody(), true);
            });

            return new EmojiParser($map);
        });

        $this->app->alias('emoji', EmojiParser::class);
    }

    /**
     * Register the environment.
     *
     * @return void
     */
    protected function registerEnvironment()
    {
        $app = $this->app;

        $app->resolving('markdown.environment', function (Environment $environment) use ($app) {
            try {
                $environment->addInlineParser($app['emoji']);
            } catch (Exception $e) {
                $app->make(ExceptionHandler::class)->report($e);
            }
            
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
            'emoji',
        ];
    }
}
