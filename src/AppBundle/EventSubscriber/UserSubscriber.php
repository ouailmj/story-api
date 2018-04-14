<?php

/*
 * This file is part of the Instan't App project.
 *
 * (c) Instan't App <contact@instant-app.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use AppBundle\DTO\ChangePassword;
use AppBundle\DTO\ChangeProfile;
use AppBundle\DTO\ForgotPasswordRequest;
use AppBundle\Entity\User;
use AppBundle\Model\UserManager;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
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
     * @var JWTTokenManagerInterface
     */
    private $JWTTokenManager;


    /**
     * UserSubscriber constructor.
     *
     * @param TokenStorage $tokenStorage
     * @param UserManager $userManager
     * @param JWTTokenManagerInterface $JWTTokenManager
     */
    public function __construct(TokenStorage $tokenStorage, UserManager $userManager, JWTTokenManagerInterface $JWTTokenManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->userManager = $userManager;
        $this->JWTTokenManager = $JWTTokenManager;
    }


    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::VIEW      => array(
                array('handleChangePasswordRequest', EventPriorities::POST_VALIDATE),
                array('handlePasswordReset', EventPriorities::POST_VALIDATE),
                array('handleChangeProfile', EventPriorities::POST_VALIDATE)
            )
        );
    }

    /**
     * @param GetResponseForControllerResultEvent $event
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handleChangePasswordRequest(GetResponseForControllerResultEvent $event)
    {
        $request = $event->getRequest();
        if ('api_change_passwords_post_collection' !== $request->attributes->get('_route')) {
            return;
        }

        $responseData = [];

        $token = $this->tokenStorage->getToken();
        if ($token && is_object($user = $token->getUser()) && $user instanceof User){

            /** @var ChangePassword $userPassword */
            $changePassword = $event->getControllerResult();
            $user->setPlainPassword($changePassword->newPassword);
            $this->userManager->updatePassword($user);

            // TODO: Translate
            $responseData['message'] = 'Your password has been updated successfully';
        }

        $event->setResponse(new JsonResponse($responseData, 200));
    }

    /**
     * @param GetResponseForControllerResultEvent $event
     */
    public function handlePasswordReset(GetResponseForControllerResultEvent $event)
    {

        $request = $event->getRequest();
        $responseData = [];
        if ('api_forgot_password_requests_post_collection' !== $request->attributes->get('_route')) {
            return;
        }
        $token = $this->tokenStorage->getToken();
        if ($token && is_object($user = $token->getUser()) && $user instanceof User){

            /** @var ForgotPasswordRequest $changePassword */
            $changePassword = $event->getControllerResult();

            // TODO: Translate
            $responseData['message'] = $changePassword->email;



        }


        $event->setResponse(new JsonResponse($responseData, 200));
    }

    /**
     * @param GetResponseForControllerResultEvent $event
     */
    public function handleChangeProfile(GetResponseForControllerResultEvent $event)
    {

        $request = $event->getRequest();
        $responseData = [];
        if ('api_change_profiles_post_collection' !== $request->attributes->get('_route')) {
            return;
        }
        $token = $this->tokenStorage->getToken();
        if ($token && is_object($user = $token->getUser()) && $user instanceof User){

            /** @var ChangeProfile $profile */
            $profile = $event->getControllerResult();

            $user->setPhoneNumber($profile->phoneNumber);
            $user->setFullName($profile->fullName);
            $user->setUsername($profile->username);
            $user->setEmail($profile->email);
            $user->setTimezoneId($profile->timeZone);

            $this->userManager->updateUser($user);

            // TODO: Translate
            $responseData['message'] = 'Your profile has been updated successfully';
            $responseData['token'] = $this->JWTTokenManager->create($user);

        }


        $event->setResponse(new JsonResponse($responseData, 200));
    }
}