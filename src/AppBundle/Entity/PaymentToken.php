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

use Doctrine\ORM\Mapping as ORM;
use Payum\Core\Model\Token;

/**
 * PaymentToken.
 *
 * @ORM\Table(name="payment_token")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PaymentTokenRepository")
 */
class PaymentToken extends Token
{
}
