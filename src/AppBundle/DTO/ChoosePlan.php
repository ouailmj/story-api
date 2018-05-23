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
 * Class ChoosePlan
 * @package AppBundle\DTO\Event
 *
 * @ApiResource(
 *      collectionOperations={
 *          "post"={
 *              "path"="/event/choose-plan/{id}",
 *          },},
 *      itemOperations={
 *     },
 * )
 *
 */
final class ChoosePlan
{

    /**
     * @var string
     *
     * @Assert\NotBlank
     */
    public $planKey;


}