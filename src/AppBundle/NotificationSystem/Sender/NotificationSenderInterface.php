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

namespace AppBundle\NotificationSystem\Sender;

use AppBundle\Entity\BaseNotification as Notification;
use Doctrine\Common\Collections\ArrayCollection;

interface NotificationSenderInterface
{
    /**
     * @param Notification $notification
     *
     * @return mixed
     */
    public function send(Notification $notification);

    /**
     * @param Notification $notification
     *
     * @return mixed
     */
    public function sendNow(Notification $notification);

    /**
     * @param Notification[]|ArrayCollection $notifications
     *
     * @return mixed
     */
    public function sendBulk(ArrayCollection $notifications);

    /**
     * @param Notification $notification
     *
     * @return mixed
     */
    public function sendScheduled(Notification $notification);

    /**
     * @param string $channelName
     *
     * @return DriverInterface | null
     */
    public function driver(string $channelName);
}
