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

namespace AppBundle\NotificationSystem\Sender\Driver;


use AppBundle\Mailer\Mailer;
use AppBundle\NotificationSystem\Sender\DriverInterface;

class DriverFactory
{
    /**
     * @param string $driverType
     *
     * @return DriverInterface|null
     */
    public static function getDriver(string $driverType = 'email')
    {
        switch ($driverType) {
            case 'email':
                return new Mail();
            case 'push':
                return new Push();
            default:
                return null;
        }
    }
}
