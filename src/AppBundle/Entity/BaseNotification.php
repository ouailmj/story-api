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

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Templating\EngineInterface;

/**
 * Class BaseNotification.
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
    protected $channels = ['email' => '', 'push' => false];

    /**
     * @var string
     *
     *  @ORM\Column(name="message", type="text")
     */
    protected $message = '';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="read_at", type="datetime", nullable=true)
     */
    protected $readAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="send_at", type="datetime", nullable=true)
     */
    protected $sendAt = null;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    protected $deleteOnRead = false;

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

    /**
     * @return \DateTime
     */
    public function getReadAt()
    {
        return $this->readAt;
    }

    /**
     * @param \DateTime $readAt
     */
    public function setReadAt(\DateTime $readAt)
    {
        $this->readAt = $readAt;
    }

    /**
     * @return bool
     */
    public function isDeleteOnRead(): bool
    {
        return $this->deleteOnRead;
    }

    /**
     * @param bool $deleteOnRead
     */
    public function setDeleteOnRead(bool $deleteOnRead)
    {
        $this->deleteOnRead = $deleteOnRead;
    }

    /**
     * @return \DateTime
     */
    public function getSendAt()
    {
        return $this->sendAt;
    }

    /**
     * @param \DateTime $sendAt
     */
    public function setSendAt(\DateTime $sendAt)
    {
        $this->sendAt = $sendAt;
    }

    public function formatMessageToMail(EngineInterface $templateEngine, UrlGeneratorInterface $router)
    {
                // TODO: change url ...
                $url = $router->generate('fos_user_registration_register', array(),  UrlGeneratorInterface::ABSOLUTE_URL );

                return $templateEngine->render('mail/notification/invitation_request.html.twig', [
                    'event_creator' => $this->triggeredBy->getUsername(),
                    'message' => $this->message,
                    'link' => $url,
                ]);
    }

    public function formatMessageToText()
    {
        // TODO: Implement formatMessageToText() method.
    }
}
