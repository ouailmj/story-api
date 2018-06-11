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
use AppBundle\Entity\InvitationRequest;
use AppBundle\Model\InvitationRequestManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class InvitationRequestController
 * @package AppBundle\Controller\Client.
 *
 * @Route("app/")
 */

class InvitationRequestController extends BaseController
{
    /**
     * @Route("invitations", name="client_invitation_index")
     *
     * @param InvitationRequestManager $invitationRequestManager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(InvitationRequestManager $invitationRequestManager)
    {

       $newInvitations = $invitationRequestManager->getInvitationRequestByStatus($this->getUser());
       $invitationForms = [];
       foreach ($newInvitations as $invitation)
       {
           $invitationForms [] = $this->createAcceptInvitationForm($invitation)->createView();
       }
       $acceptedInvitations =  $invitationRequestManager->getInvitationRequestByStatus($this->getUser(), false, true);
       $canceledInvitations =  $invitationRequestManager->getInvitationRequestByStatus($this->getUser(), true, false);

       return $this->render('client/invitation/index.html.twig', [
           'newInvitations' => $newInvitations,
           'invitationForms' => $invitationForms,
           'acceptedInvitations' => $acceptedInvitations,
           'canceledInvitations' => $canceledInvitations,
       ]);
    }

    /**
     * @Route("invitations/{id}", name="client_invitation_accept")
     *
     */
    public function acceptInvitationAction(Request $request, InvitationRequest $invitationRequest,InvitationRequestManager $invitationRequestManager)
    {
        $form = $this->createAcceptInvitationForm($invitationRequest);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if($form->get('accept')->isClicked()){
                $invitationRequestManager->acceptInvitation($this->getUser(), $invitationRequest);
            }else{
                 $invitationRequestManager->cancelInvitation($invitationRequest);
            }
            $this->addSuccessFlash();

        }

        return $this->redirectToRoute('client_invitation_index');
    }


    private function createAcceptInvitationForm(InvitationRequest $invitationRequest){
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('client_invitation_accept', ['id' => $invitationRequest->getId()]))
            ->setMethod('POST')
            ->getForm()
            ->add('accept', SubmitType::class)
            ->add('denied', SubmitType::class)
            ;
    }

}