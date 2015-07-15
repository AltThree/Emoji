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

use League\CommonMark\ContextInterface;
use League\CommonMark\Inline\Element\Image;
use League\CommonMark\Inline\Parser\AbstractInlineParser;
use League\CommonMark\InlineParserContext;

/**
 * This is the emoji parser class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class EmojiParser extends AbstractInlineParser
{
    /**
     * The emoji mappings.
     *
     * @var string[]
     */
    protected $map;

    /**
     * Create a new emoji parser instance.
     *
     * @param string[] $map
     *
     * @return void
     */
    public function __construct(array $map)
    {
        $this->map = $map;
    }

    /**
     * Get the characters that must be matched.
     *
     * @return string[]
     */
    public function getCharacters()
    {
        return [':'];
    }

    /**
     * Parse a line and determine if it contains an emoji.
     *
     * If it does, then we do the necessary.
     *
     * @return bool
     */
    public function parse(ContextInterface $context, InlineParserContext $inlineContext)
    {
        $cursor = $inlineContext->getCursor();

        $previous = $cursor->peek(-1);
        if ($previous !== null && $previous !== ' ') {
            return false;
        }

        $saved = $cursor->saveState();

        $cursor->advance();

        $handle = $cursor->match('/^[a-z0-9\+\-_]+:/');

        if (!$handle) {
            $cursor->restoreState($saved);

            return false;
        }

        $next = $cursor->peek(0);

        if ($next !== null && $next !== ' ') {
            $cursor->restoreState($saved);

            return false;
        }

        $key = substr($handle, 0, -1);

        if (!array_key_exists($key, $this->map)) {
            $cursor->restoreState($saved);

            return false;
        }

        $inlineContext->getInlines()->add(new Image($this->map[$key], $key));

        return true;
    }
}
