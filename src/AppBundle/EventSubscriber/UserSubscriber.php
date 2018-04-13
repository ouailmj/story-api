<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 12/04/2018
 * Time: 12:20
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
     * @param UserManager $userManager
     */
    public function __construct(TokenStorage $tokenStorage, UserManager $userManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->userManager = $userManager;
    }


    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::VIEW      => array('handleChangePasswordRequest', EventPriorities::POST_VALIDATE)
        );
    }

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
}