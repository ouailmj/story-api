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

use AppBundle\AppEvents;
use AppBundle\Entity\Event;
use AppBundle\Entity\EventPurchase;
use AppBundle\Entity\Media;
use AppBundle\Entity\Payment;
use AppBundle\Entity\Plan;
use AppBundle\Entity\User;
use AppBundle\Event\NewMediaUploadedEvent;
use AppBundle\Model\Payment\PaymentManager;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class EventManager
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var MediaManager */
    private $mediaManager;

    /** @var UserManager */
    private $userManager;

    /** @var EventDispatcher */
    private $eventDispatcher;

    /** @var PaymentManager */
    private $paymentManager;

    /**
     * EventManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param MediaManager           $mediaManager
     * @param UserManager            $userManager
     * @param PaymentManager            $paymentManager
     */
    public function __construct(EntityManagerInterface $entityManager, MediaManager $mediaManager, UserManager $userManager, PaymentManager $paymentManager, EventDispatcher $eventDispatcher)
    {
        $this->entityManager = $entityManager;
        $this->mediaManager = $mediaManager;
        $this->userManager = $userManager;
        $this->paymentManager = $paymentManager;

        $this->eventDispatcher = $eventDispatcher;
    }


    public function deleteUserEvents(User $user)
    {
    }

    public function createEvent(Plan $plan, Event $event, User $createdBy = null)
    {
        $eventPurchase = ($event->getEventPurchase() === null) ? new EventPurchase() : $event->getEventPurchase();
        $eventPurchase->setPlan($plan);
        $eventPurchase->setQuota($plan->getMaxUploads());
        $event->setEventPurchase($eventPurchase);
        $createdBy = ($createdBy instanceof User) ? $createdBy : $this->getCreatedBy();
        $event->setCreatedBy($createdBy);

        $startsAt = Carbon::tomorrow($createdBy->getTimeZoneInstance());
        $event->setStartsAt(Carbon::tomorrow($createdBy->getTimeZoneInstance()));
        $endsAt = $startsAt->addRealSeconds($plan->getMaxEventDuration());
        $event->setEndsAt($endsAt);
        $event->setExpiresAt($endsAt->addRealSeconds($plan->getMaxAlbumLifeTime()));

        $this->entityManager->persist($event);
        $this->entityManager->flush();

        return $event;
    }

    public function createEventWithFreePlan(User $createdBy = null, Event $event)
    {
        return $this->createEvent($this->entityManager->getRepository(Plan::class)->getFreePlan(), $event, $createdBy);
    }

    public function createEventWithStarterPlan(User $createdBy = null, Event $event)
    {
        return $this->createEvent($this->entityManager->getRepository(Plan::class)->getStarterPlan(), $event, $createdBy);
    }

    /**
     * @param int $eventId
     * @param Media $media
     * @param User|null $by
     * @return Event
     * @throws \Doctrine\ORM\EntityNotFoundException
     */
    public function addMedia(int $eventId, Media $media, User $by = null): Event
    {
        $event = $this->findEventById($eventId);

        $media->setExpiresAt($event->getExpiresAt());
        if ($by instanceof User) {
            $media->setCreatedBy($by);
        }

        $event->addUploadedMedias($media);
        $this->entityManager->flush();

        $this->eventDispatcher->dispatch(AppEvents::EVENT_NEW_MEDIA_UPLOADED, new NewMediaUploadedEvent($event, $media));

        return $event;
    }

    private function getCreatedBy(): User
    {
        return $this->userManager->getLoggedInUser();
    }

    /**
     * @param $eventId
     * @param bool $inTrash
     * @return Event
     * @throws \Doctrine\ORM\EntityNotFoundException
     */
    public function findEventById($eventId, $inTrash = false): Event
    {
        return $this->entityManager->getRepository(Event::class)->findOneOrFail($eventId);
    }

    /**
     * @param User $user
     * @return array
     */
    public function lastIncompleteEvent(User $user)
    {
        $res=$this->entityManager->getRepository(Event::class)->findBy(['createdBy'=>$user->getId()], ['createdAt' => 'desc'],1,0 );

        $res = empty($res) ? null : $res[0];
        if($res != null){
            $currentStep = $res->getCurrentStep();
            if($currentStep === '') return null;
        }
        return $res;
    }

    /**
     * @param bool $isPayed
     * @return int
     */
    public function countEventByPayment($isPayed=true)
    {
        $res = $this->entityManager->getRepository(Event::class)->getNbEventByPyment($isPayed);
        $res = empty($res) ? null : $res[0];
        if($res === null) return 0;
        return $res['NB_EVENT'];
    }

    /**
     * @param $planKey
     * @return int
     */
    public function countEventByPlan($planKey)
    {
        $res = $this->entityManager->getRepository(Event::class)->getNbEventByPlan($planKey);
        $res = empty($res) ? null : $res[0];
        if($res === null) return 0;
        return $res['NB_EVENT'];
    }

    /**
     * @return int
     */
    public function countAllPayment()
    {
        $res = $this->entityManager->getRepository(Payment::class)->getAllSUMPayment();
        $res = empty($res) ? null : $res[0];
        if($res === null) return 0;
        return $res['somme'];
    }

    /**
     * @param $user
     * @return mixed
     */
    public function getPassedEvents($user)
    {
        $res = $this->entityManager->getRepository(Event::class)->getPassedEvents($user);
        return $res;

    }

    /**
     * @param $user
     * @return array
     */
    public function getUpcomingEvents($user)
    {
        $res = $this->entityManager->getRepository(Event::class)->findBy(['createdBy' => $user, 'closedAt' => null]);
        return $res;

    }

    public function getIsPaidEvents($user)
    {
        $events = $this->getUpcomingEvents($user);

        $res = [];
        foreach($events as $event){
           $res[$event->getId()] =  $this->paymentManager->isTotalPayed($event);
        }
        return $res;
    }
}
