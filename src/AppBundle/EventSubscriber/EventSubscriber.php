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
use AppBundle\DTO\EventChallenge;
use AppBundle\DTO\EventCover;
use AppBundle\DTO\EventInformation;
use AppBundle\DTO\InviteFriends;
use AppBundle\DTO\Payment;
use AppBundle\Entity\Category;
use AppBundle\Entity\Challenge;
use AppBundle\Entity\Plan;
use AppBundle\Entity\User;
use AppBundle\Model\EventManager;
use AppBundle\Model\InvitationRequestManager;
use AppBundle\Model\PlanManager;
use Carbon\Carbon;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Serializer\Serializer;

class EventSubscriber implements EventSubscriberInterface, ContainerAwareInterface
{

    use ContainerAwareTrait;
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
     * @var InvitationRequestManager
     */
    private $invitationRequestManager;

    /**
     * EventSubscriber constructor.
     * @param TokenStorage $tokenStorage
     * @param EventManager $eventManager
     * @param PlanManager $planManager
     */
    public function __construct(TokenStorage $tokenStorage,EventManager $eventManager, PlanManager $planManager, ContainerInterface $container,InvitationRequestManager $invitationRequestManager )
    {
        $this->tokenStorage = $tokenStorage;
        $this->eventManager = $eventManager;
        $this->planManager = $planManager;
        $this->container = $container;
        $this->invitationRequestManager = $invitationRequestManager;
    }

    public static function getSubscribedEvents()
    {

        return [
            KernelEvents::VIEW => [
                ['handleChoosePlanForEvent', EventPriorities::POST_VALIDATE],
                ['handleEventInformation', EventPriorities::POST_VALIDATE],
                ['handleEventChallenge', EventPriorities::POST_VALIDATE],
                ['handleEventPayment', EventPriorities::POST_VALIDATE],
                ['handleInviteFriends', EventPriorities::POST_VALIDATE],
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

            $appEvent->setCategory($this->eventManager->getEntityManager()->getRepository(Category::class)->find($eventInformation->idCat));
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

    /**
     * @param GetResponseForControllerResultEvent $event
     * @throws \Doctrine\ORM\EntityNotFoundException
     */
    public function handleEventChallenge(GetResponseForControllerResultEvent $event)
    {
        $request = $event->getRequest();
        $responseData = [];
        if ('api_event_challenges_post_collection' !== $request->attributes->get('_route'))
        {
            return;
        }

        $token = $this->tokenStorage->getToken();

        if ($token && is_object($user = $token->getUser()) && $user instanceof User)
        {
            /** @var EventChallenge $eventChallenge */
            $eventChallenge = $event->getControllerResult();
            $appEvent = $this->eventManager->findEventById($request->get('id'));

            if($appEvent->getCreatedBy() !== $user) return;
            foreach ($eventChallenge->challenges as $description)
            {
                $challenge = new Challenge();
                $challenge->setDescription($description);
                $challenge->setEvent($appEvent);
                $appEvent->addChallenge($challenge);

                $this->eventManager->getEntityManager()->persist($challenge);
            }

            $appEvent->setCurrentStep('event-cover');
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
    public function handleEventPayment(GetResponseForControllerResultEvent $event)
    {
        $request = $event->getRequest();
        $responseData = [];
        if ('api_payments_post_collection' !== $request->attributes->get('_route'))
        {
            return;
        }

        $token = $this->tokenStorage->getToken();

        if ($token && is_object($user = $token->getUser()) && $user instanceof User)
        {
            /** @var Payment $paymentDTO */
            $paymentDTO = $event->getControllerResult();
            $appEvent = $this->eventManager->findEventById($request->get('id'));

            if($appEvent->getCreatedBy() !== $user) return;

            if($paymentDTO->isFakePayment){

                $appEvent->setCurrentStep('invite-friends');
                $this->eventManager->getEntityManager()->flush();

                $responseData['eventURI'] =  "/api/events/".$appEvent->getId() ;
                // TODO: Translate
                $responseData['message'] = 'Your event has been updated successfully';

                $event->setResponse(new JsonResponse($responseData, 200));
            }else{

            $gatewayName = 'offline';

            $storage = $this->container->get('payum')->getStorage('AppBundle\Entity\Payment');

            /**  @var \AppBundle\Entity\Payment $payment  */
            $payment = $storage->create();
            $payment->setNumber( $paymentDTO->numberCard );
            $payment->setCurrencyCode('EUR');
            $payment->setTotalAmount( $paymentDTO->price * 100);
            $payment->setUser($user);
            $payment->setEventPurchase($appEvent->getEventPurchase());
            $payment->setClientEmail($user->getEmail());
            $storage->update($payment);

            $appEvent->getEventPurchase()->addPayment($payment);

            $captureToken = $this->container->get('payum')->getTokenFactory()->createCaptureToken(
                $gatewayName,
                $payment,
                'payment_done', ['id' => $appEvent->getId()]// the route to redirect after capture
            );

            $url =  $captureToken->getTargetUrl() ;

            $appEvent->setCurrentStep('invite-friends');
            $this->eventManager->getEntityManager()->flush();

            $responseData['eventURI'] =  "/api/events/".$appEvent->getId() ;
            $responseData['payment'] =  $url ;
            // TODO: Translate
            $responseData['message'] = 'Your event has been updated successfully';

            }
        }

        $event->setResponse(new JsonResponse($responseData, 200));
    }

    /**
     * @param GetResponseForControllerResultEvent $event
     * @throws \Doctrine\ORM\EntityNotFoundException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handleInviteFriends(GetResponseForControllerResultEvent $event)
    {
        $request = $event->getRequest();
        $responseData = [];
        if ('api_invite_friends_post_collection' !== $request->attributes->get('_route'))
        {
            return;
        }

        $token = $this->tokenStorage->getToken();

        if ($token && is_object($user = $token->getUser()) && $user instanceof User)
        {
            /** @var InviteFriends $inviteFriends */
            $inviteFriends = $event->getControllerResult();
            $appEvent = $this->eventManager->findEventById($request->get('id'));

            if($appEvent->getCreatedBy() !== $user) return;
            foreach ($inviteFriends->emails as $email){
                if (null !== $email && '' !== $email) {
                    $this->invitationRequestManager->createInvitationRequest($email, $appEvent, false);
                }
            }

            $appEvent->setCurrentStep('');
            $this->eventManager->getEntityManager()->flush();

            $responseData['eventURI'] =  "/api/events/".$appEvent->getId() ;
            $responseData['$inviteFriends'] =  $inviteFriends ;
            // TODO: Translate
            $responseData['message'] = 'Your event has been updated successfully';

        }

        $event->setResponse(new JsonResponse($responseData, 200));
    }

}
