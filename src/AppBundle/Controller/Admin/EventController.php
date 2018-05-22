<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Event;
use AppBundle\Model\EventManager;
use AppBundle\Model\Payment\PaymentManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Event controller.
 *
 * @Route("admin/event")
 */
class EventController extends BaseController
{
    /**
     * Lists all event entities.
     *
     * @Route("/", name="event_index")
     * @Method("GET")
     */
    public function indexAction(EventManager $eventManager)
    {
        $em = $this->getDoctrine()->getManager();

        $nbEventNoPayed =$eventManager->countEventByPayment(false);
        $nbEventPayed =$eventManager->countEventByPayment(true);
        $nbEventFree =$eventManager->countEventByPlan('free');
        $nbEventLuxury =$eventManager->countEventByPlan('luxury');
        $nbEventStarter =$eventManager->countEventByPlan('starter');
        $nbEventPremium =$eventManager->countEventByPlan('premium');
        $sumPayment =$eventManager->countAllPayment()/100;


        $events = $em->getRepository('AppBundle:Event')->findAll();

        return $this->render('admin/event/index.html.twig', array(
            'events' => $events,
            'nbEventPayed' => $nbEventPayed,
            'nbEventNoPayed' => $nbEventNoPayed,
            'nbEventFree' => $nbEventFree,
            'nbEventLuxury' => $nbEventLuxury,
            'nbEventStarter' => $nbEventStarter,
            'nbEventPremium' => $nbEventPremium,
            'sumPayment' => $sumPayment,
        ));
    }


    /**
     * Finds and displays a event entity.
     *
     * @Route("/{id}", name="event_show")
     * @Method("GET")
     *
     * @param Event $event
     * @param PaymentManager $paymentManager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Event $event, PaymentManager $paymentManager)
    {
        $deleteForm = $this->createDeleteForm($event);

        return $this->render('admin/event/show_2.html.twig', array(
            'event' => $event,
            'delete_form' => $deleteForm->createView(),
            'TotalPayed' => $paymentManager->TotalPayed($event),
            'isPayed' => $paymentManager->isTotalPayed($event)
        ));
    }

    /**
     * Deletes a event entity.
     *
     * @Route("/{id}", name="event_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Event $event)
    {
        $form = $this->createDeleteForm($event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($event);
            $em->flush();
        }

        return $this->redirectToRoute('event_index');
    }

    /**
     * Creates a form to delete a event entity.
     *
     * @param Event $event The event entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Event $event)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('event_delete', array('id' => $event->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
