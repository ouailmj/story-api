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
use Symfony\Component\Serializer\Serializer;

class CurrentUserAction extends Controller
{

    /**
     *
     * @return Response
     *
     * @Route(
     *     name="currentUserAPI",
     *     path="/users/current",
     *     defaults={
     *          "_api_resource_class"=User::class,
     *
     *          "_api_collection_operation_name"="api_current_user"
     *     },
     *
     * )
     * @Method({"GET"})
     * @Security("has_role('ROLE_USER')")
     *
     */
    public function __invoke(Serializer $serializer)
    {
        $user = $serializer->serialize($this->getUser(), 'json');

        $response = new Response($user);

        $response->headers->set('Content-Type', 'application/ld+json');

        return $response;
    }
}