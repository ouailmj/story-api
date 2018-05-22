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

use AppBundle\Entity\Event;
use AppBundle\Entity\Media;
use AppBundle\Messaging\Driver\ZMQDriver;
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
        $event = $this->getEM()->find(Event::class, 1);
        $media = $mediaManager->createMediaFromLocalFile(__DIR__.'/../../../web/images/Imagetests/1.jpg', $this->getUser());
        $eventManager->addMedia($event->getId(), $media, $this->getUser());

        // VarDumper::dump($event->getUploadedMedias()->toArray());
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
    public function eventAction(ZMQDriver $driver)
    {
        $data = [
            '_eventId' => '1',
            '_name' => 'test',
            '_image' => 'https://pbs.twimg.com/media/Cb6-pZiWIAIutOl.jpg:large',
        ];
        try {
            $driver->push(json_encode($data));
        } catch (\Exception $exception) {
            VarDumper::dump($exception);
        }

        return $this->render('AppBundle:Events:add_event.html.twig', [
        ]);
    }

    /**
     * @Route("/event/{event}/gallery")
     *
     * @param Event $event
     *
     * @throws \Exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function galleryAction(Event $event)
    {
        return $this->render('AppBundle:Events:gallery.html.twig', [
            'event' => $event,
        ]);
    }

<<<<<<< HEAD

    /**
     * @Route("/event/{event}/galleryex1")
     * @throws \Exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */


    public function galleryexample1Action(Event $event)
=======
    /**
     * @Route("/event/{event}/gallerytest")
     *
     * @param Event $event
     *
     * @throws \Exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function gallerytestAction(Event $event)
    {
        return $this->render('AppBundle:Events:gallerytest.html.twig', [
            'event' => $event,
        ]);
    }

    /**
     * @Route("/galleryexample1")
     *
     * @throws \Exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function galleryexample1Action()
>>>>>>> d3d0d6ac888bd32b5a7e12bea8ef9e12e450099e
    {
        return $this->render('AppBundle:Events:galleryexample1.html.twig', [
            'event' => $event,
        ]);
    }

    /**
<<<<<<< HEAD
     * @Route("/event/{event}/galleryexample2")
=======
     * @Route("/galleryexample2")
     *
>>>>>>> d3d0d6ac888bd32b5a7e12bea8ef9e12e450099e
     * @throws \Exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
<<<<<<< HEAD


    public function galleryexample2Action(Event $event, MediaManager $media) //working with websocket
=======
    public function galleryexample2Action()
>>>>>>> d3d0d6ac888bd32b5a7e12bea8ef9e12e450099e
    {
        $medias = $media->mediaUploadedFifteenMinutes($event);
        return $this->render('AppBundle:Events:galleryexample2.html.twig', [
<<<<<<< HEAD
            'event' => $event,
            'medias' => $medias
        ]);
    }


=======
        ]);
    }
>>>>>>> d3d0d6ac888bd32b5a7e12bea8ef9e12e450099e
}
