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

use AltThree\Emoji\Repositories\RepositoryInterface;
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
     * The emoji repo.
     *
     * @var \AltThree\Emoji\Repositories\RepositoryInterface
     */
    protected $repo;

    /**
     * The emoji mappings.
     *
     * @var string[]|null
     */
    protected $map;

    /**
     * Create a new emoji parser instance.
     *
     * @param \AltThree\Emoji\Repositories\RepositoryInterface $repo
     *
     * @return void
     */
    public function __construct(RepositoryInterface $repo)
    {
        $this->repo = $repo;
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
     * @param \League\CommonMark\InlineParserContext $inlineContext
     *
     * @throws \AltThree\Emoji\Exceptions\FetchException
     *
     * @return bool
     */
    public function parse(InlineParserContext $inlineContext)
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

        if ($this->map === null) {
            $this->map = $this->repo->get();
        }

        if (!array_key_exists($key, $this->map)) {
            $cursor->restoreState($saved);

            return false;
        }

        $inline = new Image($this->map[$key], $key);
        $inline->data['attributes'] = ['class' => 'emoji', 'data-emoji' => $key];
        $inlineContext->getContainer()->appendChild($inline);

        return true;
    }
}
