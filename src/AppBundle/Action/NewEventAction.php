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
use AppBundle\Model\EventManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class NewEventAction extends BaseAction
{
    /**
     * @Route(
     *     name="newEventAPI",
     *     path="/event/new",
     *     defaults={
     *          "_api_resource_class"=Event::class,
     *          "_api_collection_operation_name"="api_new_event"
     *     },
     *
     * )
     * @Method({"GET"})
     * @Security("has_role('ROLE_USER')")
     *
     * @param EventManager $eventManager
     * @return mixed
     */
    public function __invoke(EventManager $eventManager)
    {
        /**  @var Event $appEvent */
        $appEvent = $eventManager->lastIncompleteEvent($this->getUser());
        if( $appEvent !== null)
        {
            $res =  new JsonResponse([
                //TODO: Translate
                'message' => 'access denied to create a new event, required to publish last event',
                'appEventURI' => "/api/events/".$appEvent->getId()
            ]);
            $res->setStatusCode(403);
            return $res;
        }

        $event = $eventManager->createEventWithFreePlan( $this->getUser(), new Event() , false);
        $event->setCurrentStep('choose-plan');
        $eventManager->getEntityManager()->flush();
        return new JsonResponse([
            'message' => 'Your event has been create successfully',
            'appEventURI' => "/api/events/".$event->getId()
        ]);
    }
}