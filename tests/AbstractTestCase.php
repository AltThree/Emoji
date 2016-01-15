<?php

/*
 * This file is part of Alt Three Emoji.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AltThree\Tests\Emoji;

use AltThree\Emoji\EmojiParser;
use AltThree\Emoji\EmojiServiceProvider;
use GrahamCampbell\Markdown\MarkdownServiceProvider;
use GrahamCampbell\TestBench\AbstractPackageTestCase;

/**
 * This is the abstract test case class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
abstract class AbstractTestCase extends AbstractPackageTestCase
{
    /**
     * Setup the application environment.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app->config->set('markdown.extensions', [EmojiParser::class]);

        $app->cache->forever('emoji', [
            '+1'             => 'https://assets-cdn.github.com/images/icons/emoji/unicode/1f44d.png?v5',
            '-1'             => 'https://assets-cdn.github.com/images/icons/emoji/unicode/1f44e.png?v5',
            '100'            => 'https://assets-cdn.github.com/images/icons/emoji/unicode/1f4af.png?v5',
            '1234'           => 'https://assets-cdn.github.com/images/icons/emoji/unicode/1f522.png?v5',
            '8ball'          => 'https://assets-cdn.github.com/images/icons/emoji/unicode/1f3b1.png?v5',
            'a'              => 'https://assets-cdn.github.com/images/icons/emoji/unicode/1f170.png?v5',
            'ab'             => 'https://assets-cdn.github.com/images/icons/emoji/unicode/1f18e.png?v5',
            'abc'            => 'https://assets-cdn.github.com/images/icons/emoji/unicode/1f524.png?v5',
            'abcd'           => 'https://assets-cdn.github.com/images/icons/emoji/unicode/1f521.png?v5',
            'accept'         => 'https://assets-cdn.github.com/images/icons/emoji/unicode/1f251.png?v5',
            'aerial_tramway' => 'https://assets-cdn.github.com/images/icons/emoji/unicode/1f6a1.png?v5',
            'airplane'       => 'https://assets-cdn.github.com/images/icons/emoji/unicode/2708.png?v5',
            'alarm_clock'    => 'https://assets-cdn.github.com/images/icons/emoji/unicode/23f0.png?v5',
            'alien'          => 'https://assets-cdn.github.com/images/icons/emoji/unicode/1f47d.png?v5',
            'ambulance'      => 'https://assets-cdn.github.com/images/icons/emoji/unicode/1f691.png?v5',
            'anchor'         => 'https://assets-cdn.github.com/images/icons/emoji/unicode/2693.png?v5',
            'angel'          => 'https://assets-cdn.github.com/images/icons/emoji/unicode/1f47c.png?v5',
            'anger'          => 'https://assets-cdn.github.com/images/icons/emoji/unicode/1f4a2.png?v5',
        ]);
    }

    /**
     * Get the required service providers.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return string[]
     */
    protected function getRequiredServiceProviders($app)
    {
        return [MarkdownServiceProvider::class];
    }

    /**
     * Get the service provider class.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return string
     */
    protected function getServiceProviderClass($app)
    {
        return EmojiServiceProvider::class;
    }
}
