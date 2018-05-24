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

namespace AppBundle\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use AppBundle\DTO\ChoosePlan;
use AppBundle\DTO\EventInformation;
use AppBundle\Entity\Plan;
use AppBundle\Entity\User;
use AppBundle\Model\EventManager;
use AppBundle\Model\PlanManager;
use Carbon\Carbon;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Serializer\Serializer;

class EventSubscriber implements EventSubscriberInterface
{

    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * @var PlanManager
     */
    private $planManager;

    /**
     * EventSubscriber constructor.
     * @param TokenStorage $tokenStorage
     * @param EventManager $eventManager
     * @param PlanManager $planManager
     */
    public function __construct(TokenStorage $tokenStorage,EventManager $eventManager, PlanManager $planManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->eventManager = $eventManager;
        $this->planManager = $planManager;
    }

    public static function getSubscribedEvents()
    {

        return [
            KernelEvents::VIEW => [
                ['handleChoosePlanForEvent', EventPriorities::POST_VALIDATE],
                ['handleEventInformation', EventPriorities::POST_VALIDATE],
            ],
        ];
    }

    /**
     * @param GetResponseForControllerResultEvent $event
     * @throws \Doctrine\ORM\EntityNotFoundException
     */
    public function handleChoosePlanForEvent(GetResponseForControllerResultEvent $event)
    {
        $request = $event->getRequest();
        $responseData = [];
        if ('api_choose_plans_post_collection' !== $request->attributes->get('_route'))
        {
            return;
        }

        $token = $this->tokenStorage->getToken();

        if ($token && is_object($user = $token->getUser()) && $user instanceof User)
        {
            /** @var ChoosePlan $choosePlan */
            $choosePlan = $event->getControllerResult();
            $appEvent = $this->eventManager->findEventById($request->get('id'));

            if($appEvent->getCreatedBy() !== $user) return;

            /** @var Plan $plan */
            $plan = $this->planManager->findPlanByCriteria(['planKey' => $choosePlan->planKey]);

            $appEvent->getEventPurchase()->setPlan($plan);
            $appEvent->setCurrentStep('event-information');
            $this->eventManager->getEntityManager()->flush();

            $responseData['eventURI'] =  "/api/events/".$appEvent->getId() ;
            // TODO: Translate
            $responseData['message'] = 'Your event has been updated successfully';
        }

        $event->setResponse(new JsonResponse($responseData, 200));
    }

    /**
     * @param GetResponseForControllerResultEvent $event
     * @throws \Doctrine\ORM\EntityNotFoundException
     */
    public function handleEventInformation(GetResponseForControllerResultEvent $event)
    {
        $request = $event->getRequest();
        $responseData = [];
        if ('api_event_informations_post_collection' !== $request->attributes->get('_route'))
        {
            return;
        }

        $token = $this->tokenStorage->getToken();

        if ($token && is_object($user = $token->getUser()) && $user instanceof User)
        {
            /** @var EventInformation $eventInformation */
            $eventInformation = $event->getControllerResult();
            $appEvent = $this->eventManager->findEventById($request->get('id'));

            if($appEvent->getCreatedBy() !== $user) return;

            $maxEndsAt=Carbon::parse( $eventInformation->startsAt->format('Y-m-d H:m'))->addRealSeconds($appEvent->getEventPurchase()->getPlan()->getMaxEventDuration());
            $endsAt= $eventInformation->endsAt instanceof \DateTime ? Carbon::parse( $eventInformation->endsAt->format('Y-m-d H:m')) : null;
            if($endsAt->gt($maxEndsAt))
            {
                $appEvent->setEndsAt($maxEndsAt);
            }else{
                $appEvent->setEndsAt($eventInformation->endsAt);
            }
            $appEvent->setDescription($eventInformation->description);
            $appEvent->setTitle($eventInformation->title);
            $appEvent->setStartsAt($eventInformation->startsAt);
            $appEvent->setPlace($eventInformation->place);
            $appEvent->setExpiresAt(Carbon::parse( $appEvent->getEndsAt()->format('Y-m-d H:m'))->addRealSeconds($appEvent->getEventPurchase()->getPlan()->getMaxAlbumLifeTime()));

            if($appEvent->getEventPurchase()->getPlan()->getEnableChallenges())
            {
                $appEvent->setCurrentStep('event-challenge');
            } else {
                 $appEvent->setCurrentStep('event-cover');
            }

            $this->eventManager->getEntityManager()->flush();

            $responseData['eventURI'] =  "/api/events/".$appEvent->getId() ;
            // TODO: Translate
            $responseData['message'] = 'Your event has been updated successfully';

        }

        $event->setResponse(new JsonResponse($responseData, 200));
    }

}