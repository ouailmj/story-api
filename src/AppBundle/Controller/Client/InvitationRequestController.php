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

namespace AppBundle\Controller\Client;


use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class InvitationRequestController extends BaseController
{
    /**
     * @Route("invitations", name="client_invitation_index")
     */
    public function indexAction()
    {
       $invitations =  $this->getDoctrine()->getManager()->getRepository('AppBundle:InvitationRequest')->findBy([
           'user' => $this->getUser(),
           'isCanceled' => false,
           'isAccepted' => false,
       ]);
        return $this->render('client/invitation/index.html.twig', [
            'invitations' => $invitations,
        ]);
    }

}