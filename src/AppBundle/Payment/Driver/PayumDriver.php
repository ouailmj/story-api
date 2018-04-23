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

namespace AppBundle\Payment\Driver;

use AppBundle\Entity\Payment;

class PayumDriver implements PaymentDriverInterface
{
    /**
     * @param Payment $payment
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function process(Payment $payment)
    {
        // TODO: Implement process() method.
    }
}
