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

class ProfileController extends BaseController
{
    /**
     * @Route("profile", name="client_profile_edit")
     */
    public function editAction()
    {

        return $this->render('client/profile/index.html.twig', [

        ]);
    }

    /**
     * @Route("change_password", name="client_profile_password")
     */
    public function passwordAction()
    {

        return $this->render('client/profile/change_password.html.twig', [

        ]);
    }

}