<?php

/**
 * Created by PhpStorm.
 * User: soufianemit
 * Date: 05/04/18
 * Time: 15:46
 */

namespace AppBundle\Model;

use AppBundle\Entity\User;
use AppBundle\Mailer\Mailer;
use Doctrine\ORM\EntityManager;


class UserManager
{
    /**
     * @var \FOS\UserBundle\Doctrine\UserManager
     */
    private $fosUserManager;

    /** @var Mailer */
    private $mailer;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * UserManager constructor.
     *
     * @param \FOS\UserBundle\Doctrine\UserManager $fosUserManager
     * @param EntityManager                        $em
     */
    public function __construct(\FOS\UserBundle\Doctrine\UserManager $fosUserManager, Mailer $mailer, EntityManager $em)
    {
        $this->fosUserManager = $fosUserManager;
        $this->mailer = $mailer;
        $this->em = $em;
    }

    /**
     * Create new user in the database.
     *
     * @param User $user
     */
    public function createUser(User $user, $flush = true)
    {
        $plainPassword = $user->getPlainPassword();

        $this->fosUserManager->updateUser($user, $flush);

        $user->setPlainPassword($plainPassword);
        $this->mailer->sendAccountCreatedMessage($user);
    }

    /**
     * Update user's password.
     *
     * @param User $user
     */
    public function updatePassword(User $user)
    {
        $plainPassword = $user->getPlainPassword();

        $this->fosUserManager->updatePassword($user);

        $user->setPlainPassword($plainPassword);
        $this->mailer->sendPasswordUpdatedMessage($user);
    }

    /**
     * Delete user from the database.
     *
     * @param User $user
     */
    public function deleteUser(User $user)
    {
        $this->fosUserManager->deleteUser($user);
    }


    /**
     * @return array
     */
    public function allUsers()
    {
        return $this->em->getRepository('AppBundle:User')->findAll();
    }

}