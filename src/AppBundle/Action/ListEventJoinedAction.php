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
                     
                     'event'=>[
                        "id" => $event->getId(),
                        'CreatedBy' => [
                            "firstName" =>  $event->getCreatedBy()->getfirstName(),
                            "lastName"=> $event->getCreatedBy()->getlastName(),
                            "fullName"=>$event->getCreatedBy()->getfullName(),
                            "avatar"=> $event->getCreatedBy()->getavatar(),
                            "email"=> $event->getCreatedBy()->getemail(),
                          ],
                         
                         "startsAt"=> $event->getstartsAt(),
                         "endsAt"=> $event->getendsAt(),
                         "place"=> $event->getPlace(),
                         "description"=> $event->getDescription(),
                         'videoGallery' => $event->getVideoGallery(),
                         'imagesGallery' =>  [
                            "img1" =>  $event->getImagesGallery()[0] !== null ?  $event->getImagesGallery()[0]->getDownloadLink() : null,
                            "img2" => $event->getImagesGallery()[1] !== null  ?  $event->getImagesGallery()[1]->getDownloadLink() : null,
                            "img3" => $event->getImagesGallery()[2] !== null ?  $event->getImagesGallery()[2]->getDownloadLink() : null,
                        ]
                     ],
                     
                     
                     
                 ];
         }
        /**
         * @var $eventMemberShip MemberShip
         */
       foreach ( $user->getEventMemberShips() as $eventMemberShip)
       {
           $event =$eventMemberShip->getEvent();
           $eventJoined [] =  [
            'event'=>[
                "id" => $event->getId(),
                'CreatedBy' => [
                    "firstName" =>  $event->getCreatedBy()->getfirstName(),
                    "lastName"=> $event->getCreatedBy()->getlastName(),
                    "fullName"=>$event->getCreatedBy()->getfullName(),
                    "avatar"=> $event->getCreatedBy()->getavatar(),
                    "email"=> $event->getCreatedBy()->getemail(),
                  ],
                    
                    "startsAt"=> $event->getstartsAt(),
                    "endsAt"=> $event->getendsAt(),
                    "place"=> $event->getPlace(),
                    "description"=> $event->getDescription(),
                    'videoGallery' => $event->getVideoGallery(),
                    'imagesGallery' => 
                    [
                        "img1" =>  $event->getImagesGallery()[0] !== null ?  $event->getImagesGallery()[0]->getDownloadLink() : null,
                        "img2" => $event->getImagesGallery()[1] !== null  ?  $event->getImagesGallery()[1]->getDownloadLink() : null,
                        "img3" => $event->getImagesGallery()[2] !== null ?  $event->getImagesGallery()[2]->getDownloadLink() : null,
                    ]
                 ],
             
                   ];
       }

       return $eventJoined;
    }

}