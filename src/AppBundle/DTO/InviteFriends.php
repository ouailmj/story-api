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

use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * Class InviteFriends
 * @package AppBundle\DTO
 *
 * @ApiResource(
 *      collectionOperations={
 *          "post"={
 *              "path"="/event/invite-friends/{id}",
 *          },},
 *      itemOperations={
 *     },
 * )
 */
final class InviteFriends
{
    /**
     * @var array
     */
    public $emails;

}