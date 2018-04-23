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

namespace AppBundle\Controller;

use AppBundle\Model\EventManager;
use AppBundle\Model\MediaManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\VarDumper\VarDumper;

class DefaultController extends BaseController
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('app_profile_edit');
        }

        return $this->render('AppBundle:Default:index.html.twig', [
            // ...
        ]);
    }

    /**
     * @Route("/dummy")
     */
    public function dummyAction(Request $request, MediaManager $mediaManager, EventManager $eventManager)
    {
        $event = $eventManager->createEventWithFreePlan();
        $media = $mediaManager->createMediaFromLocalFile(__FILE__);
        $eventManager->addMedia($event->getId(), $media);

        VarDumper::dump($event->getUploadedMedias()->toArray());
        $form = $this->createFormBuilder()
            ->add('file', FileType::class)
            ->add('submit', SubmitType::class)
            ->getForm()
        ;

        return $this->render('@App/Default/dummy.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/add_event")
     */
    public function eventAction()
    {
        return $this->render('AppBundle:Events:add_event.html.twig', [
            // ...
        ]);
    }

    /**
     * @Route("/gallery")
     */
    public function galleryAction()
    {
        return $this->render('AppBundle:Events:gallery.html.twig', [
            // ...
        ]);
    }
}
