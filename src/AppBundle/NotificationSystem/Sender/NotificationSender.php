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
use AppBundle\Mailer\Mailer;
use AppBundle\NotificationSystem\Sender\Driver\DriverFactory;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Ldap\Exception\DriverNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class NotificationSender implements NotificationSenderInterface
{

    /** @var Mailer */
    private $mailer;

    /**
     * @var UrlGeneratorInterface
     */
    protected $router;

    /**
     * Mail constructor.
     * @param Mailer                    $mailer
     * @param UrlGeneratorInterface     $router
     */
    public function __construct(Mailer $mailer, UrlGeneratorInterface $router)
    {
        $this->mailer = $mailer;
        $this->router = $router;
    }

    /**
     * @param Notification $notification
     * @return mixed
     */
    public function send(Notification $notification)
    {
        if($notification->getSendAt() === null) return $this->sendNow($notification);
        return $this->sendScheduled($notification);
    }

    /**
     * @param Notification $notification
     * @return array|mixed
     */
    public function sendNow(Notification $notification)
    {
        $channels = $notification->getChannels();
        foreach (array_keys($channels) as $key) {
            DriverFactory::getDriver($key)->handle($notification, $this->getParams());
        }
        return $channels;
    }

    /**
     * @param string $channelName
     * @return DriverInterface|null
     */
    public function driver(string $channelName)
    {
        $driver =  DriverFactory::getDriver($channelName);
        if($driver === null)  throw new DriverNotFoundException();
        return $driver;
    }

    /**
     * @param ArrayCollection $notifications
     * @return mixed|void
     */
    public function sendBulk(ArrayCollection $notifications)
    {
       foreach ($notifications as $notification){
           if($notification instanceof Notification) $this->send($notification);
       }
    }

    public function sendScheduled(Notification $notification)
    {
        // TODO: Implement sendScheduled() method.
        return null;
    }

    /**
     * @return array
     */
    private function getParams()
    {
        return
            $params = array(
                'mailer' => $this->mailer,
                'router' => $this->router,
            );
    }
}