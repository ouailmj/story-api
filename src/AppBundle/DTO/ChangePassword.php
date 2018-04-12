<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 11/04/2018
 * Time: 23:01
 */

namespace AppBundle\DTO;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ChangePassword
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
class ChangePassword
{
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
     */
    public $oldPassword;

    /**
     * @var string
     *
     * @Assert\NotBlank
     */
    public $repeatedPassword;
}