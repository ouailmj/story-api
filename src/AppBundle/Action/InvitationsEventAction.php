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

class InvitationsEventAction extends BaseAction
{

      /**
     * @Route(
     *     name="invitations_event",
     *     path="/event/invitations_event/{id}",
     *     defaults={
     *          "_api_resource_class"=Event::class,
     *          "_api_collection_operation_name"="api_show_invitations_event"
     *     },
     *
     * )
     * @Method({"GET"})
     * @Security("has_role('ROLE_USER')")
     *
     */
    public function __invoke(Event $event)
    {

        $list = [];

        foreach ($event->getInvitationRequests() as  $value) {
            $isCanceled = $value->getIsCanceled();
            $isAccepted = $value->isAccepted();

            if( $isCanceled)
            $etat = 'Canceled';
            elseif($isAccepted) 
            $etat = 'Accepted';
            else
            $etat = 'Att';

           $list [] =     [
            
               'etat'=>$etat,
               'email'=> $value->getChannels()['email']
           ]; 
        }

        
        return $list;
    }


}