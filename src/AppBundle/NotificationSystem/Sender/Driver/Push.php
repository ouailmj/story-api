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

use AppBundle\Entity\BaseNotification as Notification;
use AppBundle\NotificationSystem\Sender\DriverInterface;

class Push implements DriverInterface
{
    public function handle(Notification $notification, array $params)
    {
        // TODO: Implement handle() method.
    }
}
