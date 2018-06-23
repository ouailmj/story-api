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
        $eventData =   [
            'user' => $event->getCreatedBy(),
            'videoGallery' => $event->getVideoGallery(),
            'imagesGallery' => $event->getImagesGallery(),
            'description' => $event->getDescription(),
            'uploadedMedia' => $event->getUploadedMedias(),
        ];

        return $eventData;
    }


}