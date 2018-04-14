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

namespace AppBundle\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class AuthenticationSuccessListener
{
    protected $jwtTokenTTL;

    public function __construct($jwtTokenTTL)
    {
        $this->jwtTokenTTL = $jwtTokenTTL;
    }

    /**
     * @param AuthenticationSuccessEvent $event
     *
     * @throws \Exception
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $expiresAt = new \DateTime();
        $interval = 'PT'.$this->jwtTokenTTL.'S';
        $expiresAt->add(new \DateInterval($interval));

        $data['data'] = [
            'expiresAt' => $expiresAt->format('Y-m-d\TH:i:s'),
        ];

        $event->setData($data);
    }
}
