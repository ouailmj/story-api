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

class PaymentUtils
{
    protected static $currency = 'EUR';

    protected static $currencyCode = '€';

    /**
     * @return string
     */
    public static function currency()
    {
        return static::$currency;
    }

    /**
     * @return string
     */
    public static function currencySymbol()
    {
        return static::$currencyCode;
    }
}
