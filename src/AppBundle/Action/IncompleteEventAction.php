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


use AppBundle\Model\EventManager;
use AppBundle\Entity\Event;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class IncompleteEventAction extends BaseAction
{
    /**
     *
     * @Route(
     *     name="incompleteEventAPI",
     *     path="/event/incomplete",
     *     defaults={
     *          "_api_resource_class"=Event::class,
     *          "_api_collection_operation_name"="api_incomplete_event"
     *     },
     *
     * )
     * @Method({"GET"})
     * @Security("has_role('ROLE_USER')")
     *
     * @param EventManager $eventManager
     * @return array
     */
    public function __invoke(EventManager $eventManager)
    {
        return $eventManager->lastIncompleteEvent($this->getUser());

    }


}