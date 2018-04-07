<?php

/**
 * Created by PhpStorm.
 * User: soufianemit
 * Date: 06/04/18
 * Time: 10:58
 */

namespace AppBundle\EventListener;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Twig\Node\Expression\Binary\InBinary;

class AuthenticationSuccessListener
{

    protected $jwt_token_ttl;

    public function __construct($jwt_token_ttl)
    {
        $this->jwt_token_ttl=$jwt_token_ttl;
    }

    /**
     * @param AuthenticationSuccessEvent $event
     * @throws \Exception
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $expires_at =  new \DateTime();
        $interval ='PT'.$this->jwt_token_ttl.'S';
        $expires_at->add( new \DateInterval($interval));

        $data['data'] = array(
            'expiresAt' => $expires_at->format('Y-m-d\TH:i:s'),
        );

        $event->setData($data);
    }
}