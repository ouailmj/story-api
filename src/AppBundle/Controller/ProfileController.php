<?php

/*
 * This file is part of the Instan't App project.
 *
 * (c) Instan't App <contact@instant-app.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller;

use AppBundle\Model\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends BaseController
{
    /**
     * @Route("/auth/profile/delete")
     */
    public function deleteAction(Request $request, UserManager $userManager)
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', ['id' => $this->getUser()->getId()]))
            ->setMethod('DELETE')
            ->getForm()
        ;
        $form->handleRequest($request);

        $userManager->deleteUser($this->getUser());

        return $this->redirectToRoute('app_default_index');
    }
}
