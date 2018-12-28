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

namespace AltThree\Tests\Emoji;

use AltThree\Emoji\EmojiExtension;
use AltThree\Emoji\EmojiParser;
use AltThree\Emoji\Repositories\RepositoryInterface;
use GrahamCampbell\TestBenchCore\ServiceProviderTrait;

/**
 * This is the service provider test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class ServiceProviderTest extends AbstractTestCase
{
    use ServiceProviderTrait;

    public function testEmojiRepositoryIsInjectable()
    {
        $this->assertIsInjectable(RepositoryInterface::class);
    }

    public function testEmojiParserIsInjectable()
    {
        $this->assertIsInjectable(EmojiParser::class);
    }

    public function testEmojiExtensionIsInjectable()
    {
        $this->assertIsInjectable(EmojiExtension::class);
    }

    public function testEnvironmentIsSetup()
    {
        $this->assertTrue(in_array($this->app->make(EmojiParser::class), $this->app->make('markdown.environment')->getInlineParsers(), true));
    }
}
