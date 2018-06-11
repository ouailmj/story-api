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

namespace AppBundle\Controller\Admin;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Event;
use AppBundle\Model\EventManager;
use AppBundle\Model\Payment\PaymentManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     *
     * @param EventManager $eventManager
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\DBAL\DBALException
     */
    public function indexAction(EventManager $eventManager)
    {
        $em = $this->getDoctrine()->getManager();

        $nbEventNoPayed = $eventManager->countEventByPayment(false);
        $nbEventPayed = $eventManager->countEventByPayment(true);
        $nbEventFree = $eventManager->countEventByPlan('free');
        $nbEventLuxury = $eventManager->countEventByPlan('luxury');
        $nbEventStarter = $eventManager->countEventByPlan('starter');
        $nbEventPremium = $eventManager->countEventByPlan('premium');
        $sumPayment = $eventManager->countAllPayment() / 100;

        $events = $em->getRepository('AppBundle:Event')->findAll();

        return $this->render('admin/event/index.html.twig', [
            'events' => $events,
            'nbEventPayed' => $nbEventPayed,
            'nbEventNoPayed' => $nbEventNoPayed,
            'nbEventFree' => $nbEventFree,
            'nbEventLuxury' => $nbEventLuxury,
            'nbEventStarter' => $nbEventStarter,
            'nbEventPremium' => $nbEventPremium,
            'sumPayment' => $sumPayment,
        ]);
    }

    /**
     * Finds and displays a event entity.
     *
     * @Route("/{id}", name="event_show")
     * @Method("GET")
     *
     * @param Event          $event
     * @param PaymentManager $paymentManager
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Event $event, PaymentManager $paymentManager)
    {
        $deleteForm = $this->createDeleteForm($event);

        return $this->render('admin/event/show.html.twig', [
            'event' => $event,
            'delete_form' => $deleteForm->createView(),
            'TotalPayed' => $paymentManager->TotalPayed($event),
            'isPayed' => $paymentManager->isTotalPayed($event),
        ]);
    }

    /**
     * Creates a form to delete a event entity.
     *
     * @param Event $event
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    private function createDeleteForm(Event $event)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('event_delete', ['id' => $event->getId()]))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Deletes a event entity.
     *
     * @Route("/{id}", name="event_delete")
     * @Method("DELETE")
     *
     * @param Request $request
     * @param Event $event
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
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
}
