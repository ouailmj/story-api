<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Image
 *
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ImageRepository")
 */
class Image extends Media
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
     * @var MemberShip
     *
     * @ORM\OneToOne(targetEntity="MemberShip")
     * @ORM\JoinColumn(name="member_ship_id", referencedColumnName="id")
     *
     */
    private $uploadedBy = null;


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
     * Set uploadedBy.
     *
     * @param MemberShip $uploadedBy
     *
     * @return Image
     */
    public function setUploadedBy(MemberShip $uploadedBy)
    {
        $this->uploadedBy = $uploadedBy;

        return $this;
    }

    /**
     * Get uploadedBy.
     *
     * @return MemberShip
     */
    public function getUploadedBy()
    {
        return $this->uploadedBy;
    }
}
