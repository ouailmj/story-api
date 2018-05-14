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

namespace AppBundle\Entity;


use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class BaseNotification
 * @package AppBundle\Entity
 *
 * @ORM\Table(name="notification")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BaseNotificationRepository")
 *
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *     "notification"="BaseNotification",
 *      "invitation_request_notification"="InvitationRequestNotification",
 *      "challenge_notification"="ChallengeNotification",
 *     })
 *
 * @ORM\HasLifecycleCallbacks()
 *
 */
abstract class BaseNotification implements NotificationInterface
{
    use TimestampableEntity;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var array
     *
     * @ORM\Column(name="channels", type="array")
     */
    protected $channels = [ 'email'=> '', 'push'=> false ];

    /**
     * @var string
     *
     *  @ORM\Column(name="message", type="string")
     */
    protected $message = '';

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="triggeredNotifications", cascade={"persist", "remove"})
     */
    protected $triggeredBy;

    /**
     * @var NotificationCenter
     * @ORM\ManyToOne(targetEntity="NotificationCenter", inversedBy="notifications", cascade={"persist", "remove"})
     */
    protected $notificationCenter;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getChannels()
    {
        return $this->channels;
    }

    /**
     * @param array $channels
     */
    public function setChannels(array $channels)
    {
        $this->channels = $channels;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    /**
     * @return User
     */
    public function getTriggeredBy()
    {
        return $this->triggeredBy;
    }

    /**
     * @param User $triggeredBy
     */
    public function setTriggeredBy(User $triggeredBy)
    {
        $this->triggeredBy = $triggeredBy;
    }

    /**
     * @return NotificationCenter
     */
    public function getNotificationCenter()
    {
        return $this->notificationCenter;
    }

    /**
     * @param NotificationCenter $notificationCenter
     */
    public function setNotificationCenter(NotificationCenter $notificationCenter)
    {
        $this->notificationCenter = $notificationCenter;
    }


    public function send()
    {
        // TODO: Implement send() method.
    }

    public function formatMessageToMail()
    {
        // TODO: Implement formatMessageToMail() method.
    }

    public function formatMessageToText()
    {
        // TODO: Implement formatMessageToText() method.
    }

    public function sendBulk()
    {
        // TODO: Implement sendBulk() method.
    }


}