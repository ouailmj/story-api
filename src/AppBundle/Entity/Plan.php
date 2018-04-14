<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Plan
 *
 * @ORM\Table(name="plan")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlanRepository")
 */
class Plan
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
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price = 0.0;

    /**
     * @var int
     *
     * @ORM\Column(name="maxEventDuration", type="integer")
     */
    private $maxEventDuration;

    /**
     * @var int
     *
     * @ORM\Column(name="maxUploads", type="integer")
     */
    private $maxUploads;

    /**
     * @var int
     *
     * @ORM\Column(name="maxGuests", type="integer")
     */
    private $maxGuests;

    /**
     * @var int
     *
     * @ORM\Column(name="maxAlbumLifeTime", type="integer")
     */
    private $maxAlbumLifeTime;

    /**
     * @var bool
     *
     * @ORM\Column(name="enableChallenges", type="boolean")
     */
    private $enableChallenges = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="enableGamification", type="boolean")
     */
    private $enableGamification = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="isFree", type="boolean")
     */
    private $isFree = false;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description = "";

    /**
     * @var EventPurchase [] | ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\EventPurchase", mappedBy="plan")
     */
    private $eventPurchases;


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
     * Set price.
     *
     * @param float $price
     *
     * @return Plan
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price.
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set maxEventDuration.
     *
     * @param int $maxEventDuration
     *
     * @return Plan
     */
    public function setMaxEventDuration($maxEventDuration)
    {
        $this->maxEventDuration = $maxEventDuration;

        return $this;
    }

    /**
     * Get maxEventDuration.
     *
     * @return int
     */
    public function getMaxEventDuration()
    {
        return $this->maxEventDuration;
    }

    /**
     * Set maxUploads.
     *
     * @param int $maxUploads
     *
     * @return Plan
     */
    public function setMaxUploads($maxUploads)
    {
        $this->maxUploads = $maxUploads;

        return $this;
    }

    /**
     * Get maxUploads.
     *
     * @return int
     */
    public function getMaxUploads()
    {
        return $this->maxUploads;
    }

    /**
     * Set maxGuests.
     *
     * @param int $maxGuests
     *
     * @return Plan
     */
    public function setMaxGuests($maxGuests)
    {
        $this->maxGuests = $maxGuests;

        return $this;
    }

    /**
     * Get maxGuests.
     *
     * @return int
     */
    public function getMaxGuests()
    {
        return $this->maxGuests;
    }

    /**
     * Set maxAlbumLifeTime.
     *
     * @param int $maxAlbumLifeTime
     *
     * @return Plan
     */
    public function setMaxAlbumLifeTime($maxAlbumLifeTime)
    {
        $this->maxAlbumLifeTime = $maxAlbumLifeTime;

        return $this;
    }

    /**
     * Get maxAlbumLifeTime.
     *
     * @return int
     */
    public function getMaxAlbumLifeTime()
    {
        return $this->maxAlbumLifeTime;
    }

    /**
     * Set enableChallenges.
     *
     * @param bool $enableChallenges
     *
     * @return Plan
     */
    public function setEnableChallenges($enableChallenges)
    {
        $this->enableChallenges = $enableChallenges;

        return $this;
    }

    /**
     * Get enableChallenges.
     *
     * @return bool
     */
    public function getEnableChallenges()
    {
        return $this->enableChallenges;
    }

    /**
     * Set enableGamification.
     *
     * @param bool $enableGamification
     *
     * @return Plan
     */
    public function setEnableGamification($enableGamification)
    {
        $this->enableGamification = $enableGamification;

        return $this;
    }

    /**
     * Get enableGamification.
     *
     * @return bool
     */
    public function getEnableGamification()
    {
        return $this->enableGamification;
    }

    /**
     * Set isFree.
     *
     * @param bool $isFree
     *
     * @return Plan
     */
    public function setIsFree($isFree)
    {
        $this->isFree = $isFree;

        return $this;
    }

    /**
     * Get isFree.
     *
     * @return bool
     */
    public function getIsFree()
    {
        return $this->isFree;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Plan
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Plan
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
     * Add eventPurchases
     *
     * @param EventPurchase $eventPurchase
     * @return $this
     */
    public function addEventPurchases(EventPurchase $eventPurchase)
    {
        $this->eventPurchases[] = $eventPurchase;

        return $this;
    }


    /**
     * Remove eventPurchases.
     *
     * @param \AppBundle\Entity\EventPurchase $eventPurchase
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeEventPurchases(EventPurchase $eventPurchase)
    {
        return $this->eventPurchases->removeElement($eventPurchase);
    }


    /**
     * Get eventPurchases.
     *
     * @return EventPurchase[]|ArrayCollection
     */
    public function getEventPurchases()
    {
        return $this->eventPurchases;
    }
}
