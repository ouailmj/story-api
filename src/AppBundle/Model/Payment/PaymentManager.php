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

namespace AppBundle\Model\Payment;

use AppBundle\Entity\Event;
use AppBundle\Entity\EventPurchase;
use AppBundle\Entity\Payment;
use Symfony\Component\HttpFoundation\Request;

class PaymentManager
{
    /** @var PaymentProcessorInterface */
    protected $paymentProcessor;

    /**
     * PaymentManager constructor.
     *
     * @param PaymentProcessorInterface $paymentProcessor
     */
    public function __construct(PaymentProcessorInterface $paymentProcessor)
    {
        $this->paymentProcessor = $paymentProcessor;
    }

    /**
     * Creates Payment instance from Request.
     *
     * @param Request $request
     */
    public function createFromRequest(Request $request)
    {
        throw new \LogicException('Not yet implemented.');
    }

    public function createPaymentForEvent(Event $event, Payment $payment, array $coupons = [])
    {
        throw new \LogicException('Not yet implemented.');
    }

    protected function createPaymentForEventPurchase(EventPurchase $eventPurchase, Payment $payment, array $coupons = [])
    {
        throw new \LogicException('Not yet implemented.');
    }

    protected function applyCoupon($eventPurchase, $coupon)
    {
        throw new \LogicException('Not yet implemented.');
    }

    /**
     * @param Event $event
     *
     * @return bool
     */
    public function isTotalPayed(Event $event)
    {
        $amount = $this->TotalPayed($event);
        if ($amount >= $event->getEventPurchase()->getPlan()->getPrice()) {
            return true;
        }
        return false;
    }

    /**
     * @param Event $event
     *
     * @return int
     */
    public function TotalPayed(Event $event)
    {
        $amount = 0;
        foreach ($event->getEventPurchase()->getPayments() as $payment) {
            $amount += $payment->getTotalAmount();
        }

        return $amount;
    }
}
