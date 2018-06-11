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
use AppBundle\Entity\MemberShip;
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
     * @var NotificationManager
     */
    private $notificationManager;

    /**
     * InvitationRequestManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param FOSUserManager         $fosUserManager
     * @param NotificationManager    $notificationManager
     */
    public function __construct(EntityManagerInterface $entityManager, FOSUserManager $fosUserManager, NotificationManager $notificationManager)
    {
        $this->entityManager = $entityManager;
        $this->fosUserManager = $fosUserManager;
        $this->notificationManager = $notificationManager;
    }

    /**
     * @param $email
     * @param Event $event
     * @param bool  $flush
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @return InvitationRequest
     */
    public function createInvitationRequest($email, Event $event, $flush = true)
    {

        $user = $this->fosUserManager->findUserByUsernameOrEmail($email);
        $channels = $user instanceof User ? ['email' => $email, 'push' => true] : ['email' => $email, 'push' => false];

        $invitationRequest = new InvitationRequest();
        $invitationRequest->setChannels($channels);
        $invitationRequest->setEvent($event);
        $event->addInvitationRequest($invitationRequest);
        if ($user instanceof User) {
            $invitationRequest->setUser($user);
            $user->addInvitationRequest($invitationRequest);
        }

        $this->notificationManager->createNotificationForInvitationRequest($invitationRequest, $event->getCreatedBy(), false, false);

        $this->entityManager->persist($invitationRequest);
        if ($flush) {
            $this->entityManager->flush();
        }

        return $invitationRequest;
    }

    /**
     * @param User $user
     * @param bool $isCanceled
     * @param bool $isAccepted
     * @return array
     */
    public function getInvitationRequestByStatus(User $user, $isCanceled = false , $isAccepted = false)
    {
        return  $this->entityManager->getRepository('AppBundle:InvitationRequest')->findBy([
            'user' => $user,
            'isCanceled' => $isCanceled,
            'isAccepted' => $isAccepted,
        ]);
    }

    /**
     * @param User $user
     * @param InvitationRequest $invitationRequest
     * @param bool $flush
     */
    public function acceptInvitation(User $user, InvitationRequest $invitationRequest, $flush = true)
    {
        $invitationRequest->setIsAccepted(true);
        $memberShip = new MemberShip();
        $memberShip->setEvent($invitationRequest->getEvent());
        $memberShip->setMember($user);
        $memberShip->setCreatedAt(new \DateTime());
        $invitationRequest->getEvent()->addEventMemberShip($memberShip);
        $user->addEventMemberShip($memberShip);
        $this->entityManager->persist($memberShip);
        if ($flush) {
            $this->entityManager->flush();
        }
    }

    /**
     * @param InvitationRequest $invitationRequest
     * @param bool $flush
     */
    public function cancelInvitation(InvitationRequest $invitationRequest, $flush = true)
    {
        $invitationRequest->setIsCanceled(true);
        if ($flush) {
            $this->entityManager->flush();
        }
    }

    /**
     * @param Event $event
     * @return array
     */
    public function getInvitationRequestByEvent(Event $event)
    {
        return  $this->entityManager->getRepository('AppBundle:InvitationRequest')->findBy([
            'event' => $event,
        ]);
    }

    /**
     * @param InvitationRequest $invitationRequest
     * @param bool $flush
     */
    public function deleteInvitationRequest(InvitationRequest $invitationRequest, $flush = true)
    {
        if($invitationRequest->getUser() !== null) $invitationRequest->getUser()->getInvitationRequests()->removeElement($invitationRequest);
        $invitationRequest->getEvent()->getInvitationRequests()->removeElement($invitationRequest);
        $this->entityManager->remove($invitationRequest);
        if ($flush) {
            $this->entityManager->flush();
        }
    }
}
