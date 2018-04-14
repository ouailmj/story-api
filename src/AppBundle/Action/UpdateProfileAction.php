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
use AppBundle\Model\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     *     path="/update-profile",
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
    public function __invoke($data, UserManager $userManager)
    {
        $this->getUser()->setPhoneNumber($data->getPhoneNumber());
        $this->getUser()->setFullName($data->getFullName());
        $this->getUser()->setUsername($data->getUsername());
        $this->getUser()->setEmail($data->getEmail());
        $this->getUser()->setTimezoneId($data->getTimezoneId());

        $userManager->updateUser($this->getUser());

        $jwtManager = $this->container->get('lexik_jwt_authentication.jwt_manager');

        return new JsonResponse(['token' => $jwtManager->create($this->getUser())]);
    }
}
