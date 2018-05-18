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

namespace AppBundle\Controller\Client;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Challenge;
use AppBundle\Entity\Event;
use AppBundle\Entity\Image;
use AppBundle\Entity\Video;
use AppBundle\Exception\FileNotAuthorizedException;
use AppBundle\Form\Event\ChoosePlanType;
use AppBundle\Form\Event\EventChallengeType;
use AppBundle\Form\Event\EventCoverType;
use AppBundle\Form\Event\EventInformationType;
use AppBundle\Form\Event\InviteFriendsType;
use AppBundle\Form\Event\PaymentEventType;
use AppBundle\Model\EventManager;
use AppBundle\Model\InvitationRequestManager;
use AppBundle\Model\MediaManager;
use AppBundle\Model\Payment\PaymentManager;
use AppBundle\Model\PlanManager;
use Carbon\Carbon;
use Doctrine\ORM\ORMException;
use Payum\Core\Request\GetHumanStatus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * User controller.
 *
 * @Security("has_role('ROLE_USER')")
 */
class EventController extends BaseController
{
    /**
     * @Route("add-event/", name="add_event_index")
     * @Method("GET")
     *
     * @param EventManager $eventManager
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request, EventManager $eventManager)
    {
        /**
         * @var Event
         */
        $event = $eventManager->lastIncompleteEvent($this->getUser());

        if (null === $event) {
            return $this->redirectToRoute('add_event_choose_plan');
        }
        switch ($event->getCurrentStep()) {
            case 'choose-plan':
               return $this->redirectToRoute('add_event_choose_plan');
                break;
            case 'event-information':
                return $this->redirectToRoute('add_event_event_information', ['id' => $event->getId()]);
                break;
            case 'event-challenge':
                return $this->redirectToRoute('add_event_event_challenge', ['id' => $event->getId()]);
                break;
            case 'event-cover':
                return $this->redirectToRoute('add_event_event_cover', ['id' => $event->getId()]);
                break;
            case 'payment':
                return $this->redirectToRoute('add_event_payment', ['id' => $event->getId()]);
                break;
            case 'invite-friends':
                return $this->redirectToRoute('add_event_invite_friends', ['id' => $event->getId()]);
                break;
            case 'finish':
                $event->setCurrentStep('');
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('list-event');
                break;
            default:
                return $this->redirectToRoute('add_event_choose_plan');
                break;
        }
    }

    /**
     * @Route("add-event/choose-plan", name="add_event_choose_plan")
     * @Method({"GET", "POST"})
     *
     * @param Request      $request
     * @param EventManager $eventManager
     * @param PlanManager  $planManager
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function choosePlanAction(Request $request, EventManager  $eventManager, PlanManager $planManager)
    {
        $lastEvent = $eventManager->lastIncompleteEvent($this->getUser());

        if (null !== $lastEvent) {
            if (null === $request->query->get('event')) {
                return $this->redirectToRoute('add_event_index');
            }
            $event = $eventManager->findEventById($request->query->get('event'));
            if ($event->getCreatedBy() !== $this->getUser()) {
                return $this->redirectToRoute('add_event_index');
            }
        } else {
            $event = new Event();
        }
        $options = ['plan_data' => (null !== $event->getEventPurchase()) ? $event->getEventPurchase()->getPlan() : null];
        $form = $this->createForm(ChoosePlanType::class, null, $options);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $event->setCurrentStep('event-challenge');
            $plan = $form->getData()['plan'];
            $eventManager->createEvent($plan, $event, $this->getUser());

            if ($event->getEventPurchase()->getPlan()->getEnableChallenges()) {
                $event->setCurrentStep('event-challenge');
            } else {
                $event->setCurrentStep('event-information');
            }
            $this->addSuccessFlash();

            return $this->redirectToRoute('add_event_index', ['id' => $event->getId()]);
        }

        return $this->render('client/event/choose-plan.html.twig', [
            'form' => $form->createView(),
            'plans' => $planManager->allPlans(),
            'event' => $event,
            ]);
    }

    /**
     * @Route("add-event/event-information/{id}", name="add_event_event_information")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Event   $event
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function EventInformationAction(Request $request, Event $event)
    {
        $form = $this->createForm(EventInformationType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $maxEndsAt = Carbon::parse($event->getStartsAt()->format('Y-m-d H:m'))->addRealSeconds($event->getEventPurchase()->getPlan()->getMaxEventDuration());
            $endsAt = $event->getEndsAt() instanceof \DateTime ? Carbon::parse($event->getEndsAt()->format('Y-m-d H:m')) : null;
            if ($endsAt->gt($maxEndsAt)) {
                $event->setEndsAt($maxEndsAt);
            }
            $event->setCurrentStep('event-cover');

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('add_event_index', ['id' => $event->getId()]);
        }

        return $this->render('client/event/event-information.html.twig', [
            'form' => $form->createView(),
            'event' => $event,
            'maxDuration' => $event->getEventPurchase()->getPlan()->getMaxEventDuration(),
            ]);
    }

    /**
     * @Route("add-event/event-challenges/{id}", name="add_event_event_challenge")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Event   $event
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function EventChallengeAction(Request $request, Event $event)
    {
        if (!$event->getEventPurchase()->getPlan()->getEnableChallenges()) {
            $event->setCurrentStep('event-information');
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('add_event_index', ['id' => $event->getId()]);
        }

        $hours = [];
        $startsAt = $event->getStartsAt() instanceof \DateTime ? Carbon::parse($event->getStartsAt()->format('Y-m-d H:m')) : null;
        $endsAt = $event->getEndsAt() instanceof \DateTime ? Carbon::parse($event->getEndsAt()->format('Y-m-d H:m')) : null;
        $diffHour = $startsAt->diffInHours($endsAt);

        for ($i = 0; $i < $diffHour; ++$i) {
            $hours[] = $i;
        }
        $options = ['data_hours' => $hours];
        $form = $this->createForm(EventChallengeType::class, null, $options);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($form->get('challenges') as $item) {
                $challenge = new Challenge();
                $challenge->setDescription($item->get('description')->getData());
                $plannedAtHour = $item->get('plannedAtHour')->getData();
                $plannedAtHourToS = (($plannedAtHour['hour'] * 3600) + ($plannedAtHour['minute'] * 60));
                $challenge->setPlannedAt(Carbon::parse($event->getStartsAt()->format('Y-m-d H:m'))->addRealSeconds($plannedAtHourToS));
                $challenge->setEvent($event);
                $event->addChallenge($challenge);
            }

            $event->setCurrentStep('event-information');
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('add_event_index', ['id' => $event->getId()]);
        }

        return $this->render('client/event/event-challenge.html.twig', [
            'form' => $form->createView(),
            'event' => $event,
        ]);
    }

    /**
     * @Route("add-event/event-cover/{id}", name="add_event_event_cover")
     * @Method({"GET", "POST"})
     *
     * @param Request      $request
     * @param Event        $event
     * @param MediaManager $mediaManager
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function EventCoverAction(Request $request, Event $event, MediaManager $mediaManager)
    {
        $form = $this->createForm(EventCoverType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $coverType = $form->get('coverType')->getData();
                if ('video' === $coverType) {
                    /** @var UploadedFile $uploadedVideo */
                    $uploadedVideo = $form->get('videoCover')->getData();
                    /** @var Video $media */
                    $media = $mediaManager->uploadVideo($uploadedVideo, $this->getUser());
                    $event->setVideoGallery($media);
                    $this->addSuccessFlash();
                } elseif ('image' === $coverType) {
                    $gallery = [$form->get('firstImageCover')->getData(), $form->get('secondImageCover')->getData(), $form->get('thirdImageCover')->getData()];
                    foreach ($gallery as $img) {
                        if (null !== $img) {
                            /** @var Image $media */
                            $media = $mediaManager->uploadImage($img, $this->getUser());
                            $event->addImagesGallery($media);
                        }
                    }
                }
            } catch (FileNotFoundException $exception) {
                $this->get('logger')->addError($exception->getTraceAsString());
                $this->addFlash('error', $this->get('translator')->trans('flash.file_error'));
            } catch (FileNotAuthorizedException $exception) {
                $this->get('logger')->addError($exception->getTraceAsString());
                $this->addFlash('error', $this->get('translator')->trans('flash.file_not_authorized'));
            } catch (ORMException $exception) {
            }

            if ('free' === $event->getEventPurchase()->getPlan()->getPlanKey()) {
                $event->setCurrentStep('invite-friends');
            } else {
                $event->setCurrentStep('payment');
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('add_event_index', ['id' => $event->getId()]);
        }

        return $this->render('client/event/event-cover.html.twig', [
            'form' => $form->createView(),
            'event' => $event,
        ]);
    }

    /**
     * @Route("add-event/payment/{id}", name="add_event_payment")
     * @Method({"GET", "POST"})
     *
     * @param Request        $request
     * @param Event          $event
     * @param PaymentManager $paymentManager
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function PaymentAction(Request $request, Event $event, PaymentManager $paymentManager)
    {
        if ($paymentManager->isTotalPayed($event)) {
            $event->setCurrentStep('invite-friends');
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('add_event_index');
        }
        $form = $this->createForm(PaymentEventType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $gatewayName = 'offline';

            $storage = $this->get('payum')->getStorage('AppBundle\Entity\Payment');

            $payment = $storage->create();
            $payment->setNumber($form->get('numberCard')->getData());
            $payment->setCurrencyCode('EUR');
            $payment->setTotalAmount($form->get('price')->getData() * 100);
            $payment->setUser($this->getUser());
            $payment->setEventPurchase($event->getEventPurchase());
            $payment->setClientEmail($this->getUser()->getEmail());
            $storage->update($payment);

            $event->getEventPurchase()->addPayment($payment);

            $captureToken = $this->get('payum')->getTokenFactory()->createCaptureToken(
                $gatewayName,
                $payment,
                'payment_done', ['id' => $event->getId()]// the route to redirect after capture
            );

            return $this->redirect($captureToken->getTargetUrl());
        }
        $sumPayed = $paymentManager->TotalPayed($event);

        return $this->render('client/event/payment.html.twig', [
            'form' => $form->createView(),
            'event' => $event,
            'sumPayed' => $sumPayed,
        ]);
    }

    /**
     * @Route("add-event/payment-done/{id}" ,  name="payment_done")
     *
     * @param Request        $request
     * @param Event          $event
     * @param PaymentManager $paymentManager
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function doneAction(Request $request, Event $event, PaymentManager $paymentManager)
    {
        $token = $this->get('payum')->getHttpRequestVerifier()->verify($request);

        $gateway = $this->get('payum')->getGateway($token->getGatewayName());

        $gateway->execute($status = new GetHumanStatus($token));

        // Now you have order and payment status
        if ($paymentManager->isTotalPayed($event)) {
            $event->setCurrentStep('invite-friends');
        }

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('add_event_index');
    }

    /**
     * @Route("add-event/invite-friends/{id}" ,  name="add_event_invite_friends")
     *
     * @param Request                  $request
     * @param Event                    $event
     * @param InvitationRequestManager $invitationRequestManager
     *
     * @return Response
     */
    public function InviteFriendsAction(Request $request, Event $event, InvitationRequestManager $invitationRequestManager, PaymentManager $paymentManager)
    {
        $form = $this->createForm(InviteFriendsType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $items = $form->get('items')->getData();

            $emails = explode(';', $items);
            foreach ($emails as $email) {
                if (null !== $email && '' !== $email) {
                    $invitationRequestManager->createInvitationRequest($email, $event, false);
                }
            }
            $event->setCurrentStep('finish');
            $this->getDoctrine()->getManager()->flush();
            $this->addSuccessFlash();

            return $this->redirectToRoute('add_event_index', ['id' => $event->getId()]);
        }

        return $this->render('client/event/invite-friends.html.twig', [
            'form' => $form->createView(),
            'event' => $event,
            'isPaid' => $paymentManager->isTotalPayed($event),
        ]);
    }

    /**
     * @Route("/events" ,  name="list-event")
     */
    public function ListEventAction(EventManager $eventManager)
    {
        $passedEvents = $eventManager->getPassedEvents($this->getUser());
        $upcomingEvents = $eventManager->getUpcomingEvents($this->getUser());
        $isPaidEvent = $eventManager->getIsPaidEvents($this->getUser());

        return $this->render('client/event/index.html.twig', [
            'passedEvents' => $passedEvents,
            'upcomingEvents' => $upcomingEvents,
            'isPaidEvent' => $isPaidEvent,
        ]);
    }


    /**
     * @Route("/edit-event")
     */

    public function editAction()
    {
    return $this->render('client/event/edit-event.html.twig', [
        // ...
    ]);
        }
    }


