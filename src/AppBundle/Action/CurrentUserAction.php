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

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;

class CurrentUserAction extends BaseAction
{
    /**
     * @Route(
     *     name="currentUserAPI",
     *     path="/current-user",
     *     defaults={
     *          "_api_resource_class"=User::class,
     *          "_api_collection_operation_name"="api_current_user"
     *     },
     *
     * )
     * @Method({"GET"})
     * @Security("has_role('ROLE_USER')")
     */
    public function __invoke()
    {
        return  $this->getUser() ;
    }
}
