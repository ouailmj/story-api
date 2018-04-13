<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;

/**
 * Challenge
 *
 * @ApiResource
 * @ORM\Table(name="challenge")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ChallengeRepository")
 */
class Challenge
{
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
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="plannedAt", type="datetime")
     */
    private $plannedAt;

    /**
     * @var bool
     *
     * @ORM\Column(name="isLaunched", type="boolean")
     */
    private $isLaunched;

    /**
     * @var Event
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Event", inversedBy="challenges" )
     * @ApiSubresource()
     */
    private $event;


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
}
