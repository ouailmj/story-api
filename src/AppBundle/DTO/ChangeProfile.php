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

namespace AppBundle\DTO;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ChangeProfile.
 *
 * @ApiResource(
 *      collectionOperations={
 *          "post"={
 *              "path"="/me/change-profile",
 *          },
 *      },
 *      itemOperations={},
 * )
 */
final class ChangeProfile
{
    /**
     * @var string
     *
     * @Assert\NotBlank
     */
    public $username;

    /**
     * @var string
     *
     * @Assert\NotBlank
     */
    public $fullName;

    /**
     * @var string
     *
     * @Assert\NotBlank
     * @Assert\Email
     */
    public $email;

    /**
     * @var string
     *
     * @Assert\NotBlank
     */
    public $phoneNumber;

    /**
     * @var string
     *
     * @Assert\NotBlank
     */
    public $timeZone;

    /**
     * @var string
     *
     * @Assert\NotBlank
     * @UserPassword
     */
    public $password;
}
