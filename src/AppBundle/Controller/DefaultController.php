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

use AppBundle\AppEvents;
use AppBundle\Entity\Event;
use AppBundle\Entity\User;
use AppBundle\Event\NewMediaUploadedEvent;
use AppBundle\Messaging\Driver\ZMQDriver;
use AppBundle\Model\EventManager;
use AppBundle\Model\MediaManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\EventDispatcher\EventDispatcher;
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
        $media = $mediaManager->createMediaFromLocalFile(__DIR__.'/../../../web/assets/images/avatar.png', $this->getUser());

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
        $data = array(
            '_eventId' =>'1',
            '_name' => 'test',
            '_image'  => 'https://pbs.twimg.com/media/Cb6-pZiWIAIutOl.jpg:large'
        );
        try{
            $driver->push(json_encode($data));
        }catch (\Exception $exception){
            VarDumper::dump($exception);
        }
        return $this->render('AppBundle:Events:add_event.html.twig', [
            // ...
        ]);
    }

     /**
      * @Route("/event/{event}/gallery")
      * @throws \Exception
      *
      * @param Event $event
      * @return \Symfony\Component\HttpFoundation\Response
      */


    public function galleryAction(Event $event)
    {
       return $this->render('AppBundle:Events:gallery.html.twig', [
           'event' => $event,
                ]);
    }


}
