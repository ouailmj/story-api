<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;

/**
 * EventPurchase
 *
 * @ApiResource
 * @ORM\Table(name="event_purchase")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EventPurchaseRepository")
 */
class EventPurchase
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
     * @ORM\Column(name="quota", type="float", length=255)
     */
    private $quota;

    /**
     * @var string
     *
     * @ORM\Column(name="currencyCode", type="string", length=255)
     */
    private $currencyCode = 'EUR';

    /**
     * @var Plan
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Plan", inversedBy="eventPurchases" )
     * @ApiSubresource()
     */
    private $plan;

    /**
     * @var Event
     *
     * @ORM\OneToOne(targetEntity="Event")
     * @ApiSubresource()
     */
    private $event;

    /**
     * @var Payment [] | ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Payment", mappedBy="eventPurchase")
     */
    private $payments;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;


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
     * Set quota.
     *
     * @param float $quota
     *
     * @return EventPurchase
     */
    public function setQuota($quota)
    {
        $this->quota = $quota;

        return $this;
    }

    /**
     * Get quota.
     *
     * @return float
     */
    public function getQuota()
    {
        return $this->quota;
    }

    /**
     * Set currencyCode.
     *
     * @param string $currencyCode
     *
     * @return EventPurchase
     */
    public function setCurrencyCode($currencyCode)
    {
        $this->currencyCode = $currencyCode;

        return $this;
    }

    /**
     * Get currencyCode.
     *
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * Set plan.
     *
     * @param Plan $plan
     *
     * @return EventPurchase
     */
    public function setPlan($plan)
    {
        $this->plan = $plan;

        return $this;
    }

    /**
     * Get plan.
     *
     * @return Plan
     */
    public function getPlan()
    {
        return $this->plan;
    }

    /**
     * Set event.
     *
     * @param Event $event
     *
     * @return EventPurchase
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
     * Add payments
     *
     * @param Payment $payment
     * @return $this
     */
    public function addChallenges( Payment $payment)
    {
        $this->payments[] = $payment;

        return $this;
    }


    /**
     * Remove payments.
     *
     * @param \AppBundle\Entity\Payment $payment
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeChallenges(Payment $payment)
    {
        return $this->payments->removeElement($payment);
    }

    /**
     * Get payments.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPayments()
    {
        return $this->payments;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return EventPurchase
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
