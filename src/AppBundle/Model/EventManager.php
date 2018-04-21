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
        $endsAt = $startsAt->addSeconds($plan->getMaxEventDuration());

        $event->setCreatedBy($createdBy);
        $event->setStartsAt($startsAt);
        $event->setEndsAt($endsAt);
        $event->setExpiresAt($endsAt->addSeconds($plan->getMaxAlbumLifeTime()));

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
     * @param int $eventId
     * @param Media $media
     * @param User|null $by
     * @return Event
     * @throws \Doctrine\ORM\EntityNotFoundException
     */
    public function addMedia(int $eventId, Media $media, User $by = null): Event
    {
        /** @var Event $event */
        $event = $this->entityManager->getRepository(Event::class)->findOneOrFail($eventId);

        $media->setExpiresAt($event->getExpiresAt());
        if ($by instanceof User) $media->setCreatedBy($by);

        $event->addUploadedMedias($media);
        $this->entityManager->flush();

        $this->eventDispatcher->dispatch(AppEvents::EVENT_NEW_MEDIA_UPLOADED, new NewMediaUploadedEvent($event, $media));

        return $event;
    }

    private function getCreatedBy()
    {
        return $this->userManager->getLoggedInUser();
    }
}
