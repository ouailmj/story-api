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

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Timestampable;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Challenge.
 *
 *
 * @ORM\Table(name="challenge")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ChallengeRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ApiResource()
 */
class Challenge implements Timestampable
{
    use TimestampableEntity;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description = '';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="plannedAt", type="datetime", nullable=true)
     */
    private $plannedAt;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isLaunched = false;

    /**
     * @var Event
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Event", inversedBy="challenges" )
     */
    private $event;

    /**
     * @var ChallengeNotification
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\ChallengeNotification",  inversedBy="challenge", cascade={"persist", "remove"})
     */
    private $notification;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Challenge
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set plannedAt.
     *
     * @param \DateTime $plannedAt
     *
     * @return Challenge
     */
    public function setPlannedAt($plannedAt)
    {
        $this->plannedAt = $plannedAt;

        return $this;
    }

    /**
     * Get plannedAt.
     *
     * @return \DateTime
     */
    public function getPlannedAt()
    {
        return $this->plannedAt;
    }

    /**
     * Set isLaunched.
     *
     * @param bool $isLaunched
     *
     * @return Challenge
     */
    public function setIsLaunched($isLaunched)
    {
        $this->isLaunched = $isLaunched;

        return $this;
    }

    /**
     * Get isLaunched.
     *
     * @return bool
     */
    public function getIsLaunched()
    {
        return $this->isLaunched;
    }

    /**
     * Set event.
     *
     * @param Event $event
     *
     * @return Challenge
     */
    public function setEvent($event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event.
     *
     * @return Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @return ChallengeNotification
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * @param ChallengeNotification $notification
     */
    public function setNotification(ChallengeNotification $notification)
    {
        $this->notification = $notification;
    }
}
