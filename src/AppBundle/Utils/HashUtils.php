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

use Ramsey\Uuid\Uuid;

final class HashUtils
{
    private static $hashAlgorithm = 'sha256';

    private static function uuid()
    {
        $uuid = Uuid::uuid4();

        return $uuid->toString();
    }

    public static function hash($payload = null)
    {
        $payload = (empty($payload)) ? static::uuid() : $payload;

        return hash(static::$hashAlgorithm, $payload);
    }
}
