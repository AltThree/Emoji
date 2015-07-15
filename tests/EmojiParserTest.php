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
            [':+1:', '<p><img src="https://assets-cdn.github.com/images/icons/emoji/unicode/1f44d.png?v5" alt="" /></p>'],
            ['* :airplane:', "<ul>\n<li>\n<img src=\"https://assets-cdn.github.com/images/icons/emoji/unicode/2708.png?v5\" alt=\"\" />\n</li>\n</ul>"],
            ['foo bar baz: lol', '<p>foo bar baz: lol</p>'],
            [':+1:123', '<p>:+1:123</p>'],
            [':123123123:', '<p>:123123123:</p>'],
            [':+1 :', '<p>:+1 :</p>'],
            [':8ball: :100:', '<p><img src="https://assets-cdn.github.com/images/icons/emoji/unicode/1f3b1.png?v5" alt="" /> <img src="https://assets-cdn.github.com/images/icons/emoji/unicode/1f4af.png?v5" alt="" /></p>'],
        ];
    }

    /**
     * @dataProvider provideRenderCases
     */
    public function testRender($input, $output)
    {
        $this->assertSame("$output\n", $this->app->markdown->convertToHtml($input));
    }
}
