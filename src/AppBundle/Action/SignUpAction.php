<?php

/**
 * Created by PhpStorm.
 * User: soufianemit
 * Date: 06/04/18
 * Time: 15:36
 */

namespace AppBundle\Action;


use AppBundle\Entity\User;
use AppBundle\Model\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class SignUpAction extends Controller
{

    /**
     *
     * @param User $data
     * @param UserManager $userManager
     * @return Response
     *
     * @Route(
     *     name="signUpAPI",
     *     path="/users/sign_up",
     *     defaults={
     *          "_api_resource_class"=User::class,
     *
     *          "_api_collection_operation_name"="api_sign_up"
     *     },
     *
     * )
     * @Method({"POST"})
     *
     */
    public function __invoke(User $data, UserManager $userManager)
    {
        $userManager->createUser($data);

        $response = new Response(json_encode($data->getId()));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}