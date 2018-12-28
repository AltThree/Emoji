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

use AltThree\Emoji\Exceptions\FetchException;
use AltThree\Emoji\Repositories\RepositoryInterface;
use Exception;

/**
 * This is the emoji parser test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class EmojiParserTest extends AbstractTestCase
{
    public function provideRenderCases()
    {
        return [
            [':+1:', '<p><img class="emoji" data-emoji="+1" src="https://assets-cdn.github.com/images/icons/emoji/unicode/1f44d.png?v5" alt="+1" /></p>'],
            ['* :airplane:', "<ul>\n<li>\n<img class=\"emoji\" data-emoji=\"airplane\" src=\"https://assets-cdn.github.com/images/icons/emoji/unicode/2708.png?v5\" alt=\"airplane\" />\n</li>\n</ul>"],
            ['foo bar baz: lol', '<p>foo bar baz: lol</p>'],
            [':+1:123', '<p>:+1:123</p>'],
            [':123123123:', '<p>:123123123:</p>'],
            [':+1 :', '<p>:+1 :</p>'],
            [':8ball: :100:', '<p><img class="emoji" data-emoji="8ball" src="https://assets-cdn.github.com/images/icons/emoji/unicode/1f3b1.png?v5" alt="8ball" /> <img class="emoji" data-emoji="100" src="https://assets-cdn.github.com/images/icons/emoji/unicode/1f4af.png?v5" alt="100" /></p>'],
        ];
    }

    /**
     * @dataProvider provideRenderCases
     */
    public function testRender($input, $output)
    {
        $this->assertSame("$output\n", $this->app->markdown->convertToHtml($input));
    }

    /**
     * @expectedException \AltThree\Emoji\Exceptions\FetchException
     * @expectedExceptionMessage Failed to fetch the emoji map.
     */
    public function testRepoFailure()
    {
        $this->app->singleton(RepositoryInterface::class, function () {
            return new class implements RepositoryInterface
            {
                public function get()
                {
                    throw new FetchException(new Exception());
                }
            };
        });

        $this->app->markdown->convertToHtml(':+1:');
    }
}
