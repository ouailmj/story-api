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


use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * Class EventCover
 * @package AppBundle\DTO
 *
 *
 * @ApiResource(
 *      collectionOperations={
 *          "post"={
 *              "path"="/event/event-cover/{id}",
 *          },},
 *      itemOperations={
 *     },
 * )
 */
final class EventCover
{

    /**
     * @var File
     *
     * @Assert\File()
     */
    public $firstImgCover;

    /**
     * @var File
     *
     * @Assert\File()
     */
    public $secondImgCover;

    /**
     * @var File
     *
     * @Assert\File()
     */
    public $thirdImgCover;

    /**
     * @var File
     *
     * @Assert\File()
     */
    public $videoCover;
}