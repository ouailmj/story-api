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
use AppBundle\Entity\Event;
use AppBundle\Entity\EventPurchase;
use AppBundle\Form\Event\ChoosePlanType;
use AppBundle\Model\EventManager;
use AppBundle\Model\PlanManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

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
     * @Route("/", name="add-event_index")
     * @Method("GET")
     *
     * @param EventManager $eventManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(EventManager $eventManager)
    {
        /**
         * @var Event $event
         */
        $event = $eventManager->lastIncompleteEvent($this->getUser());

        if($event === null) return $this->redirectToRoute('add-event_choose_plan');

        switch($event->getCurrentStep()){
            case 'choose-plan':
               return $this->redirectToRoute('add-event_event_information');
                break;
            case 'event-information':
                return $this->redirectToRoute('add-event_event_information');
                break;
            default:
                return $this->redirectToRoute('add-event_choose_plan');
                break;
        }
       // return null;
    }

    /**
     *
     * @Route("/choose-plan", name="add-event_choose_plan")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param EventManager $eventManager
     * @param PlanManager $planManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function choosePlanAction(Request $request, EventManager  $eventManager, PlanManager $planManager)
    {
        $event = new Event();
        $event->setCurrentStep('choose-plan');
        $form = $this->createForm(ChoosePlanType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $plan = $form->getData()['plan'];
            $eventManager->createEvent($plan, $event, $this->getUser());
            $this->addSuccessFlash();
            return $this->redirectToRoute('add-event_index');
        }

        return $this->render('AppBundle:Default:draft.html.twig', [
            'form' => $form->createView(),
            'plans' => $planManager->allPlans(),
        ]);

    }

    /**
     *
     * @Route("/event-information", name="add-event_event_information")
     * @Method({"GET", "PUT"})
     */
    public function EventInformationAction(){
        return $this->render('AppBundle:Events:add_event.html.twig');
    }
}