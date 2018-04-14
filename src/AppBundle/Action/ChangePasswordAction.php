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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ChangePasswordAction extends Controller
{
    /**
     * @Route(
     *     name="ChangePasswordAPI",
     *     path="/change-password",
     *     defaults={
     *          "_api_resource_class"=User::class,
     *          "_api_collection_operation_name"="api_change_password"
     *     },
     * )
     * @Method({"PUT"})
     * @Security("has_role('ROLE_USER')")
     *
     * @param User                         $data
     * @param Request                      $request
     * @param UserManager                  $userManager
     * @param UserPasswordEncoderInterface $encoder
     *
     * @return mixed|Response
     */
    public function __invoke(User $data, Request $request, UserManager $userManager, UserPasswordEncoderInterface $encoder)
    {
        $newPassword = json_decode($request->getContent(), true)['newPassword'];
        $oldPassword = json_decode($request->getContent(), true)['oldPassword'];

        $isCorrectPassword = $encoder->isPasswordValid($this->getUser(), $oldPassword);
        if ($isCorrectPassword) {
            $this->getUser()->setPlainPassword($newPassword);
            $userManager->updateUser($this->getUser());
        }
        $jwtManager = $this->container->get('lexik_jwt_authentication.jwt_manager');
        $response = new Response(json_encode(['isCorrectPassword' => $isCorrectPassword, 'token' => $jwtManager->create($this->getUser())]));
        $response->headers->set('Content-Type', 'application/ld+json');

        return $response;
    }
}
