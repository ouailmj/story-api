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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;

class ShowSingleEventAction extends BaseAction
{

    /**
     * @Route(
     *     name="showEventAPI",
     *     path="/show-event/{id}",
     *     defaults={
     *          "_api_resource_class"=Event::class,
     *          "_api_collection_operation_name"="api_show_event"
     *     },
     *
     * )
     * @Method({"GET"})
     * @Security("has_role('ROLE_USER')")
     */
    public function __invoke(Event $event)
    {

      

            $loadedMedias=[];
            
            foreach($event->getuploadedMedias() as $uploadMedia){

                $loadedMedias[] = [
                    "postImageUrl" => $uploadMedia->getdownloadLink(),
                    "date" =>  $uploadMedia->getuploadedAt(),
                    "avatar" => $uploadMedia->getcreatedBy()->getavatar() === null ? null : $uploadMedia->getcreatedBy()->getavatar()->getdownloadLink(),
                    "userName" => $uploadMedia->getcreatedBy()->getfullName(),
                ];

            }
        

        $eventData =   [
            'event'=>[
                "id" => $event->getId(),
                'CreatedBy' => [
                    "firstName" =>  $event->getCreatedBy()->getfirstName(),
                    "lastName"=> $event->getCreatedBy()->getlastName(),
                    "fullName"=>$event->getCreatedBy()->getfullName(),
                    "avatar"=> [
                       "downloadLink" =>  $event->getCreatedBy()->getavatar() !== null ?  $event->getCreatedBy()->getavatar()->getDownloadLink() : null
                    ],
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
                 ],
                 'loadedMedias' => $loadedMedias
                 
             ],
        ];

        return $eventData;
    }


}