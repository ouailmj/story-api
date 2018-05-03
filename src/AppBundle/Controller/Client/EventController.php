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
use AppBundle\Entity\EventPurchase;
use AppBundle\Entity\Image;
use AppBundle\Entity\Video;
use AppBundle\Exception\FileNotAuthorizedException;
use AppBundle\Form\ChallengeType;
use AppBundle\Form\Event\ChoosePlanType;
use AppBundle\Form\Event\EventCoverType;
use AppBundle\Form\Event\EventInformationType;
use AppBundle\Model\EventManager;
use AppBundle\Model\MediaManager;
use AppBundle\Model\PlanManager;
use Carbon\Carbon;
use Doctrine\ORM\ORMException;
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
 * @Route("add-event")
 * @Security("has_role('ROLE_ADMIN')")
 */
class EventController extends BaseController
{

    /**
     *
     * @Route("/", name="add_event_index")
     * @Method("GET")
     *
     * @param EventManager $eventManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request, EventManager $eventManager)
    {
        /**
         * @var Event $event
         */
        $event = $eventManager->lastIncompleteEvent($this->getUser());

        if($event === null) return $this->redirectToRoute('add_event_choose_plan');

        switch($event->getCurrentStep()){
            case 'choose-plan':
               return $this->redirectToRoute('add_event_choose_plan');
                break;
            case 'event-information':
                return $this->redirectToRoute('add_event_event_information', ['id'=> $event->getId()]);
                break;
            case 'event-cover':
                return $this->redirectToRoute('add_event_event_cover', ['id'=> $event->getId()]);
                break;
            case 'payment':
                return $this->redirectToRoute('add_event_payment', ['id'=> $event->getId()]);
                break;
            default:
                return $this->redirectToRoute('add_event_choose_plan');
                break;
        }
       // return null;
    }

    /**
     *
     * @Route("/choose-plan", name="add_event_choose_plan")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param EventManager $eventManager
     * @param PlanManager $planManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function choosePlanAction(Request $request, EventManager  $eventManager, PlanManager $planManager)
    {
        $lastEvent = $eventManager->lastIncompleteEvent($this->getUser());

        if($lastEvent != null) {
            if($request->query->get('event') === null) return $this->redirectToRoute('add_event_index');
            $event = $eventManager->findEventById($request->query->get('event'));
            if($event->getCreatedBy() != $this->getUser()) return $this->redirectToRoute('add_event_index');
        }else {
            $event = new Event();
        }
        $form = $this->createForm(ChoosePlanType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $event->setCurrentStep('event-information');
            $plan = $form->getData()['plan'];
            $eventManager->createEvent($plan, $event, $this->getUser());
            $this->addSuccessFlash();
            return $this->redirectToRoute('add_event_index',['id'=> $event->getId()]);
        }

        return $this->render('client/event/choose-plan.html.twig', [
            'form' => $form->createView(),
            'plans' => $planManager->allPlans(),
            'event' => $event
            ]);

    }

    /**
     *
     * @Route("/event-information/{id}", name="add_event_event_information")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Event $event
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function EventInformationAction(Request $request, Event $event){

        $form = $this->createForm(EventInformationType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($form->get('challenges') as $challenge){
                $plannedAtHour = $challenge->get('plannedAtHour')->getData();
                $plannedAtHourToS = (($plannedAtHour['hour']*3600) + ($plannedAtHour['minute']*60)) ;
                $challenge->getData()->setPlannedAt(Carbon::parse( $event->getStartsAt()->format('Y-m-d H:m'))->addRealSeconds($plannedAtHourToS));
                $challenge->getData()->setEvent($event);
            }

            $event->setCurrentStep('event-cover');
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('add_event_index', ['id' => $event->getId()]);
        }
        return $this->render('client/event/event-information.html.twig', [
            'form' => $form->createView(),
            'event' => $event
            ]);
    }

    /**
     *
     * @Route("/event-cover/{id}", name="add_event_event_cover")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Event $event
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function EventCoverAction(Request $request, Event $event, MediaManager $mediaManager){
        $form = $this->createForm(EventCoverType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $coverType=$form->get('coverType')->getData();
                dump($coverType);die();
                if($coverType === 'video'){
                    /** @var UploadedFile $uploadedVideo */
                    $uploadedVideo = $form->get('videoCover')->getData();
                    /** @var Video $media */
                    $media = $mediaManager->uploadVideo($uploadedVideo, $this->getUser());
                    $event->setVideoGallery($media);
                    $this->addSuccessFlash();
                }elseif($coverType === 'image'){
                    $gallery= array($form->get('firstImageCover')->getData(), $form->get('secondImageCover')->getData(), $form->get('thirdImageCover')->getData());
                    foreach($gallery as $img){
                        /** @var Image $media */
                        $media = $mediaManager->uploadImage($img, $this->getUser());

                       // $this->getDoctrine()->getManager()->persist($media);
                        $event->addImagesGallery($media);

                    }
                }
            } catch (FileNotFoundException $exception) {
                    $this->get('logger')->addError($exception->getTraceAsString());
                    $this->addFlash('error', $this->get('translator')->trans('flash.file_error'));
            } catch (FileNotAuthorizedException $exception) {
                    $this->get('logger')->addError($exception->getTraceAsString());
                    $this->addFlash('error', $this->get('translator')->trans('flash.file_not_authorized'));
            } catch (ORMException $exception) {}

            $event->setCurrentStep('payment');

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('add_event_index', ['id' => $event->getId()]);
        }
        return $this->render('client/event/event-cover.html.twig', [
            'form' => $form->createView(),
            'event' => $event
        ]);
    }

    /**
     *
     * @Route("/payment/{id}", name="add_event_payment")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Event $event
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function PaymentAction(Request $request, Event $event){
        dump($event->getImagesGallery());
        die;
        $form = $this->createForm(EventCoverType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();
            $event->setCurrentStep('payment');
            return $this->redirectToRoute('add_event_index', ['id' => $event->getId()]);
        }
        return $this->render('client/event/payment.html.twig', [
            'form' => $form->createView(),
            'event' => $event
        ]);
    }
}