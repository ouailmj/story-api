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

use AppBundle\Entity\Payment;
use Payum\Core\Payum;
use Symfony\Component\HttpFoundation\Request;

class PayumPaymentProcessor implements PaymentProcessorInterface
{
    /** @var Payum */
    protected $payum;

    /**
     * PaymentProcessor constructor.
     *
     * @param Payum $payum
     */
    public function __construct(Payum $payum)
    {
        $this->payum = $payum;
    }

    public function process(Payment $payment)
    {
        // TODO: Implement process() method.
    }

    public function processFromRequest(Request $request)
    {
        // TODO: Implement processFromRequest() method.
    }
}
