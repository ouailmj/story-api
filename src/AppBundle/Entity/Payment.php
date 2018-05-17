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
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Payum\Core\Model\BankAccountInterface;
use Payum\Core\Model\CreditCardInterface;
use Payum\Core\Model\DirectDebitPaymentInterface;
use Payum\Core\Model\PaymentInterface;

/**
 * Payment.
 *
 *
 * @ORM\Table(name="payment")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PaymentRepository")
 * @ApiResource()
 */
class Payment implements PaymentInterface, DirectDebitPaymentInterface
{
    use PaymentTrait, TimestampableEntity;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var EventPurchase
     *
     * @ORM\ManyToOne(targetEntity="EventPurchase", inversedBy="payments")
     */
    private $eventPurchase;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="payments")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $number;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $clientEmail;

    /**
     * @var string
     */
    protected $clientId;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $totalAmount;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $currencyCode;

    /**
     * @var array
     *
     * @ORM\Column(type="array", nullable=true)
     */
    protected $details;

    /**
     * @var CreditCardInterface|null
     */
    protected $creditCard;

    /**
     * @var BankAccountInterface|null
     */
    protected $bankAccount;

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
     * @return EventPurchase
     */
    public function getEventPurchase()
    {
        return $this->eventPurchase;
    }

    /**
     * @param EventPurchase $eventPurchase
     */
    public function setEventPurchase(EventPurchase $eventPurchase)
    {
        $this->eventPurchase = $eventPurchase;
    }

    /**
     * Set user.
     *
     * @param \AppBundle\Entity\User|null $user
     *
     * @return Payment
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \AppBundle\Entity\User|null
     */
    public function getUser()
    {
        return $this->user;
    }

    public function __construct()
    {
        $this->details = [];
    }
}
