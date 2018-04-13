<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 11/04/2018
 * Time: 23:01
 */

namespace AppBundle\DTO;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ChangePassword
 *
 * @package AppBundle\DTO
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