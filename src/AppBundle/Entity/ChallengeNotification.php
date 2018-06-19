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

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * Class ChallengeNotification.
 *
 *
 * @ORM\Table(name="challenge_notification")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ChallengeNotificationRepository")
 * @ApiResource()
 */
class ChallengeNotification extends BaseNotification
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Challenge
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Challenge",  mappedBy="notification", cascade={"persist", "remove"})
     */
    private $challenge;

    /**
     * @return Challenge
     */
    public function getChallenge()
    {
        return $this->challenge;
    }

    /**
     * @param Challenge $challenge
     */
    public function setChallenge(Challenge $challenge)
    {
        $this->challenge = $challenge;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function getObject()
    {
        // TODO: Implement getObject() method.
    }

    public function getSource()
    {
        // TODO: Implement getSource() method.
    }
}
