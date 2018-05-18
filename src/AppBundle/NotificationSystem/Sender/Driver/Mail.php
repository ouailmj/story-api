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
use AppBundle\Entity\ChallengeNotification;
use AppBundle\Mailer\Mailer;
use AppBundle\NotificationSystem\Sender\DriverInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Mail  implements DriverInterface
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
     * @return mixed|void
     */
    public function handle(Notification $notification)
    {
        if($notification instanceof ChallengeNotification) return;
        $body = $notification->formatMessageToMail($this->mailer->getTemplateEngine(), $this->router);
        $this->mailer->sendEmail($body, $notification->getChannels()['email'], "Instan't Notification");
        return;
    }


}