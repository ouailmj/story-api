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

/**
 * Plan.
 *
 * @ORM\Table(name="plan")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlanRepository")
 */
class Plan
{
    const FREE_PLAN_KEY = 'free';
    const STARTER_PLAN_KEY = 'starter';
    const LUXURY_PLAN_KEY = 'luxury';
    const PREMIUM_PLAN_KEY = 'premium';

    public static $supportedPlanKeys = [
        self::FREE_PLAN_KEY => self::FREE_PLAN_KEY,
        self::STARTER_PLAN_KEY => self::STARTER_PLAN_KEY,
        self::LUXURY_PLAN_KEY => self::LUXURY_PLAN_KEY,
        self::PREMIUM_PLAN_KEY => self::PREMIUM_PLAN_KEY,
    ];

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
     * Max event duration in seconds.
     *
     * @var int
     *
     * @ORM\Column(name="maxEventDuration", type="integer")
     */
    private $maxEventDuration = 6 * 3600;

    /**
     * Maximum media uploads.
     *
     * @var int
     *
     * @ORM\Column(name="maxUploads", type="integer")
     */
    private $maxUploads = 200;

    /**
     * @var int
     *
     * @ORM\Column(name="maxGuests", type="integer")
     */
    private $maxGuests = 20;

    /**
     * @var int
     *
     * @ORM\Column(name="maxAlbumLifeTime", type="integer")
     */
    private $maxAlbumLifeTime = 24 * 3600;

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
    private $isFree = true;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name = 'Free Plan';

    /**
     * @var string
     *
     * @ORM\Column(unique=true, type="string", length=20)
     */
    private $planKey = self::FREE_PLAN_KEY;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description = '';

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
     * Add eventPurchases.
     *
     * @param EventPurchase $eventPurchase
     *
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

    /**
     * @return string
     */
    public function getPlanKey(): string
    {
        return $this->planKey;
    }

    /**
     * @param string $planKey
     *
     * @return Plan
     */
    public function setPlanKey(string $planKey): self
    {
        $this->planKey = $planKey;

        return $this;
    }

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->eventPurchases = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add eventPurchase.
     *
     * @param \AppBundle\Entity\EventPurchase $eventPurchase
     *
     * @return Plan
     */
    public function addEventPurchase(\AppBundle\Entity\EventPurchase $eventPurchase)
    {
        $this->eventPurchases[] = $eventPurchase;

        return $this;
    }

    /**
     * Remove eventPurchase.
     *
     * @param \AppBundle\Entity\EventPurchase $eventPurchase
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeEventPurchase(\AppBundle\Entity\EventPurchase $eventPurchase)
    {
        return $this->eventPurchases->removeElement($eventPurchase);
    }
}
