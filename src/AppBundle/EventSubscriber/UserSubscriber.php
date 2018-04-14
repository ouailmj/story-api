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

namespace AppBundle\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use AppBundle\DTO\ChangePassword;
use AppBundle\Entity\User;
use AppBundle\Model\UserManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class UserSubscriber implements EventSubscriberInterface
{
    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * UserSubscriber constructor.
     *
     * @param TokenStorage $tokenStorage
     * @param UserManager  $userManager
     */
    public function __construct(TokenStorage $tokenStorage, UserManager $userManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->userManager = $userManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['handleChangePasswordRequest', EventPriorities::POST_VALIDATE],
        ];
    }

    public function handleChangePasswordRequest(GetResponseForControllerResultEvent $event)
    {
        $request = $event->getRequest();
        if ('api_change_passwords_post_collection' !== $request->attributes->get('_route')) {
            return;
        }

        $responseData = [];

        $token = $this->tokenStorage->getToken();
        if ($token && is_object($user = $token->getUser()) && $user instanceof User) {
            /** @var ChangePassword $userPassword */
            $changePassword = $event->getControllerResult();
            $user->setPlainPassword($changePassword->newPassword);
            $this->userManager->updatePassword($user);

            // TODO: Translate
            $responseData['message'] = 'Your password has been updated successfully';
        }

        $event->setResponse(new JsonResponse($responseData, 200));
    }
}
