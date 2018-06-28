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

namespace AppBundle\Action;

use AppBundle\Entity\Event;
use AppBundle\Entity\MemberShip;
use AppBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;

class ListEventJoinedAction extends BaseAction
{
    /**
     * @Route(
     *     name="eventJoinedAPI",
     *     path="/event-joined",
     *     defaults={
     *          "_api_resource_class"=Event::class,
     *          "_api_collection_operation_name"="api_event_joined"
     *     },
     *
     * )
     * @Method({"GET"})
     * @Security("has_role('ROLE_USER')")
     */
    public function __invoke()
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        $eventJoined = [];

        /**
         * @var $event Event
         */
         foreach ( $user->getCreatedEvents() as $event)
         {
             $eventJoined [] =  [
                     'user' => $event->getCreatedBy(),
                     'event' => $event,
                     'videoGallery' => $event->getVideoGallery(),
                     'imagesGallery' => $event->getImagesGallery(),
                     'description' => $event->getDescription(),
                 ];
         }
        /**
         * @var $eventMemberShip MemberShip
         */
       foreach ( $user->getEventMemberShips() as $eventMemberShip)
       {
           $event =$eventMemberShip->getEvent();
           $eventJoined [] =  [
                       'user' => $event->getCreatedBy(),
                       'event' => $event,
                       'videoGallery' => $event->getVideoGallery(),
                       'imagesGallery' => $event->getImagesGallery(),
                       'description' => $event->getDescription(),
                   ];
       }

       return $eventJoined;
    }

}