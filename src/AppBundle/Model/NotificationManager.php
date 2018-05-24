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

use AppBundle\Entity\Challenge;
use AppBundle\Entity\ChallengeNotification;
use AppBundle\Entity\InvitationRequest;
use AppBundle\Entity\InvitationRequestNotification;
use AppBundle\Entity\User;
use AppBundle\NotificationSystem\Sender\NotificationSender;
use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Doctrine\UserManager as FOSUserManager;

class NotificationManager
{
    /**
     * @var NotificationSender
     */
    private $notificationSender;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var FOSUserManager
     */
    private $fosUserManager;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * NotificationManager constructor.
     *
     * @param NotificationSender $notificationSender
     * @param UserManager        $userManager
     * @param FOSUserManager     $fosUserManager
     * @param EntityManager      $em
     */
    public function __construct(NotificationSender $notificationSender, UserManager $userManager, FOSUserManager $fosUserManager, EntityManager $em)
    {
        $this->notificationSender = $notificationSender;
        $this->userManager = $userManager;
        $this->fosUserManager = $fosUserManager;
        $this->em = $em;
    }

    /**
     * @param InvitationRequest $invitationRequest
     * @param User              $triggeredBy
     * @param bool              $deleteOnRead
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @return InvitationRequestNotification
     */
    public function createNotificationForInvitationRequest(InvitationRequest $invitationRequest, User $triggeredBy, $deleteOnRead=false)
    {

        $notification = new InvitationRequestNotification();
        $notification->setInvitationRequest($invitationRequest);
        $notification->setTriggeredBy($triggeredBy);
        $notification->setChannels($invitationRequest->getChannels());
        $notification->setDeleteOnRead($deleteOnRead);
        // TODO: change content the message
        $notification->setMessage('invitation request for an event ');

        if ($notification->getChannels()['push']) {
            /** @var User $receiver */
            $receiver = $this->fosUserManager->findUserByUsernameOrEmail($notification->getChannels()['email']);
            $receiver->addNotification($notification);
            $notification->setNotificationCenter($receiver->getNotificationCenter());
        }
        $this->notificationSender->send($notification);
        $this->em->persist($notification);
        $this->em->flush();

        return $notification;
    }

    /**
     * @param Challenge $challenge
     * @param User      $triggeredBy
     * @param bool      $deleteOnRead
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @return array
     */
    public function createNotificationsForChallenge(Challenge $challenge, User $triggeredBy, $deleteOnRead = false)
    {
        $notifications = [];
        foreach ($challenge->getEvent()->getEventMemberShips() as $eventMemberShip)
        {

            $notification = new ChallengeNotification();
            $notification->setChallenge($challenge);
            $notification->setChannels(['email' => $eventMemberShip->getMember()->getEmail(), 'push' => true]);
            $notification->setTriggeredBy($triggeredBy);
            $notification->setDeleteOnRead($deleteOnRead);
            $notification->setSendAt($challenge->getPlannedAt());
            $notification->setMessage($challenge->getDescription());

            $notifications[] = $notification;

            /** @var User $receiver */
            $receiver = $this->fosUserManager->findUserByUsernameOrEmail($notification->getChannels()['email']);
            $receiver->addNotification($notification);
            $notification->setNotificationCenter($receiver->getNotificationCenter());

            $this->notificationSender->send($notification);

            $this->em->persist($notification);
            $this->em->flush();
        }

        return $notifications;
    }
}
