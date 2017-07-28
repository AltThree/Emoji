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

return [

    /*
    |--------------------------------------------------------------------------
    | GitHub Token
    |--------------------------------------------------------------------------
    |
    | Here you may get us to use your personal github access token to increase
    | your rate limit while contacting GitHub's API.
    |
    */

    'token' => env('GITHUB_TOKEN', null),

];
