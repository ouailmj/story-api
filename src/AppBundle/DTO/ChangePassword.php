<?php

/*
 * This file is part of the Instan't App project.
 *
 * (c) Instan't App <contact@instant-app.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\DTO;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ChangePassword.
 *
 * @ApiResource(
 *      collectionOperations={
 *          "post"={
 *              "path"="/me/change-password",
 *          },
 *      },
 *      itemOperations={},
 * )
 */
final class ChangePassword
{
    /**
     * @var string
     *
     * @Assert\NotBlank
     * @UserPassword
     */
    public $oldPassword;

    /**
     * @var string
     *
     * @Assert\NotBlank
     */
    public $newPassword;

    /**
     * @var string
     *
     * @Assert\NotBlank
     * @Assert\EqualTo(propertyPath="newPassword")
     */
    public $repeatedPassword;
}
