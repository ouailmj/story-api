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
use AppBundle\Form\ChallengeType;
use AppBundle\Form\Event\ChoosePlanType;
use AppBundle\Form\Event\EventInformationType;
use AppBundle\Model\EventManager;
use AppBundle\Model\PlanManager;
use Carbon\Carbon;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
     * @Method({"GET", "PUT"})
     *
     * @param Request $request
     * @param Event $event
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function EventCoverAction(Request $request, Event $event){

       dump($event);die;
        return $this->render('client/event/event-information.html.twig');
    }
}