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

use AppBundle\Model\Trashable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Media.
 *
 * @ORM\Table(name="media")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MediaRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *     "media"="Media",
 *     "image"="Image",
 *     "video"="Video"
 *     })
 *
 * @ORM\HasLifecycleCallbacks()
 */
class Media implements Trashable
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
     * @var string
     *
     * @ORM\Column(type="string", length=500)
     */
    private $downloadLink = '';

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=500)
     */
    private $file = '';

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $expiresAt;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $hasBeenDownloaded = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetimetz")
     */
    private $uploadedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $trashedAt = null;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="medias")
     */
    private $createdBy;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=500)
     */
    private $src;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=10)
     */
    private $type = '';

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
     * Set downloadLink.
     *
     * @param string $downloadLink
     *
     * @return Media
     */
    public function setDownloadLink($downloadLink)
    {
        $this->downloadLink = $downloadLink;

        return $this;
    }

    /**
     * Get downloadLink.
     *
     * @return string
     */
    public function getDownloadLink()
    {
        return $this->downloadLink;
    }

    /**
     * Set file.
     *
     * @param string $file
     *
     * @return Media
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file.
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set expiresAt.
     *
     * @param \DateTime $expiresAt
     *
     * @return Media
     */
    public function setExpiresAt($expiresAt)
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    /**
     * Get expiresAt.
     *
     * @return \DateTime
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * Set hasBeenDownloaded.
     *
     * @param bool $hasBeenDownloaded
     *
     * @return Media
     */
    public function setHasBeenDownloaded($hasBeenDownloaded)
    {
        $this->hasBeenDownloaded = $hasBeenDownloaded;

        return $this;
    }

    /**
     * Get hasBeenDownloaded.
     *
     * @return bool
     */
    public function getHasBeenDownloaded()
    {
        return $this->hasBeenDownloaded;
    }

    /**
     * Set uploadedAt.
     *
     * @param \DateTime $uploadedAt
     *
     * @return Media
     */
    public function setUploadedAt($uploadedAt)
    {
        $this->uploadedAt = $uploadedAt;

        return $this;
    }

    /**
     * Get uploadedAt.
     *
     * @return \DateTime
     */
    public function getUploadedAt()
    {
        return $this->uploadedAt;
    }

    /**
     * Set createdBy.
     *
     * @param User $createdBy
     *
     * @return Media
     */
    public function setCreatedBy(User $createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy.
     *
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set src.
     *
     * @param string $src
     *
     * @return Media
     */
    public function setSrc($src)
    {
        $this->src = $src;

        return $this;
    }

    /**
     * Get src.
     *
     * @return string
     */
    public function getSrc()
    {
        return $this->src;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return Media
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return \DateTime
     */
    public function getTrashedAt(): \DateTime
    {
        return $this->trashedAt;
    }

    /**
     * @param \DateTime $trashedAt
     *
     * @return Media
     */
    public function setTrashedAt(\DateTime $trashedAt): self
    {
        $this->trashedAt = $trashedAt;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setUploadedAtValue()
    {
        if (empty($this->uploadedAt)) {
            $tz = ($this->getCreatedBy() instanceof User)
                ? $this->getCreatedBy()->getTimeZoneInstance()
                : new \DateTimeZone(date_default_timezone_get());
            $this->uploadedAt = new \DateTime($tz);
        }
    }
}
