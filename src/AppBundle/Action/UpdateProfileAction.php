<?php

/*
 * This file is part of the Instan't App project.
 *
 * (c) Instan't App <contact@instant-app.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace AppBundle\Action;


use AppBundle\Entity\User;
use AppBundle\Model\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UpdateProfileAction extends Controller
{

    /**
     * @param User        $data
     * @param UserManager $userManager
     *
     * @return Response
     *
     * @Route(
     *     name="updateProfileAPI",
     *     path="/users/update-profile",
     *     defaults={
     *          "_api_resource_class"=User::class,
     *
     *          "_api_collection_operation_name"="api_update_profile"
     *     },
     *
     * )
     * @Method({"PUT"})
     * @Security("has_role('ROLE_USER')")
     */
    public function __invoke(User $data, UserManager $userManager)
    {

        $userManager->updateUser($data);

        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/ld+json');

        return $response;
    }
}