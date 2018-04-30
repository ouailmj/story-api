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

use AppBundle\Event\NewMediaUploadedEvent;
use AppBundle\Messaging\Driver\ZMQDriver;
use AppBundle\Messaging\GalleryPublisher;
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
        $event = $eventManager->createEventWithFreePlan($this->getUser());
        $media = $mediaManager->createMediaFromLocalFile(__DIR__.'/../../../web/assets/images/avatar.png', $this->getUser());

        $eventManager->addMedia($event->getId(), $media, $this->getUser());

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

    /**
     * @Route("/gallerytest")
     */
    public function gallerytestAction(ZMQDriver $driver)
    {
        try {
            $data = array(
               '_name' => 'Farah',
                '_eventId' => 'event-123456',
               '_image' => 'https://www.argospetinsurance.co.uk/assets/uploads/2017/12/cat-pet-animal-domestic-104827.jpeg'
            );
            $driver->push(json_encode($data));
        }catch (\ZMQSocketException $e){
            echo $e;
       }
        return $this->render('gallery.html.twig', [
        ]);
    }
}
