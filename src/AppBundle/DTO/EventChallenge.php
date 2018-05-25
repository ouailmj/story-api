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

/**
 * Class EventChallenge
 * @package AppBundle\DTO\Event
 *
 * @ApiResource(
 *      collectionOperations={
 *          "post"={
 *              "path"="/event/event-challenge/{id}",
 *          },},
 *      itemOperations={
 *     },
 * )
 *
 */
final class EventChallenge
{
    /**
     * @var array
     */
    public $challenges;

}