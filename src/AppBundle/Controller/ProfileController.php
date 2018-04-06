<?php

/**
 * Created by PhpStorm.
 * User: soufianeMIT
 * Date: 04/04/18
 * Time: 18:06
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends BaseController
{

    /**
     * @Route("/auth/profile/delete")
     */
    public function deleteAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $this->getUser()->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $em->remove($this->getUser());
        $em->flush();

        return $this->redirectToRoute('app_default_index');
    }
}