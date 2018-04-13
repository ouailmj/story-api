<?php

/*
 * This file is part of the Instan't App project.
 *
 * (c) Instan't App <contact@instant-app.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Developed by MIT <contact@mit-agency.com>
 *
 */

namespace AppBundle\Utils;

use Symfony\Component\HttpFoundation\Request;

final class RequestUtils
{
    public static function parseRequestContent(Request $request)
    {
        return json_decode($request->getContent(), true);
    }
}
