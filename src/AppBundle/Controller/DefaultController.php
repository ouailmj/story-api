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

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
     * @Route("/mockup")
     */
    public function mockupAction()
    {

        return $this->render('AppBundle:Events:index.html.twig', [
            // ...
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
