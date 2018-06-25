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
 * Class EventInformation
 * @package AppBundle\DTO
 *
 * @ApiResource(
 *      collectionOperations={
 *          "post"={
 *              "path"="/event/event-information/{id}",
 *          },},
 *      itemOperations={
 *     },
 * )
 */
final class EventInformation
{
    /**
     * @var string
     * @Assert\NotBlank
     */
    public $description;

    /**
     * @var string
     * @Assert\NotBlank
     */
    public $title;

    /**
     * @var string
     * @Assert\NotBlank
     */
    public $place;

    /**
     * @var \DateTime
     * @Assert\Date()
     */
    public $startsAt;

    /**
     * @var \DateTime
     * @Assert\Date()
     */
    public $endsAt;

    /**
     * @var int
     */
    public $idCat;

}