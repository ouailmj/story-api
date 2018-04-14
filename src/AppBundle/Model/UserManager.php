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
use FOS\UserBundle\Event\GetResponseNullableUserEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

class UserManager
{
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

    /**
     * @var int
     */
    private $retryTtl;


    /**
     * UserManager constructor.
     *
     * @param \FOS\UserBundle\Doctrine\UserManager $fosUserManager
     * @param Mailer                               $mailer
     * @param EntityManager                        $em
     * @param JWTManager                           $jwtTokenManager
     * @param EventDispatcherInterface             $eventDispatcher
     * @param int                                  $retryTtl
     * @param TokenGeneratorInterface              $tokenGenerator
     * @param \FOS\UserBundle\Mailer\Mailer        $fos_mailer
     */
    public function __construct(\FOS\UserBundle\Doctrine\UserManager $fosUserManager, Mailer $mailer, EntityManager $em, JWTManager $jwtTokenManager, EventDispatcherInterface $eventDispatcher, $retryTtl=7200, TokenGeneratorInterface $tokenGenerator, \FOS\UserBundle\Mailer\Mailer $fos_mailer)
    {
        $this->fosUserManager = $fosUserManager;
        $this->mailer = $mailer;
        $this->em = $em;
        $this->jwtTokenManager = $jwtTokenManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->retryTtl = $retryTtl;
        $this->tokenGenerator = $tokenGenerator;
        $this->fos_mailer = $fos_mailer;
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

        $this->fosUserManager->updateUser($user, $flush);

        $user->setPlainPassword($plainPassword);

        if ($sendMail) {
            $this->mailer->sendAccountCreatedMessage($user);
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
     * @return string
     */
    public function generateToken(User $user)
    {
        return $this->jwtTokenManager->create($user);
    }

    /**
     * @param User $user
     */
    public function updateUser(User $user)
    {
        $this->fosUserManager->updateUser($user);
    }

    /**
     * @param User $user
     * @param Request $request
     * @return null|\Symfony\Component\HttpFoundation\Response
     */
   public function forgotPasswordMobile(User $user, Request $request){

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
