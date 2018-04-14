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
use Symfony\Component\Validator\Constraints as Assert;



/**
 * Class ForgotPasswordRequest
 *
 * @package AppBundle\DTO
 * @ApiResource(
 *      collectionOperations={
 *          "post"={
 *              "path"="/me/forgot-password-request",
 *          },
 *      },
 *      itemOperations={},
 * )
 */
final class ForgotPasswordRequest
{
    /**
     * @var string
     *
     * @Assert\NotBlank
     * @Assert\Email
     */
    public $email;
}
