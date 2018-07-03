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
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Payment
 * @package AppBundle\DTO
 * * @ApiResource(
 *      collectionOperations={
 *          "post"={
 *              "path"="/event/add-payment/{id}",
 *          },},
 *      itemOperations={
 *     },
 * )
 *
 */
final class Payment
{
    /**
     * @var integer
     * @Assert\NotBlank
     */
    public $numberCard;

    /**
     * @var integer
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 3,
     *      max = 3,
     *      minMessage = "Your first name must be at least {{ limit }} characters ",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     */
    public $cvv;

    /**
     * @var integer
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 2,
     *      max = 2,
     *      minMessage = "Your first name must be at least {{ limit }} characters ",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     */
    public $monthExpire;

    /**
     * @var integer
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 4,
     *      max = 4,
     *      minMessage = "Your first name must be at least {{ limit }} characters ",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     */
    public $yearExpire;

    /**
     * @var float
     * @Assert\NotBlank
     */
    public $price;

    //TODO: delete this when implement payment
    /**
     * @var boolean
     */
    public $isFakePayment = true;
}