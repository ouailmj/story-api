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

namespace AppBundle\Model;

use AppBundle\Entity\User;
use AppBundle\Mailer\Mailer;
use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Doctrine\UserManager as FOSUserManager;
use FOS\UserBundle\Event\GetResponseNullableUserEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Mailer\Mailer as FOSMailer;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserManager
{
    protected static $defaultAvatar = __DIR__.'/../../../web/assets/images/avatar.png';

    /**
     * @var \FOS\UserBundle\Doctrine\UserManager
     */
    private $fosUserManager;

    /** @var Mailer */
    private $mailer;

    /** @var \FOS\UserBundle\Mailer\Mailer */
    private $fos_mailer;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var JWTManager
     */
    private $jwtTokenManager;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var TokenGeneratorInterface
     */
    private $tokenGenerator;

    /** @var MediaManager */
    private $mediaManager;

    /**
     * @var int
     */
    private $retryTtl;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /**
     * UserManager constructor.
     *
     * @param FOSUserManager          $fosUserManager
     * @param Mailer                  $mailer
     * @param FOSMailer               $fos_mailer
     * @param EntityManager           $em
     * @param JWTManager              $jwtTokenManager
     * @param TokenGeneratorInterface $tokenGenerator
     * @param TokenStorageInterface   $tokenStorage
     * @param MediaManager            $mediaManager
     * @param EventDispatcher         $eventDispatcher
     */
    public function __construct(
        FOSUserManager $fosUserManager,
        Mailer $mailer,
        FOSMailer $fos_mailer,
        EntityManager $em,
        JWTManager $jwtTokenManager,
        TokenGeneratorInterface $tokenGenerator,
        TokenStorageInterface $tokenStorage,
        MediaManager $mediaManager,
        EventDispatcher $eventDispatcher
    ) {
        $this->fosUserManager = $fosUserManager;
        $this->mailer = $mailer;
        $this->fos_mailer = $fos_mailer;
        $this->em = $em;
        $this->jwtTokenManager = $jwtTokenManager;
        $this->tokenGenerator = $tokenGenerator;
        $this->tokenStorage = $tokenStorage;
        $this->mediaManager = $mediaManager;

        $this->eventDispatcher = $eventDispatcher;
        $this->retryTtl = 7200;
    }

    /**
     * Create new user in the database.
     *
     * @param User $user
     * @param bool $flush
     * @param bool $sendMail
     *
     * @return User
     */
    public function createUser(User $user, $flush = true, $sendMail = false)
    {
        $plainPassword = $user->getPlainPassword();

        if (!$user->getAvatar()) {
            $avatar = $this->mediaManager->createMediaFromLocalFile(static::$defaultAvatar);
            $user->setAvatar($avatar);
        }

        $this->fosUserManager->updateUser($user, $flush);

        $user->setPlainPassword($plainPassword);

        if ($sendMail) {
            $this->mailer->sendAccountCreatedMessage($user);
        }

        return $user;
    }

    /**
     * @param string $email
     * @param string $username
     * @param string $plainPassword
     *
     * @return User|\FOS\UserBundle\Model\UserInterface|null
     */
    public function createUserFromEmail(string $email, string $username = '', string $plainPassword = '')
    {
        if (empty($username)) {
            $username = $email;
        }

        $user = $this->fosUserManager->findUserByUsernameOrEmail($email);
        if (empty($user)) {
            $user = $this->fosUserManager->findUserByUsername($username);
        }

        if (empty($user)) {
            $user = new User();
            $user->setEmail($email);
            $user->setPlainPassword($plainPassword);
            $user->setUsername($username);
            $user = $this->createUser($user);
        }

        return $user;
    }

    /**
     * @param User $user
     * @param bool $sendMail
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updatePassword(User $user, $sendMail = true)
    {
        $plainPassword = $user->getPlainPassword();
        $this->fosUserManager->updatePassword($user);
        $this->em->flush();
        $user->setPlainPassword($plainPassword);
        if ($sendMail) {
            $this->mailer->sendPasswordUpdatedMessage($user);
        }
        $user->eraseCredentials();
    }

    /**
     * Delete user from the database.
     *
     * @param User $user
     */
    public function deleteUser(User $user, $sendMail = true)
    {
        $email = $user->getEmail();
        $this->fosUserManager->deleteUser($user);
    }

    /**
     * @return array
     */
    public function allUsers()
    {
        return $this->em->getRepository('AppBundle:User')->findAll();
    }

    /**
     * @param User $user
     * @param $newTimezoneId
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @return User
     */
    public function updateTimezoneId(User $user, $newTimezoneId)
    {
        $user->setTimezoneId($newTimezoneId);
        $this->em->flush();

        return $user;
    }

    /**
     * @param User $user
     *
     * @return string
     */
    public function generateToken(User $user)
    {
        return $this->jwtTokenManager->create($user);
    }

    /**
     * @return User | mixed
     */
    public function getLoggedInUser()
    {
        if (null === $token = $this->tokenStorage->getToken()) {
            return;
        }
        if (!is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return;
        }

        return $user;
    }

    /**
     * @param User $user
     */
    public function updateUser(User $user)
    {
        $this->fosUserManager->updateUser($user);
    }

    /**
     * @return \FOS\UserBundle\Model\UserManager
     */
    public function getFOSUserManager(): \FOS\UserBundle\Model\UserManager
    {
        return $this->fosUserManager;
    }

    /**
     * @param string  $email
     * @param Request $request
     *
     * @return null|\Symfony\Component\HttpFoundation\Response
     */
    public function forgotPasswordMobile($email, Request $request)
    {
        $user = $this->em->getRepository('AppBundle:User')->findOneBy(['email' => $email]);

        $event = new GetResponseNullableUserEvent($user, $request);
        $this->eventDispatcher->dispatch(FOSUserEvents::RESETTING_SEND_EMAIL_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        if (null !== $user && !$user->isPasswordRequestNonExpired($this->retryTtl)) {
            $event = new GetResponseUserEvent($user, $request);
            $this->eventDispatcher->dispatch(FOSUserEvents::RESETTING_RESET_REQUEST, $event);

            if (null !== $event->getResponse()) {
                return $event->getResponse();
            }

            if (null === $user->getConfirmationToken()) {
                $user->setConfirmationToken($this->tokenGenerator->generateToken());
            }

            $event = new GetResponseUserEvent($user, $request);
            $this->eventDispatcher->dispatch(FOSUserEvents::RESETTING_SEND_EMAIL_CONFIRM, $event);

            if (null !== $event->getResponse()) {
                return $event->getResponse();
            }

            $this->fos_mailer->sendResettingEmailMessage($user);
            $user->setPasswordRequestedAt(new \DateTime());
            $this->updateUser($user);

            $event = new GetResponseUserEvent($user, $request);
            $this->eventDispatcher->dispatch(FOSUserEvents::RESETTING_SEND_EMAIL_COMPLETED, $event);

            if (null !== $event->getResponse()) {
                return $event->getResponse();
            }
        }
    }
}
