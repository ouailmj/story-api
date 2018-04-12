<?php

/*
 * This file is part of the Instan't App project.
 *
 * (c) Instan't App <contact@instant-app.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
     * @param bool $flush
     * @param bool $sendMail
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
     */
    public function updatePassword(User $user, $sendMail = true)
    {
        $plainPassword = $user->getPlainPassword();

        $this->fosUserManager->updatePassword($user);

        $user->setPlainPassword($plainPassword);

        if ($sendMail) {
            $this->mailer->sendPasswordUpdatedMessage($user);
        }
    }

    /**
     * Delete user from the database.
     *
     * @param User $user
     */
    public function deleteUser(User $user, $sendMail = true)
    {
        $email=$user->getEmail();
        $this->fosUserManager->deleteUser($user);

        if ($sendMail) {
            $this->mailer->sendAccountDeletedMessage($email);
        }
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
     */
   public function updateUser(User $user)
   {
       $this->fosUserManager->updateUser($user);

   }
}
