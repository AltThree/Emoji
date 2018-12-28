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

namespace AltThree\Emoji\Exceptions;

use Exception;

/**
 * This is the emoji fetch exception class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class FetchException extends Exception implements EmojiExceptionInterface
{
    /**
     * Create a new emoji fetch exception instance.
     *
     * @param \Exception $e
     *
     * @return void
     */
    public function __construct(Exception $e)
    {
        parent::__construct('Failed to fetch the emoji map.', 0, $e);
    }
}
