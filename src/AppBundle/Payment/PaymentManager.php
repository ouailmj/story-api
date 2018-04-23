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

namespace AppBundle\Payment;

use AppBundle\Entity\Event;
use AppBundle\Entity\EventPurchase;
use AppBundle\Entity\Payment;
use Symfony\Component\HttpFoundation\Request;

class PaymentManager
{
    /** @var PaymentProcessor */
    protected $paymentProcessor;

    /**
     * PaymentManager constructor.
     *
     * @param PaymentProcessor $paymentProcessor
     */
    public function __construct(PaymentProcessor $paymentProcessor)
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

    public function payForEvent(Event $event, Payment $payment, array $coupons = [])
    {
        throw new \LogicException('Not yet implemented.');
    }

    protected function payForEventPurchase(EventPurchase $eventPurchase, Payment $payment, array $coupons = [])
    {
        throw new \LogicException('Not yet implemented.');
    }

    protected function applyCoupon($eventPurchase, $coupon)
    {
        throw new \LogicException('Not yet implemented.');
    }
}
