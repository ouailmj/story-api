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
use AppBundle\Entity\InvitationRequest;
use AppBundle\Entity\Media;
use AppBundle\Entity\Payment;
use AppBundle\Entity\Plan;
use AppBundle\Entity\User;
use AppBundle\Event\NewMediaUploadedEvent;
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

    /**
     * EventManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param MediaManager           $mediaManager
     * @param UserManager            $userManager
     */
    public function __construct(EntityManagerInterface $entityManager, MediaManager $mediaManager, UserManager $userManager)
    {
        $this->entityManager = $entityManager;
        $this->mediaManager = $mediaManager;
        $this->userManager = $userManager;

        $this->eventDispatcher = new EventDispatcher();
    }

    public function deleteUserEvents(User $user)
    {
    }

    public function createEvent(Plan $plan, User $createdBy = null)
    {
        $event = new Event();
        $eventPurchase = new EventPurchase();
        $eventPurchase->setPlan($plan);
        $eventPurchase->setQuota($plan->getMaxUploads());
        $event->setEventPurchase($eventPurchase);
        $createdBy = ($createdBy instanceof User) ? $createdBy : $this->getCreatedBy();

        $startsAt = Carbon::tomorrow($createdBy->getTimeZoneInstance());
        $endsAt = $startsAt->addRealSeconds($plan->getMaxEventDuration());

        $event->setCreatedBy($createdBy);
        $event->setStartsAt($startsAt);
        $event->setEndsAt($endsAt);
        $event->setExpiresAt($endsAt->addRealSeconds($plan->getMaxAlbumLifeTime()));

        $this->entityManager->persist($event);
        $this->entityManager->flush();

        return $event;
    }

    public function createEventWithFreePlan(User $createdBy = null)
    {
        return $this->createEvent($this->entityManager->getRepository(Plan::class)->getFreePlan(), $createdBy);
    }

    public function createEventWithStarterPlan(User $createdBy = null)
    {
        return $this->createEvent($this->entityManager->getRepository(Plan::class)->getStarterPlan(), $createdBy);
    }

    /**
     *
     * @param int       $eventId
     * @param Media     $media
     * @param User|null $by
     *
     * @throws \Doctrine\ORM\EntityNotFoundException
     *
     * @return Event
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

    /**
     * @param int $eventId
     * @throws \Doctrine\ORM\EntityNotFoundException
     */
    public function trashEvent(int $eventId)
    {
        $event = $this->findEventById($eventId);

        // TODO: Trash all media
        foreach ($event->getUploadedMedias() as $media) {
            $this->mediaManager->trashMedia($media->getId());
        }

        // TODO: Archive Event

        // TODO: Fire the EventTrashedEvent event
    }

    /**
     * @param int $trashedEventId
     * @throws \Doctrine\ORM\EntityNotFoundException
     */
    public function unTrashEvent(int $trashedEventId)
    {
        $event = $this->findEventById($trashedEventId, true);

        // TODO: Trash all media
        foreach ($event->getUploadedMedias() as $media) {
            $this->mediaManager->trashMedia($media->getId());
        }

        // TODO: Archive Event

        // TODO: Fire the EventArchivedEvent event
    }

    /**
     * @param int $eventId
     * @param Payment $payment
     * @return Event
     */
    public function addPayment(int $eventId, Payment $payment): Event
    {
        // Call the payment processor.

        // Fire payment event (success/error)

        // If all due amount is payed enable the event.

        // Fire EventEnabledEvent.
    }

    /**
     * Events are enabled only when they are fully payed.
     *
     * @param int $eventId
     * @return Event
     */
    public function enableEvent(int $eventId): Event
    {
    }

    public function addEmailInvitationRequest(int $eventId, string $email, $andSend = false): InvitationRequest
    {
        $user = $this->userManager->getFOSUserManager()->findUserByUsernameOrEmail($email);

        $event = $this->findEventById($eventId);
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
    private function findEventById($eventId, $inTrash = false): Event
    {
        return $this->entityManager->getRepository(Event::class)->findOneOrFail($eventId);
    }


}
