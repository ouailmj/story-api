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
     */
    public function createUser(User $user, $flush = true, $sendMail = false)
    {
        $plainPassword = $user->getPlainPassword();

        $this->fosUserManager->updateUser($user, $flush);

        $user->setPlainPassword($plainPassword);

        if ($sendMail) {
            $this->mailer->sendAccountCreatedMessage($user);
        }
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
}
