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

use AppBundle\Entity\Payment;
use AppBundle\Payment\Driver\PaymentDriverInterface;

class PaymentProcessor
{
    /**
     * @var PaymentDriverInterface
     */
    protected $driver;

    /**
     * PaymentProcessor constructor.
     *
     * @param PaymentDriverInterface $driver
     */
    public function __construct(PaymentDriverInterface $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @param Payment $payment
     *
     * @throws \AppBundle\Exception\PaymentException
     * @throws \Exception
     */
    public function process(Payment $payment)
    {
        $this->driver->process($payment);
    }
}
