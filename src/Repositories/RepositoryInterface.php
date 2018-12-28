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

namespace AltThree\Emoji\Repositories;

/**
 * This is the emoji repository interface.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
interface RepositoryInterface
{
    /**
     * Fetch the emoji map.
     *
     * @throws \AltThree\Emoji\Exceptions\FetchException
     *
     * @return array
     */
    public function get();
}
