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

class InviteFriendsAction extends BaseAction
{

    /**
     * @Route(
     *     name="inviteFriends",
     *     path="/event/invite/{id}",
     *     defaults={
     *          "_api_resource_class"=Event::class,
     *          "_api_collection_operation_name"="api_show_event_invite"
     *     },
     *
     * )
     * @Method({"GET"})
     * @Security("has_role('ROLE_USER')")
     *
     */
    public function __invoke(Event $event)
    {

        $listInvites = [];

        foreach ($event->getEventMemberShips() as $invite) {
            $listInvites[]    = $invite->getMember()->getEmail();
        }

        return $listInvites;
    }


}