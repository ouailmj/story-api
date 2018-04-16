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

/**
 * InvitationRequest.
 *
 * @ORM\Table(name="invitation_request")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InvitationRequestRepository")
 */
class InvitationRequest
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sentAt", type="datetime")
     */
    private $sentAt;

    /**
     * @var bool
     *
     * @ORM\Column(name="isCanceled", type="boolean")
     */
    private $isCanceled;

    /**
     * @var string
     *
     * @ORM\Column(name="response", type="string", length=255)
     */
    private $response = null;

    /**
     * @var array
     *
     * @ORM\Column(name="channels", type="array")
     */
    private $channels = ['email'];

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set sentAt.
     *
     * @param \DateTime $sentAt
     *
     * @return InvitationRequest
     */
    public function setSentAt($sentAt)
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    /**
     * Get sentAt.
     *
     * @return \DateTime
     */
    public function getSentAt()
    {
        return $this->sentAt;
    }

    /**
     * Set isCanceled.
     *
     * @param bool $isCanceled
     *
     * @return InvitationRequest
     */
    public function setIsCanceled($isCanceled)
    {
        $this->isCanceled = $isCanceled;

        return $this;
    }

    /**
     * Get isCanceled.
     *
     * @return bool
     */
    public function getIsCanceled()
    {
        return $this->isCanceled;
    }

    /**
     * Set response.
     *
     * @param string $response
     *
     * @return InvitationRequest
     */
    public function setResponse($response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * Get response.
     *
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set channels.
     *
     * @param array $channels
     *
     * @return InvitationRequest
     */
    public function setChannels($channels)
    {
        $this->channels = $channels;

        return $this;
    }

    /**
     * Get channels.
     *
     * @return array
     */
    public function getChannels()
    {
        return $this->channels;
    }
}
