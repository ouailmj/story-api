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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SignUpAction extends Controller
{
    /**
     * @param User        $data
     * @param UserManager $userManager
     *
     * @return JsonResponse
     *
     * @Route(
     *     name="signUpAPI",
     *     path="/users/sign-up",
     *     defaults={
     *          "_api_resource_class"=User::class,
     *
     *          "_api_collection_operation_name"="api_sign_up"
     *     },
     * )
     * @Method({"POST"})
     *
     */
    public function __invoke(User $data, UserManager $userManager)
    {

        $user = $userManager->createUser($data);

        $jwtManager = $this->container->get('lexik_jwt_authentication.jwt_manager');

        return new JsonResponse(['token' => $jwtManager->create($user)]);
    }
}
