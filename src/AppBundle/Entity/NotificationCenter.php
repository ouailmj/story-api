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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Class NotificationCenter.
 *
 * @ORM\Table(name="notification_center")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NotificationCenterRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class NotificationCenter
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
     * @var BaseNotification[] | ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\BaseNotification", mappedBy="notificationCenter")
     */
    protected $notifications;

    /**
     * @var User
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User",  inversedBy=" notificationCenter", cascade={"persist", "remove"})
     */
    protected $receiver;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return BaseNotification[]|ArrayCollection
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * Add notification.
     *
     * @param \AppBundle\Entity\BaseNotification $notification
     *
     * @return NotificationCenter
     */
    public function addNotification(BaseNotification $notification)
    {
        $this->notifications[] = $notification;

        return $this;
    }

    /**
     * Remove notification.
     *
     * @param \AppBundle\Entity\BaseNotification $notification
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeNotification(BaseNotification $notification)
    {
        return $this->notifications->removeElement($notification);
    }

    /**
     * @return User
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * @param User $receiver
     */
    public function setReceiver(User $receiver)
    {
        $this->receiver = $receiver;
    }
}
