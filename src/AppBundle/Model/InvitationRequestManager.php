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

namespace AppBundle\Model;


use AppBundle\Entity\Event;
use AppBundle\Entity\InvitationRequest;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Doctrine\UserManager as FOSUserManager;

class InvitationRequestManager
{
    /** @var EntityManagerInterface */
    private $entityManager;
    /**
     * @var FOSUserManager
     */
    private $fosUserManager;

    /**
     * InvitationRequestManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param FOSUserManager $fosUserManager
     */
    public function __construct(EntityManagerInterface $entityManager,  FOSUserManager $fosUserManager)
    {
        $this->entityManager = $entityManager;
        $this->fosUserManager = $fosUserManager;
    }

    public function createInvitationRequest($email, Event $event, $flush = true){

        $user = $this->fosUserManager->findUserByUsernameOrEmail($email);
        $channels = $user instanceof User ? ['email'=> $email, 'push' => true] : ['email'=> $email, 'push' => false];

        $invitationRequest = new InvitationRequest();
        $invitationRequest->setChannels($channels);
        $invitationRequest->setEvent($event);
        $event->addInvitationRequest($invitationRequest);
        if($user instanceof User){
            $invitationRequest->setUser($user);
            $user->addInvitationRequest($invitationRequest);
        }

        $this->entityManager->persist($invitationRequest);
       if($flush) $this->entityManager->flush();

        return $invitationRequest;
    }
}