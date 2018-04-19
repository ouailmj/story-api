<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 19/04/2018
 * Time: 15:25
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
